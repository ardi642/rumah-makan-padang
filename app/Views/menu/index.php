<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-5 p-5 border border-1 rounded">
  <h5 class="text-center mb-4">Tabel Menu Makanan</h5>
  <div class="text-center mb-5">
    <a class="btn btn-success" href="/menu/tambah" role="button">
      Tambah Menu Makanan
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
        data: "id_menu",
        name: 'id_menu',
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
        data: "kategori",
        name: 'kategori',
      },
      {
        data: "nama_menu",
        name: 'nama_menu',
      },
      {
        data: "harga",
        name: 'harga'
      },
      {
        orderable: false,
        render: function(data, type, row, meta) {
          return `
            <a href="menu/edit/${row.id_menu}" class="btn btn-sm btn-warning">Edit</a>
            <button class="btn btn-sm btn-danger tombol-hapus" data-row-index="${meta.row}">
            Delete
            </button>
                `;
        }
      }
    ],
    ajax: {
      url: '/api/menu/selectDatatable'
    }
  });

  $('#tabel').on('click', '.tombol-hapus', async function(e) {
    let rowIndex = $(this).data('rowIndex');
    let rowData = tabel.row(rowIndex).data();
    let status;
    let keputusan = await Swal.fire({
      title: 'Apakah Anda yakin?',
      text: `Anda akan menghapus ${rowData.kategori} ${rowData.nama_menu} pada daftar menu ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    })

    if (!keputusan.isConfirmed) return;

    try {
      const response = await axios.delete(`/api/menu/${rowData.id_menu}`);
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
          title: `data ${rowData.kategori} ${rowData.nama_menu} berhasil dihapus`
        })

      }

      if (status == 'gagal') {
        Toast.fire({
          icon: 'error',
          title: `data ${rowData.kategori} ${rowData.nama_menu} gagal dihapus`
        })
      }
    }
  })
</script>

<?= $this->endSection() ?>