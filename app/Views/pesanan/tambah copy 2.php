<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-4 text-center">
  <a class="btn btn-primary" href="/karyawan" role="button">Kembali</a>
</div>
<form method="post" class="container mt-4 p-5 border border-1 rounded" id="form" x-data class="was-validated" x-effect="setNotification()">
  <divx-init="$watch('$store.form.pesanans', sinkronSelect)"></divx-init=>
    div
    <div class=""></div>
    <h5 class="text-center mb-5">Form Tambah Pesanan</h5>
    <template x-for="(pesanan, index) in $store.form.pesanans">
      <div class="mb-3">
        <div class="row border border-1 rounded py-4 px-3">
          <p class="mb-0" x-text="`pesanan ke ${index + 1}`"></p>
          <div class="col-12 col-md my-2">
            <select class="select-pesanan form-control" x-init="initSelect2" :data-index="index">
              <option value=""></option>
            </select>
          </div>
          <div class="col-12 col-md my-2">
            <input type="number" class="form-control" placeholder="jumlah pesanan" x-model="pesanan.jumlah">
          </div>
          <div class="col-12 col-md my-2" x-text="aturSubTotal"></div>
          <div class="col-12 col-md-auto my-2">
            <button type="button" class="btn btn-danger" @click="hapusPesanan">Hapus Pesanan</button>
          </div>
        </div>
      </div>
    </template>
    <div class="mb-3">
      <button type="button" class="btn btn-success" @click="tambahPesanan">Tambah Pesanan</button>
    </div>
    <div class="mb-3 row">
      <label for="inputPassword" class="col-12 col-md-auto col-form-label">Uang Pelanggan</label>
      <div class="col-12 col-md-auto">
        <input type="text" class="form-control" id="inputPassword">
      </div>
    </div>
    <div class="mb-3">
      Uang Pelanggan : Rp. 70.000<br>
      Total Bayar : Rp. 50.000<br>
      Uang Kembalian : Rp. 20.000<br>
    </div>
    <div class="d-grid gap-2">
      <template x-if="$store.form.loading">
        <button class="btn btn-primary" type="submit" disabled>
          <span class="spinner-border spinner-border-sm"></span>
          <span class="visually-hidden">Loading...</span>
        </button>
      </template>
      <template x-if="!$store.form.loading">
        <button class="btn btn-primary" type="submit" @click="handleSubmit">Proses Pesanan</button>
      </template>
      <button class="btn btn-primary" type="reset" @click="handleReset">Reset</button>
    </div>
</form>
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

  function factoryPesanan() {
    return {
      id_menu: null,
      nama_menu: null,
      harga_tertentu: null,
      jumlah: '1'
    }
  }

  document.addEventListener('alpine:init', () => {
    Alpine.store('form', {
      pesanans: [factoryPesanan()]
    })

    Alpine.store('state', {
      totalBayar: 0
    })
  })

  $(document).on('change', '.select-pesanan', function(e) {

    const storeForm = Alpine.store('form');
    const data = $(this).select2('data')[0];
    const index = $(this).data('index');
    if (data == null) {
      // storeForm.id_menu[index] = null;
      // storeForm.nama_menu[index] = null;
      // storeForm.harga_tertentu[index] = null;
      storeForm.pesanans[index] = factoryPesanan();
      return;
    }
    storeForm.pesanans[index] = {
      ...storeForm.pesanans[index],
      id_menu: data.id,
      nama_menu: data.nama_menu,
      harga_tertentu: data.harga
    }
  })

  async function tambahPesanan(e) {
    const storeForm = Alpine.store('form');
    let index = this.index;
    await storeForm.pesanans.push(factoryPesanan());
  }

  async function sinkronSelect() {
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

  async function hapusPesanan(event) {
    const storeForm = Alpine.store('form');
    let index = this.index;
    await storeForm.pesanans.splice(index, 1);
  }

  function aturSubTotal() {
    let index = this.index;
    const pesanans = Alpine.store('form').pesanans;
    if (pesanans[index] == null) return;

    let id_menu = parseInt(pesanans[index].id_menu);
    let harga_tertentu = parseInt(pesanans[index].harga_tertentu);
    let jumlah = parseInt(pesanans[index].jumlah);

    if (isNaN(id_menu))
      return 'menu belum dipilih';

    if (isNaN(jumlah) || jumlah == 0)
      return 'jumlah pesanan tidak boleh kosong';

    if (jumlah < 0)
      return 'jumlah pesanan harus lebih dari 0';

    return `Rp. ${harga_tertentu * jumlah}`;
  }

  function initSelect2() {
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

  function setNotification() {
    const storeForm = Alpine.store('form');
    const status = storeForm.status;
    if (status == 'sukses') {
      document.getElementById('form').reset();
      Toast.fire({
        icon: 'success',
        title: 'data karyawan berhasil ditambahkan'
      })
    }

    if (status == 'gagal') {
      Toast.fire({
        icon: 'error',
        title: 'data karyawan gagal ditambahkan'
      })
    }
    storeForm.errorValidasi = [];
    storeForm.status = null;
  }

  function setValidationClass(name) {
    const errorValidasi = Alpine.store('form').errorValidasi;
    if (errorValidasi[name] != null) return `form-control is-invalid`;
    else return 'form-control';
  }

  function getValidationError(name) {
    const errorValidasi = Alpine.store('form').errorValidasi;
    return errorValidasi[name];
  }

  function hasValidationError(name) {
    const errorValidasi = Alpine.store('form').errorValidasi;
    if (errorValidasi[name] != null) return true;
    else return false;
  }

  function getFormData(el) {
    const formData = new FormData(el);
    const data = {};
    for (let [key, value] of formData.entries()) {
      data[key] = value;
    }
    return data;
  }

  async function handleSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('form');
    const data = getFormData(form);
    const storeForm = Alpine.store('form');

    storeForm.loading = true;
    try {
      const response = await axios.post('/api/karyawan', data);
      storeForm.status = 'sukses';
    } catch (error) {
      const statusCode = error.response?.status;
      const data = error.response?.data;
      if (statusCode == 400) {
        storeForm.errorValidasi = data.error;
      } else if (statusCode == 500) {
        storeForm.status = 'gagal';
      }
    } finally {
      storeForm.loading = false;
    }
  }

  function handleReset(e) {
    e.preventDefault();
    const storeForm = Alpine.store('form')
    storeForm.errorValidasi = [];
    storeForm.status = null;
    storeForm.loading = false;
    document.getElementById('form').reset();
  }
</script>
<?= $this->endSection() ?>