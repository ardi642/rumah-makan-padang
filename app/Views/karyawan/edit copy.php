<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-4 text-center">
  <a class="btn btn-primary" href="/karyawan" role="button">Kembali</a>
</div>
<form method="post" class="container mt-4 p-5 border border-1 rounded" id="form" class="was-validated" x-data>
  <div x-init="$watch('$store.state.status', setNotification)"></div>
  <h5 class="text-center mb-5">Form Edit Karyawan</h5>
  <div class="mb-3">
    <label class="form-label">username</label>
    <input type="text" class="form-control" placeholder="masukkan username" :value="$store.form.username" x-model="$store.form.username" @keyup="resetValidasi('username')">
    <template x-if="$store.validasi.username != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.username">
      </div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">nama karyawan</label>
    <input type="text" :class="setValidationClass('nama_karyawan')" name="nama_karyawan" placeholder="masukkan nama karyawan" :value="$store.form.nama_karyawan" x-model="$store.form.nama_karyawan">
    <template x-if="hasValidationError('nama_karyawan')">
      <div class="invalid-feedback" x-text="getValidationError('nama_karyawan')"></div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">email (Optional)</label>
    <input type="text" :class="setValidationClass('email')" name="email" placeholder="masukkan email" :value="$store.form.email" x-model="$store.form.email">
    <template x-if="hasValidationError('email')">
      <div class="invalid-feedback" x-text="getValidationError('email')"></div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">password lama (Optional)</label>
    <input type="password" :class="setValidationClass('password_lama')" name="password_lama" placeholder="masukkan password lama" :value="$store.form.password_lama" x-model="$store.form.password_lama">
    <template x-if="hasValidationError('password_lama')">
      <div class="invalid-feedback" x-text="getValidationError('password_lama')"></div>
    </template>
  </div>
  <template x-if="$store.form.password_lama != '' && $store.form.password_lama != null">
    <div>
      <div class="mb-3">
        <label class="form-label">password baru</label>
        <input type="password" :class="setValidationClass('password_baru')" name="password_baru" placeholder="masukkan password baru" :value="$store.form.password_baru" x-model="$store.form.password_baru">
        <template x-if="hasValidationError('password_baru')">
          <div class="invalid-feedback" x-text="getValidationError('password_baru')"></div>
        </template>
      </div>
      <div class="mb-3">
        <label class="form-label">konfirmasi password baru</label>
        <input type="password" :class="setValidationClass('konfirmasi_password')" name="konfirmasi_password" placeholder="konfirmasi password" :value="$store.form.konfirmasi_password" x-model="$store.form.konfirmasi_password">
        <template x-if="hasValidationError('konfirmasi_password')">
          <div class="invalid-feedback" x-text="getValidationError('konfirmasi_password')"></div>
        </template>
      </div>
    </div>
  </template>
  <div class="mb-3">
    <label class="form-label">no telepon (Optional)</label>
    <input type="text" :class="setValidationClass('no_telepon')" name="no_telepon" placeholder="masukkan no telepon" :value="$store.form.no_telepon" x-model="$store.form.no_telepon">
    <template x-if="hasValidationError('no_telepon')">
      <div class="invalid-feedback" x-text="getValidationError('no_telepon')"></div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">alamat (Optional)</label>
    <textarea :class="setValidationClass('alamat')" rows="3" name="alamat" placeholder="masukkan alamat" x-text="$store.form.alamat" x-model="$store.form.alamat"></textarea>
    <template x-if="hasValidationError('alamat')">
      <div class="invalid-feedback" x-text="getValidationError('alamat')"></div>
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
      <button class="btn btn-primary" type="submit" @click="handleSubmit">Edit</button>
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

  document.addEventListener('alpine:init', () => {
    Alpine.store('form', {})
    Alpine.store('validasi', {})
    Alpine.store('state', {
      loading: false,
      status: null
    })
    loadDataForm();
  })

  function removeObjectProperties(obj) {
    Object.keys(obj).forEach(key => {
      delete obj[key];
    });
    return obj;
  }

  function setNotification() {
    const storeForm = Alpine.store('form');
    const storeValidasi = Alpine.store('validasi');
    const storeState = Alpine.store('state');
    const status = storeState.status;
    if (status == 'sukses') {
      Toast.fire({
        icon: 'success',
        title: 'data karyawan berhasil diperbarui'
      })
    }

    if (status == 'gagal') {
      Toast.fire({
        icon: 'error',
        title: 'data karyawan gagal diperbarui'
      })
    }
    removeObjectProperties(storeValidasi);
    storeState.status = null;
  }

  function setValidationClass(name) {
    const storeValidasi = Alpine.store('validasi');
    if (storeValidasi[name] != null) return `form-control is-invalid`;
    else return 'form-control';
  }

  function getValidationError(name) {
    const storeValidasi = Alpine.store('validasi');
    return storeValidasi[name];
  }

  function hasValidationError(name) {
    const storeValidasi = Alpine.store('validasi');
    if (storeValidasi[name] != null) return true;
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
      storeState.loading = true;
      const response = await axios.get('/api/karyawan/find/<?= $idKaryawan ?>');
      removeObjectProperties(storeForm);
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
    const storeForm = Alpine.store('form');
    const storeValidasi = Alpine.store('validasi');
    const storeState = Alpine.store('state');

    const form = document.getElementById('form');
    const data = getFormData(form);

    storeState.loading = true;
    try {
      const response = await axios.put('/api/karyawan/<?= $idKaryawan ?>', data);
      storeState.status = 'sukses';
      delete storeForm.password_lama;
      delete storeForm.password_baru;
      delete storeForm.konfirmasi_password;
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