<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-4 text-center">
  <a class="btn btn-primary" href="/pesanan" role="button">Kembali</a>
</div>
<form method="post" class="container mt-4 p-5 border border-1 rounded" id="form" x-data>
  <div x-init="$watch('$store.form.pesanans', sinkronSelectPesanan)"></div>
  <div x-init="$watch('$store.form.id_label', sinkronSelectLabel)"></div>
  <div x-init="$watch('$store.form.pesanans', sinkronRincianPembayaran)"></div>
  <h5 class="text-center mb-5">Form Edit Pesanan</h5>
  <template x-for="(pesanan, index) in $store.form.pesanans">
    <div class="mb-3">
      <div class="row border border-1 rounded py-4 px-3">
        <p class="mb-0" x-text="`pesanan ke ${index + 1}`"></p>
        <div class="col-12 col-md my-2">
          <select class="select-pesanan form-control" x-init="initSelectPesanan" :data-index="index">
            <option value=""></option>
          </select>
          <template x-if="$store.validasi.validasiPesanans?.[index]?.id_menu != null">
            <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.validasiPesanans?.[index]?.id_menu">
            </div>
          </template>
        </div>
        <div class="col-12 col-md my-2">
          <input type="number" class="form-control" placeholder="jumlah pesanan" x-model="pesanan.jumlah" @keyup="resetValidasiJumlah">
          <template x-if="$store.validasi.validasiPesanans?.[index]?.jumlah != null">
            <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.validasiPesanans?.[index]?.jumlah">
            </div>
          </template>
        </div>
        <div class="col-12 col-md my-2" x-text="setSubTotal"></div>
        <div class="col-12 col-md-auto my-2">
          <button type="button" class="btn btn-danger" @click="hapusPesanan">Hapus Pesanan</button>
        </div>
      </div>
    </div>
  </template>
  <div class="mb-3">
    <button type="button" class="btn btn-success" @click="tambahPesanan">Tambah Pesanan</button>
  </div>
  <!-- <div class="mb-3 row">
    <label class="col-12 col-md-2 col-form-label  ms-md-auto">Uang Pelanggan</label>
    <div class="col-12 col-md-2">
      <input type="number" class="form-control" x-model="$store.pembayaran.uang_pelanggan" @keyup="sinkronRincianPembayaran">
      <template x-if="$store.validasi.validasiPembayaran?.uang_pelanggan != null">
        <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.validasiPembayaran?.uang_pelanggan">
        </div>
      </template>
    </div>
  </div> -->
  <div class="mb-3">
    <div class="row">
      <div class="col-12 col-md-4 ms-md-auto">
        <label class="col-form-label text-center">Uang Pelanggan</label>
        <input type="number" class="form-control" x-model="$store.pembayaran.uang_pelanggan" @keyup="sinkronRincianPembayaran" placeholder="masukkan Uang Pelanggan">
        <template x-if="$store.validasi.validasiPembayaran?.uang_pelanggan != null">
          <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.validasiPembayaran?.uang_pelanggan">
          </div>
        </template>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-12 col-md-4 ms-md-auto">
        <label class="col-form-label text-center">Label Pesanan</label>
        <select class="select-label form-control" x-init="initSelectLabel">
          <option :value="$store.form.id_label" x-text="$store.form.label"></option>
        </select>
        <template x-if="$store.validasi.validasiLabel?.id_label != null">
          <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.validasiLabel?.id_label">
          </div>
        </template>
      </div>
    </div>
    <div class="row">
      <div class="col-6 col-md-2 ms-md-auto">Total Bayar</div>
      <div class="col-6 col-md-2">
        Rp. <span x-text="$store.pembayaran.total_bayar || 0"></span>
      </div>
    </div>
    <div class="row">
      <div class="col-6 col-md-2 ms-md-auto">Uang Pelanggan</div>
      <div class="col-6 col-md-2">
        Rp. <span x-text="$store.pembayaran.uang_pelanggan || 0"></span>
      </div>
    </div>
    <div class="row">
      <div class="col-6 col-md-2 ms-md-auto">Uang Kembalian</div>
      <div class="col-6 col-md-2">
        Rp. <span x-text="$store.pembayaran.uang_kembalian || 0"></span>
      </div>
    </div>
  </div>
  <div class="d-grid gap-2">
    <template x-if="$store.state.loading">
      <button class="btn btn-primary" type="submit" disabled>
        <span class="spinner-border spinner-border-sm"></span>
        <span class="visually-hidden">Loading...</span>
      </button>
      <button class="btn btn-primary" type="reset" @click="handleReset">Reset</button>
    </template>
    <template x-if="!$store.state.loading">
      <button class="btn btn-primary" type="submit" @click="handleSubmit">Proses Edit Pesanan</button>
    </template>
    <button class="btn btn-primary" type="reset" @click="handleReset">Reset</button>
  </div>
</form>

<div class="container mt-5 p-5 border border-1 rounded" x-data x-show="$store.pesananMasuk.id_pesanan != null">
  <h4 class="text-center mb-4">Rincian Pesanan Masuk</h4>
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
    timerProgressBar: true
  })

  document.addEventListener('alpine:init', () => {
    Alpine.store('form', {
      pesanans: [factoryPesanan()],
      id_label: null,
      label: null
    })

    Alpine.store('state', {
      status: null,
      loading: false
    })

    Alpine.store('pembayaran', factoryPembayaran())

    Alpine.store('validasi', factoryValidasi());

    Alpine.store('pesananMasuk', {});

    loadDataPesanan();
  })

  async function loadDataPesanan() {
    const storeForm = Alpine.store('form');
    const storePembayaran = Alpine.store('pembayaran');
    const storePesananMasuk = Alpine.store('pesananMasuk');
    let idPesanan = <?= $idPesanan ?>;
    try {
      const response = await axios.get(`/api/dataPesanan/${idPesanan}`);
      const pesananMasuk = response.data.pesananMasuk;
      await Object.assign(storePesananMasuk, pesananMasuk);
      storeForm.pesanans = Object.assign([], pesananMasuk.pesanans);
      storeForm.id_label = pesananMasuk.id_label;
      storeForm.label = pesananMasuk.label;
      await Object.assign(storePembayaran, {
        uang_pelanggan: pesananMasuk.uang_pelanggan,
        total_bayar: pesananMasuk.total_bayar,
        uang_kembalian: pesananMasuk.uang_kembalian
      });

    } catch (error) {
      const statusCode = error.response?.status;
      const data = error.response?.data;
      if (statusCode == 400) {} else if (statusCode == 500) {

      }
    } finally {

    }
  }

  function removeObjectProperties(obj) {
    Object.keys(obj).forEach(key => {
      delete obj[key];
    });
    return obj;
  }

  function setTotalBayar() {
    const pesanans = Alpine.store('form').pesanans;
    const storePembayaran = Alpine.store('pembayaran');
    let total_bayar = 0;
    let harga, jumlahPesanan;
    pesanans.forEach(pesanan => {
      harga = parseInt(pesanan.harga_tertentu) || 0;
      jumlahPesanan = parseInt(pesanan.jumlah) || 0;
      total_bayar += (harga * jumlahPesanan);
    });
    storePembayaran.total_bayar = total_bayar;
  }

  function setUangKembalian() {
    const storePembayaran = Alpine.store('pembayaran');
    let {
      total_bayar,
      uang_pelanggan
    } = storePembayaran;
    storePembayaran.uang_kembalian = uang_pelanggan - total_bayar;
  }

  function sinkronRincianPembayaran() {
    const validasiPembayaran = Alpine.store('validasi').validasiPembayaran;
    if (validasiPembayaran?.uang_pelanggan != null)
      delete validasiPembayaran.uang_pelanggan;
    setTotalBayar();
    setUangKembalian();
  }

  function factoryPesanan() {
    return {
      id_menu: null,
      nama_menu: null,
      harga_tertentu: null,
      jumlah: 1
    }
  }

  function factoryPembayaran() {
    return {
      uang_pelanggan: null,
      total_bayar: 0,
      uang_kembalian: 0,
    }
  }

  function factoryValidasi() {
    return {
      validasiPesanans: [],
      validasiPembayaran: [],
      validasiLabel: []
    }
  }

  $(document).on('change', '.select-pesanan', async function(e) {

    const storeForm = Alpine.store('form');
    const storeValidasi = Alpine.store('validasi');
    const data = $(this).select2('data')[0];
    const index = $(this).data('index');

    const validasiPesanans = storeValidasi.validasiPesanans;
    if (validasiPesanans[index]?.id_menu != null)
      delete validasiPesanans[index]?.id_menu;

    if (data == null) {
      storeForm.pesanans[index] = factoryPesanan();
      return;
    }

    let length = storeForm.pesanans.length;
    for (let i = 0; i < length; i++) {
      if (i == index) continue;
      let idMenu = storeForm.pesanans[i].id_menu;
      let idMenuSekarang = data.id;
      if (idMenuSekarang != null && idMenuSekarang == idMenu) {
        $(this).val(null).trigger('change');
        validasiPesanans[index] = validasiPesanans[index] ?? {};
        validasiPesanans[index].id_menu = `menu ${data.nama_menu} sudah pilih sebelumnya`;
        storeForm.pesanans[index] = factoryPesanan();
        return;
      }
    }

    storeForm.pesanans[index] = {
      ...storeForm.pesanans[index],
      id_menu: data.id,
      nama_menu: data.nama_menu,
      harga_tertentu: data.harga
    }
  })

  $(document).on('change', '.select-label', function(e) {
    const storeForm = Alpine.store('form');
    const storeValidasi = Alpine.store('validasi');
    const data = $(this).select2('data')[0];
    const index = $(this).data('index');

    const validasiLabel = storeValidasi.validasiLabel;
    if (validasiLabel.id_label != null)
      delete validasiLabel.id_label;

    if (data == null) {
      storeForm.id_label = null;
      storeForm.label = null;
      return;
    }

    storeForm.id_label = data.id;
    storeForm.label = data.text;
  })

  function resetValidasiJumlah(event) {
    let index = this.index;
    const validasiPesanans = Alpine.store('validasi').validasiPesanans;
    if (validasiPesanans[index]?.jumlah != null)
      delete validasiPesanans[index]?.jumlah;
  }

  async function tambahPesanan() {
    const storeForm = Alpine.store('form');
    await storeForm.pesanans.push(factoryPesanan());
  }

  async function sinkronSelectPesanan() {
    const storeForm = Alpine.store('form');
    document.querySelectorAll(".select-pesanan").forEach(function(element) {
      let index = $(element).data('index');
      if (storeForm.pesanans[index].id_menu == null) {
        element.innerHTML = `<option value=""></option>`
        return;
      }

      let namaMenu = storeForm.pesanans[index].nama_menu;
      let idMenu = storeForm.pesanans[index].id_menu;
      let option = new Option(namaMenu, idMenu, true, true);
      element.innerHTML = ``;
      element.appendChild(option);
    });
  }

  async function sinkronSelectLabel() {
    const storeForm = Alpine.store('form');
    const storeValidasi = Alpine.store('validasi');
    const element = document.querySelector(".select-label");
    const data = $(element).select2('data')[0];

    if (storeForm.id_label == null) {
      element.innerHTML = `<option value=""></option>`
      return;
    }

    let idLabel = storeForm.id_label;
    let label = storeForm.label;
    let option = new Option(label, idLabel, true, true);
    element.innerHTML = ``;
    element.appendChild(option);
  }

  async function hapusPesanan(event) {
    const storeForm = Alpine.store('form');
    let index = this.index;
    if (storeForm.pesanans.length == 1) {
      storeForm.pesanans[0] = factoryPesanan();
      return;
    }
    await storeForm.pesanans.splice(index, 1);
  }

  function setSubTotal() {
    let index = this.index;
    const pesanans = Alpine.store('form').pesanans;
    if (pesanans[index] == null) return;

    let id_menu = parseInt(pesanans[index].id_menu);
    let harga_tertentu = parseInt(pesanans[index].harga_tertentu);
    let jumlah = parseInt(pesanans[index].jumlah);

    if (isNaN(id_menu))
      return 'menu belum dipilih';

    if (isNaN(jumlah) || jumlah == 0)
      return 'Rp. 0';

    if (jumlah < 0)
      return 'jumlah pesanan menu minimal 1';

    return `Rp. ${harga_tertentu * jumlah}`;
  }

  function initSelectPesanan() {
    const select = $(this.$el);
    select.select2({
      allowClear: true,
      placeholder: 'pilih menu',
      ajax: {
        url: "/api/menu/findByFilters",
        data: function(params) {
          return {
            'nama_menu': params.term
          }
        },
        dataType: 'json',
        processResults: function(data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          const newData = data.data.map(function(item, index) {
            return {
              id: item.id_menu,
              text: item.nama_menu,
              ...item
            }
          })
          return {
            results: newData
          };
        }
      }
    });
  }

  function initSelectLabel() {
    const select = $(this.$el);
    select.select2({
      allowClear: true,
      placeholder: 'pilih label pesanan',
      ajax: {
        url: "/api/LabelPesanan/findByFilters",
        data: function(params) {
          return {
            'label': params.term
          }
        },
        dataType: 'json',
        processResults: function(data) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          const newData = data.data.map(function(item, index) {
            return {
              id: item.id_label,
              text: item.label,
              ...item
            }
          })
          return {
            results: newData
          };
        }
      }
    });
  }

  async function setNotification() {
    const storeState = Alpine.store('state');
    const status = storeState.status;
    if (status == 'sukses') {
      Toast.fire({
        icon: 'success',
        title: 'data pesanan berhasil diubah'
      })
    }
    if (status == 'gagal') {
      Toast.fire({
        icon: 'error',
        title: 'data pesanan gagal diubah'
      })
    }
  }

  async function handleSubmit(e) {
    e.preventDefault();
    const storeForm = Alpine.store('form');
    const storeState = Alpine.store('state');
    const storePembayaran = Alpine.store('pembayaran');
    const storeValidasi = Alpine.store('validasi');
    const storePesananMasuk = Alpine.store('pesananMasuk');
    let idPesanan = <?= $idPesanan ?>;
    const data = {
      pesanans: storeForm.pesanans,
      pembayaran: storePembayaran,
      id_label: storeForm.id_label

    }
    storeState.loading = true;
    try {
      const response = await axios.put(`/api/pesanan/${idPesanan}`, data);
      Object.assign(storePesananMasuk, {
        id_pesanan: response.data.id_pesanan,
        waktu_update: response.data.waktu_update,
        pesanans: Object.assign([], storeForm.pesanans),
        id_label: storeForm.id_label,
        label: storeForm.label,
        ...storePembayaran,
      });
      setTimeout(function() {
        document.getElementsByClassName('table')[0]
          .scrollIntoView({
            behavior: "smooth"
          })
      }, 100);
      storeState.status = 'sukses';
      Object.assign(storeValidasi, factoryValidasi());
      setNotification();
      handleReset();
    } catch (error) {
      const statusCode = error.response?.status;
      const data = error.response?.data;
      if (statusCode == 400) {
        Object.assign(storeValidasi, data.error);
        storeValidasi.validasiPesanans = data.validasiPesanans;
        storeValidasi.validasiPembayaran = data.validasiPembayaran;
        storeValidasi.validasiLabel = data.validasiLabel;
      } else if (statusCode == 500) {
        storeState.status = 'gagal';
      }
    } finally {
      storeState.loading = false;
    }
  }

  async function handleReset(e) {
    let keputusan;
    if (e != null) e.preventDefault();
    if (e != null) {
      let keputusan = await Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Anda akan mereset inputan ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya!',
        cancelButtonText: 'Batal'
      })
      if (!keputusan.isConfirmed) return;
    }


    const storeForm = Alpine.store('form');
    const storePembayaran = Alpine.store('pembayaran');
    const storePesananMasuk = Alpine.store('pesananMasuk');
    const storeValidasi = Alpine.store('validasi');
    storeForm.pesanans = Object.assign([], storePesananMasuk.pesanans);
    await Object.assign(storePembayaran, {
      uang_pelanggan: storePesananMasuk.uang_pelanggan,
      total_bayar: storePesananMasuk.total_bayar,
      uang_kembalian: storePesananMasuk.uang_kembalian
    });
    await Object.assign(storeValidasi, factoryValidasi());

  }
</script>
<?= $this->endSection() ?>