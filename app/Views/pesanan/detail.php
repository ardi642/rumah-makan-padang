<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-4 text-center">
  <a class="btn btn-primary" href="/pesanan" role="button">Kembali</a>
</div>
<div class="container mt-5 p-5 border border-1 rounded" x-data>
  <h4 class="text-center mb-4">Rincian Pesanan</h4>
  <table class="table table-hover table-bordered">
    <thead class="text-center">
      <tr>
        <td>No</td>
        <td>Menu</td>
        <td>harga</td>
        <td>jumlah</td>
        <td>sub total Bayar</td>
      </tr>
    </thead>
    <tbody class="text-center">
      <template x-for="(pesanan, index) in $store.pesananMasuk.pesanans">
        <tr>
          <td><span x-text="index + 1"></span></td>
          <td><span x-text="pesanan.nama_menu"></span></td>
          <td>Rp. <span x-text="pesanan.harga_tertentu"></span></td>
          <td><span x-text="pesanan.jumlah"></span></td>
          <td>Rp. <span x-text="pesanan.harga_tertentu * pesanan.jumlah"></span></td>
        </tr>
      </template>
    </tbody>
  </table>
  <div class="row mt-4">
    <div class="col-6 col-md-2 ms-md-auto">Id Pesanan</div>
    <div class="col-6 col-md-2">
      <span x-text="$store.pesananMasuk.id_pesanan"></span>
    </div>
  </div>
  <div class="row">
    <div class="col-6 col-md-2 ms-md-auto">Label Pesanan</div>
    <div class="col-6 col-md-2">
      <span x-text="$store.pesananMasuk.label"></span>
    </div>
  </div>
  <div class="row">
    <div class="col-6 col-md-2 ms-md-auto">Total Bayar</div>
    <div class="col-6 col-md-2">
      Rp. <span x-text="$store.pesananMasuk.total_bayar"></span>
    </div>
  </div>
  <div class="row">
    <div class="col-6 col-md-2 ms-md-auto">Uang Pelanggan</div>
    <div class="col-6 col-md-2">
      Rp. <span x-text="$store.pesananMasuk.uang_pelanggan"></span>
    </div>
  </div>
  <div class="row">
    <div class="col-6 col-md-2 ms-md-auto">Uang Kembalian</div>
    <div class="col-6 col-md-2">
      Rp. <span x-text="$store.pesananMasuk.uang_kembalian"></span>
    </div>
  </div>
  <div class="row">
    <div class="col-6 col-md-2 ms-md-auto">Waktu Pesanan</div>
    <div class="col-6 col-md-2">
      <span x-text="$store.pesananMasuk.waktu"></span>
    </div>
  </div>
  <template x-if="$store.pesananMasuk.waktu_update != null">
    <div class="row">
      <div class="col-6 col-md-2 ms-md-auto">Waktu Update Pesanan</div>
      <div class="col-6 col-md-2">
        <span x-text="$store.pesananMasuk.waktu_update"></span>
      </div>
    </div>
  </template>
</div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })

  document.addEventListener('alpine:init', () => {
    Alpine.store('pesananMasuk', {});
    loadDataPesanan();
  })

  async function loadDataPesanan() {
    const storePesananMasuk = Alpine.store('pesananMasuk');
    let idPesanan = <?= $idPesanan ?>;
    try {
      const response = await axios.get(`/api/dataPesanan/${idPesanan}`);
      const pesananMasuk = response.data.pesananMasuk;
      if (pesananMasuk.waktu != null) {
        pesananMasuk.waktu = dayjs(pesananMasuk.waktu).format("DD MMMM YYYY - HH:mm");
      }
      if (pesananMasuk.waktu_update != null) {
        pesananMasuk.waktu_update = dayjs(pesananMasuk.waktu_update).format("DD MMMM YYYY - HH:mm");
      }
        
      await Object.assign(storePesananMasuk, pesananMasuk);
    } catch (error) {
      const statusCode = error.response?.status;
      const data = error.response?.data;
      if (statusCode == 400) {} else if (statusCode == 500) {

      }
    } finally {

    }
  }
</script>
<?= $this->endSection() ?>