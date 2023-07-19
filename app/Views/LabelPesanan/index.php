<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-5 p-5 border border-1 rounded">
  <h5 class="text-center mb-4">Tabel Label Pesanan</h5>
  <div class="text-center mb-5">
    <a class="btn btn-success" href="/LabelPesanan/tambah" role="button">
      Tambah Label Pesanan
    </a>
  </div>
  <div class="table-responsive">
    <table id="tabel" class="table table-hover table-striped dataTable">
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
    timer: 2000,
    timerProgressBar: true
  })

  let tabel = $('#tabel').DataTable({
    processing: true,
    serverSide: true,
    columns: [{
        data: "id_label",
        name: 'id_label',
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
        data: "label",
        name: 'label',
      },
      {
        orderable: false,
        render: function(data, type, row, meta) {
          return `
            <a href="LabelPesanan/edit/${row.id_label}" class="btn btn-sm btn-warning">Edit</a>
            <button class="btn btn-sm btn-danger tombol-hapus" data-row-index="${meta.row}">
            Delete
            </button>
                `;
        }
      }
    ],
    ajax: {
      url: '/api/LabelPesanan/selectDatatable'
    }
  });

  $('#tabel').on('click', '.tombol-hapus', async function(e) {
    let rowIndex = $(this).data('rowIndex');
    let rowData = tabel.row(rowIndex).data();
    let status;
    let keputusan = await Swal.fire({
      title: 'Apakah Anda yakin?',
      text: `Anda akan menghapus label pesanan ${rowData.label} ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    })

    if (!keputusan.isConfirmed) return;

    try {
      const response = await axios.delete(`/api/LabelPesanan/${rowData.id_label}`);
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
          title: `label pesanan ${rowData.label} berhasil dihapus`
        })

      }

      if (status == 'gagal') {
        Toast.fire({
          icon: 'error',
          title: `label pesanan ${rowData.label} gagal dihapus`
        })
      }
    }
  })
</script>

<?= $this->endSection() ?>