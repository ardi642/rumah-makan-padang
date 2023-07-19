<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-4 text-center">
  <a class="btn btn-primary" href="/karyawan" role="button">Kembali</a>
</div>
<form method="post" class="container mt-4 p-5 border border-1 rounded" id="form" x-data>
  <div x-init="$watch('$store.state.status', setNotification)"></div>
  <h5 class="text-center mb-5">Form Tambah Karyawan</h5>
  <div class="mb-3">
    <label class="form-label">username</label>
    <input type="text" class="form-control" name="username" placeholder="masukkan username" :value="$store.form.username" x-model="$store.form.username" @keyup="resetValidasi('username')">
    <template x-if="$store.validasi.username != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.username">
      </div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">nama karyawan</label>
    <input type="text" class="form-control" name="nama_karyawan" placeholder="masukkan nama karyawan" :value="$store.form.nama_karyawan" x-model="$store.form.nama_karyawan" @keyup="resetValidasi('nama_karyawan')">
    <template x-if="$store.validasi.nama_karyawan != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.nama_karyawan">
      </div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">email (Optional)</label>
    <input type="text" class="form-control" name="email" placeholder="masukkan email" :value="$store.form.email" x-model="$store.form.email" @keyup="resetValidasi('email')">
    <template x-if="$store.validasi.email != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.email">
      </div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">password</label>
    <input type="password" class="form-control" name="password" placeholder="masukkan password" :value="$store.form.password" x-model="$store.form.password" @keyup="resetValidasi('password')">
    <template x-if="$store.validasi.password != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.password">
      </div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">konfirmasi password</label>
    <input type="password" class="form-control" name="konfirmasi_password" placeholder="konfirmasi password baru" :value="$store.form.konfirmasi_password" x-model="$store.form.konfirmasi_password" @keyup="resetValidasi('konfirmasi_password')">
    <template x-if="$store.validasi.konfirmasi_password != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.konfirmasi_password">
      </div>
    </template>
    <div class="mb-3">
      <label class="form-label">no. telepon (Optional)</label>
      <input type="text" class="form-control" name="no_telepon" placeholder="masukkan no. telepon" :value="$store.form.no_telepon" x-model="$store.form.no_telepon" @keyup="resetValidasi('no_telepon')">
      <template x-if="$store.validasi.no_telepon != null">
        <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.no_telepon">
        </div>
      </template>
    </div>
    <div class="mb-3">
      <label class="form-label">alamat (Optional)</label>
      <textarea class="form-control" rows="3" name="alamat" placeholder="masukkan alamat" x-text="$store.form.alamat" x-model="$store.form.alamat"></textarea>
      <template x-if="$store.validasi.alamat != null">
        <div class="alert alert-danger mt-2 p-1" x-text="$store.validasi.alamat">
        </div>
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
    timerProgressBar: true
  })

  document.addEventListener('alpine:init', () => {
    Alpine.store('form', {})
    Alpine.store('validasi', {})
    Alpine.store('state', {
      loading: false,
      status: null
    })
  })

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
    storeState.status = null;
    storeState.loading = false;
  }

  function resetValidasi(name) {
    const storeValidasi = Alpine.store('validasi');
    delete storeValidasi[name];
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
    const storeForm = Alpine.store('form');
    const storeState = Alpine.store('state');
    const storeValidasi = Alpine.store('validasi');
    const data = getFormData(form);

    storeState.loading = true;
    try {
      const response = await axios.post('/api/karyawan', data);
      storeState.status = 'sukses';
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
    const storeForm = Alpine.store('form')
    const storeState = Alpine.store('state')
    const storeValidasi = Alpine.store('validasi')
    removeObjectProperties(storeValidasi);
    storeState.status = null;
    storeState.loading = false;
    document.getElementById('form').reset();
  }
</script>
<?= $this->endSection() ?>