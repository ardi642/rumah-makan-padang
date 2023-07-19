<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-4 text-center">
  <a class="btn btn-primary" href="/karyawan" role="button">Kembali</a>
</div>
<form method="post" class="container mt-4 p-5 border border-1 rounded" id="form" x-data="form" class="was-validated" x-effect="setNotification()">
  <div class=""></div>
  <h5 class="text-center mb-5">Form Tambah Karyawan</h5>
  <div class="mb-3">
    <label class="form-label">username</label>
    <input type="text" :class="setValidationClass('username')" name="username" placeholder="masukkan username">
    <template x-if="hasValidationError('username')">
      <div class="invalid-feedback" x-text="getValidationError('username')"></div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">nama karyawan</label>
    <input type="text" :class="setValidationClass('nama_karyawan')" name="nama_karyawan" placeholder="masukkan nama karyawan">
    <template x-if="hasValidationError('nama_karyawan')">
      <div class="invalid-feedback" x-text="getValidationError('nama_karyawan')"></div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">email (Optional)</label>
    <input type="text" :class="setValidationClass('email')" name="email" placeholder="masukkan email">
    <template x-if="hasValidationError('email')">
      <div class="invalid-feedback" x-text="getValidationError('email')"></div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">password</label>
    <input type="password" :class="setValidationClass('password')" name="password" placeholder="masukkan password">
    <template x-if="hasValidationError('password')">
      <div class="invalid-feedback" x-text="getValidationError('password')"></div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">konfirmasi password</label>
    <input type="password" :class="setValidationClass('konfirmasi_password')" name="konfirmasi_password" placeholder="konfirmasi password">
    <template x-if="hasValidationError('konfirmasi_password')">
      <div class="invalid-feedback" x-text="getValidationError('konfirmasi_password')"></div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">no telepon (Optional)</label>
    <input type="text" :class="setValidationClass('no_telepon')" name="no_telepon" placeholder="masukkan no telepon">
    <template x-if="hasValidationError('no_telepon')">
      <div class="invalid-feedback" x-text="getValidationError('no_telepon')"></div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">alamat (Optional)</label>
    <textarea :class="setValidationClass('alamat')" rows="3" name="alamat" placeholder="masukkan alamat"></textarea>
    <template x-if="hasValidationError('alamat')">
      <div class="invalid-feedback" x-text="getValidationError('alamat')"></div>
    </template>
  </div>
  <div class="d-grid gap-2">
    <template x-if="$store.form.loading">
      <button class="btn btn-primary" type="submit" disabled>
        <span class="spinner-border spinner-border-sm"></span>
        <span class="visually-hidden">Loading...</span>
      </button>
    </template>
    <template x-if="!$store.form.loading">
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
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })

  document.addEventListener('alpine:init', () => {
    Alpine.store('form', {
      errorValidasi: [],
      status: null,
      loading: false
    })
  })

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