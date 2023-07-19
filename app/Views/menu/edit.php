<?= $this->extend('template') ?>

<?= $this->section('content') ?>

</div>
<div class="container mt-4 text-center">
  <a class="btn btn-primary" href="/menu" role="button">Kembali</a>
</div>
<form method="post" class="container mt-4 p-5 border border-1 rounded" id="form" x-data x-effect="setNotification()">
  <h5 class="text-center mb-5">Form Edit Menu Makanan</h5>
  <div class="mb-3">
    <label class="form-label">nama_menu</label>
    <input type="text" class="form-control" name="nama_menu" placeholder="masukkan nama menu" :value="$store.form.nama_menu" x-model="$store.form.nama_menu" @keyup="resetValidasi('nama_menu')">
    <template x-if="$store.validasi.nama_menu != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.nama_menu">
      </div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">kategori</label>
    <select class="form-select" name="kategori" class="form-control" x-model="$store.form.kategori" @change="resetValidasi('kategori')">
      <option value="" :selected="$store.form.kategori == '' || $store.form.kategori == null">
        Pilih kategori menu
      </option>
      <option value="makanan" selected="$store.form.kategori == 'makanan'">makanan</option>
      <option value="minuman" selected="$store.form.kategori == 'minuman'">minuman</option>
    </select>
    <template x-if="$store.validasi.kategori != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.kategori">
      </div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">harga</label>
    <input type="text" class="form-control" name="harga" placeholder="masukkan harga menu" :value="$store.form.harga" x-model="$store.form.harga" @keyup="resetValidasi('harga')">
    <template x-if="$store.validasi.harga != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.harga">
      </div>
    </template>
  </div>

  <div class="d-grid gap-2">
    <template x-if="$store.state.loading">
      <button class="btn btn-primary" type="submit" disabled>
        <span class="spinner-border spinner-border-sm"></span>
        <span class="visually-hidden">Loading...</span>
      </button>
    </template>
    <template x-if="!$store.state.loading">
      <button class="btn btn-primary" type="submit" @click="handleSubmit">Tambah</button>
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
    // didOpen: (toast) => {
    //   toast.addEventListener('mouseenter', Swal.stopTimer)
    //   toast.addEventListener('mouseleave', Swal.resumeTimer)
    // }
  })

  document.addEventListener('alpine:init', () => {
    Alpine.store('form', {})
    Alpine.store('validasi', {})
    Alpine.store('state', {
      loading: false,
      status: null
    })

    loadDataForm();
  })

  function resetValidasi(name) {
    const storeValidasi = Alpine.store('validasi');
    delete storeValidasi[name];
  }

  function removeObjectProperties(obj) {
    Object.keys(obj).forEach(key => {
      delete obj[key];
    });
    return obj;
  }

  function setNotification() {
    const storeState = Alpine.store('state');
    const status = storeState.status;
    if (status == 'sukses') {
      Toast.fire({
        icon: 'success',
        title: 'data menu makanan berhasil diperbarui'
      })
    }

    if (status == 'gagal') {
      Toast.fire({
        icon: 'error',
        title: 'data menu makanan gagal diperbarui'
      })
    }
    storeState.status = null;
    storeState.loading = false;
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

  async function loadDataForm() {
    const storeForm = Alpine.store('form');
    const storeValidasi = Alpine.store('validasi');
    const storeState = Alpine.store('state');
    try {
      storeForm.loading = true;
      const response = await axios.get('/api/menu/find/<?= $idMenu ?>');
      Object.assign(storeForm, response.data.data);
      removeObjectProperties(storeValidasi);
      storeState.status = null;
    } catch (error) {
      console.log(error);
      const statusCode = error.response?.status;
      const data = error.response?.data;

      if (statusCode == 400) {

      } else if (statusCode == 500) {

      }
    } finally {
      storeState.loading = false;
    }
  }

  async function handleSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('form');
    const data = getFormData(form);
    const storeForm = Alpine.store('form');
    const storeValidasi = Alpine.store('validasi');
    const storeState = Alpine.store('state');

    storeState.loading = true;
    try {
      const response = await axios.put('/api/menu/<?= $idMenu ?>', data);
      storeState.status = 'sukses';
      removeObjectProperties(storeValidasi);
    } catch (error) {
      const statusCode = error.response?.status;
      const data = error.response?.data;

      if (statusCode == 400) {
        removeObjectProperties(storeValidasi);
        Object.assign(storeValidasi, data.error);
      } else if (statusCode == 500) {
        storeState.status = 'gagal';
      }
    } finally {
      storeState.loading = false;
    }
  }

  function handleReset(e) {
    e.preventDefault();
    loadDataForm();
  }
</script>
<?= $this->endSection() ?>