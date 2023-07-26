<?= $this->extend('template') ?>

<?= $this->section('content') ?>

</div>
<div class="container mt-4 text-center">
  <a class="btn btn-primary" href="/pengeluaran" role="button">Kembali</a>
</div>
<form method="post" class="container mt-4 p-5 border border-1 rounded" id="form" x-data x-effect="setNotification()">
  <div class=""></div>
  <h5 class="text-center mb-5">Form Tambah Pengeluaran</h5>
  <div class="mb-3">
    <label class="form-label">Keterangan Pengeluaran</label>
    <textarea name="keterangan" class="form-control" rows="3" placeholder="masukkan keterangan pengeluaran" x-value="$store.global.form.keterangan" x-model="$store.global.form.keterangan" @keyup="resetValidasi('keterangan')"></textarea>
    <template x-if=" $store.global.validasi.keterangan !=null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.global.validasi.keterangan">
      </div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">Nominal Pengeluaran</label>
    <input type="number" name="nominal" class="form-control" placeholder="masukkan nominal pengeluaran" :value="$store.global.form.nominal" x-model="$store.global.form.nominal" @keyup="resetValidasi('nominal')">
    <template x-if="$store.global.validasi.nominal != null">
      <div class="alert alert-danger mt-2 p-1" x-text="$store.global.validasi.nominal">
      </div>
    </template>
  </div>
  <div class="mb-3">
    <label class="form-label">label pengeluaran</label>
    <div x-show="$store.global.state.labelTerload === true">
      <select class="form-select label-pengeluaran" name="id_label" class="form-control" x-model="$store.global.form.id_label" @change="resetValidasi('id_label')" x-init="loadSelectLabel">
        <template x-for="label in $store.global.labels">
          <option :value="label.id_label" x-text="label.label" :selected="$store.global.form.id_label=label.id_label"></option>
        </template>
      </select>
      <template x-if="$store.global.validasi.id_label != null">
        <div class="alert alert-danger mt-2 p-1" x-text="$store.global.validasi.id_label">
        </div>
      </template>
    </div>
    <div x-show="$store.global.state.labelTerload === null">sedang memuat label pengeluaran ...</div>
    <div x-show="$store.global.state.labelTerload === false">
      gagal memuat label pengeluaran <button class="btn btn-primary" @click="loadSelectLabel" type="button">muat ulang</button>
    </div>
  </div>
  <div class="d-grid gap-2">
    <template x-if="$store.global.state.loading">
      <button class="btn btn-primary" type="submit" disabled>
        <span class="spinner-border spinner-border-sm"></span>
        <span class="visually-hidden">Loading...</span>
      </button>
    </template>
    <template x-if="!$store.global.state.loading">
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
    Alpine.store('global', {
      'form': {
        'id_label': null
      },
      'validasi': {},
      'state': {
        status: null,
        loading: false,
        labelTerload: null
      },
      'labels': []
    })
    window.storeGlobal = Alpine.store('global');

  })

  async function loadSelectLabel() {
    storeGlobal.state.labelTerload = null
    try {
      const response = await axios.get(`/api/LabelPengeluaran/selectAll`);
      storeGlobal.labels = response.data.labels;
      storeGlobal.labels.unshift({
        id_label: null,
        label: 'pilih label pengeluaran'
      });
      storeGlobal.state.labelTerload = true;

      this.$nextTick(() => {
        storeGlobal.form.id_label = null;
      })

    } catch (error) {
      storeGlobal.state.labelTerload = false;
    }
  }

  function resetValidasi(name) {
    storeValidasi = Alpine.store('global').validasi;
    delete storeValidasi[name];
  }

  function removeObjectProperties(obj) {
    Object.keys(obj).forEach(key => {
      delete obj[key];
    });
    return obj;
  }

  function setNotification() {
    const storeState = storeGlobal.state;
    const status = storeState.status;
    if (status == 'sukses') {
      document.getElementById('form').reset();
      Toast.fire({
        icon: 'success',
        title: 'data pengeluaran berhasil ditambahkan'
      })
    }

    if (status == 'gagal') {
      Toast.fire({
        icon: 'error',
        title: 'data pengeluaran gagal ditambahkan'
      })
    }
    storeState.status = null;
    storeState.loading = false;
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
    const storeForm = storeGlobal.form;
    const storeValidasi = storeGlobal.validasi;
    const storeState = storeGlobal.state;

    storeState.loading = true;
    try {
      const response = await axios.post('/api/pengeluaran/', data);
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
    const storeForm = storeGlobal.form;
    const storeValidasi = storeGlobal.validasi;
    const storeState = storeGlobal.state;
    removeObjectProperties(storeValidasi);
    storeState.status = null;
    storeState.loading = false;
    document.getElementById('form').reset();
  }
</script>
<?= $this->endSection() ?>