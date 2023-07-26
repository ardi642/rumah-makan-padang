<?php
$kolomTabel = [
  'no', 'id pesanan', 'banyak menu', 'banyak porsi', 'label pesanan',
  'uang pelanggan', 'total_bayar', 'uang_kembalian', 'waktu', 'aksi'
];
?>

<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-5 p-5 border border-1 rounded" x-data>
  <h5 class="text-center mb-4">Tabel Pesanan</h5>
  <div class="text-center mb-5">
    <a class="btn btn-success" href="/pesanan/tambah" role="button">
      Tambah Pesanan
    </a>
  </div>
  <div class="row mb-4">
    <div class="col-12 col-md-auto mb-2 mb-md-0">Rentang Waktu :</div>
    <div class="col-12 col-md-auto mb-2 mb-md-0">
      <input type="date" class="form-control" :value="getDate()" id="tanggalDari" @change="tabel.draw()">
    </div>
    <div class="col-12 col-md-auto mb-2 mb-md-0">sampai</div>
    <div class="col-12 col-md-auto mb-2 mb-md-0">
      <input type="date" class="form-control" :value="getDate()" id="tanggalSampai" @change="tabel.draw()">
    </div>
    <div x-init="tabel.draw()"></div>
  </div>
  <div class="table-responsive">
    <table id="tabel" class="table table-striped  dataTable">
      <thead>
        <tr>
          <?php foreach ($kolomTabel as $kolom) : ?>
            <th><?= $kolom ?></th>
          <?php endforeach ?>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <tr>
          <?php foreach ($kolomTabel as $kolom) : ?>
            <th><?= $kolom ?></th>
          <?php endforeach ?>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
  })

  function getDate(dateString = null) {
    let dateObj;
    if (dateString == null) {
      dateObj = new Date();
    } else {
      const dateTime = Date.parse(dateString);
      dateObj = new Date(dateTime);
    }
    let date = dateObj.getDate() + '';
    let month = (dateObj.getMonth() + 1) + '';
    let year = dateObj.getFullYear() + '';

    date = date.length == 1 ? '0' + date : date;
    month = month.length == 1 ? '0' + month : month;

    return `${year}-${month}-${date}`;
  }

  let tabel = $('#tabel').DataTable({
    order: [
      [8, 'desc']
    ],
    responsive: true,
    processing: true,
    serverSide: true,
    ajax: {
      url: '/api/pesanan/selectDatatable',
      data: function(d) {
        let tanggal_dari = $('#tanggalDari').val();
        let tanggal_sampai = $('#tanggalSampai').val();

        if (tanggal_dari != "")
          d.tanggal_dari = tanggal_dari;

        if (tanggal_sampai != "")
          d.tanggal_sampai = tanggal_sampai;
      },
    },
    columnDefs: [{
        responsivePriority: 0,
        targets: 1
      },
      {
        responsivePriority: 1,
        targets: 4
      },
      {
        responsivePriority: 3,
        targets: 6
      },
      {
        responsivePriority: 2,
        targets: 8
      },
    ],
    columns: [{
        data: "id_pesanan",
        render: function(data, type, row, meta) {
          let length = meta.settings.fnRecordsDisplay();
          let orderDirection = meta.settings.aaSorting[0][1];
          if (orderDirection == 'asc')
            return meta.row + 1;
          else
            return length - (meta.row);
        }
      },
      {
        data: "id_pesanan"
      },
      {
        data: "banyak_menu"
      },
      {
        data: "banyak_porsi"
      },
      {
        data: "label"
      },
      {
        data: "uang_pelanggan"
      },
      {
        data: "total_bayar"
      },
      {
        data: "uang_kembalian"
      },
      {
        data: "waktu",
        render: function(data, type, row, meta) {
          return dayjs(data).format("DD MMMM YYYY");
        }
      },
      {
        data: null,
        orderable: false,
        render: function(data, type, row, meta) {
          let tanggalSekarang = getDate();
          let tanggalPesanan = getDate(row.waktu);
          return `
          <a href = "pesanan/detail/${row.id_pesanan}" class = "btn btn-sm btn-success">Detail</a>
          ${tanggalSekarang == tanggalPesanan ? 
            `<a href = "pesanan/edit/${row.id_pesanan}" class = "btn btn-sm btn-warning">Edit</a>` 
            : ""}
          <button class = "btn btn-sm btn-danger tombol-hapus" data-row-index = "${meta.row}">
            Delete 
          </button>
          `;
        }
      }
    ]
  });

  $('#tabel').on('click', '.tombol-hapus', async function(e) {
    let rowIndex = $(this).data('rowIndex');
    let rowData = tabel.row(rowIndex).data();
    let status;
    console.log(rowData);
    let keputusan = await Swal.fire({
      title: 'Apakah Anda yakin?',
      text: `Anda akan menghapus pesanan dengan id pesanan ${rowData.id_pesanan} ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    })

    if (!keputusan.isConfirmed) return;

    try {
      const response = await axios.delete(`/api/pesanan/${rowData.id_pesanan}`);
      status = 'sukses';
    } catch (error) {
      console.log(error);
      const statusCode = error.response?.status;
      const data = error.response?.data;
      status = 'gagal';
    } finally {
      if (status == 'sukses') {
        tabel.draw();
        Toast.fire({
          icon: 'success',
          title: `Pesanan dengan id pesanan ${rowData.id_pesanan} berhasil dihapus`
        })

      }

      if (status == 'gagal') {
        Toast.fire({
          icon: 'error',
          title: `Karyawan dengan username ${rowData.username} gagal dihapus`
        })
      }
    }
  })
</script>

<?= $this->endSection() ?>