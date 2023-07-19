<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container mt-4 text-center">
  <a class="btn btn-primary" href="/karyawan" role="button">Kembali</a>
</div>
<form method="post" class="container mt-4 p-5 border border-1 rounded" id="form" x-data="form" class="was-validated" x-effect="setNotification()" x-data x-init="$watch('$store.form.id_menu', sinkronSelect)">
  <div class=""></div>
  <h5 class="text-center mb-5">Form Tambah Pesanan</h5>
  <template x-for="(id, index) in $store.form.id_menu">
    <div class="mb-3">
      <div class="row border border-1 rounded py-4 px-3">
        <p class="mb-0" x-text="`pesanan ke ${index + 1}`"></p>
        <div class="col-12 col-md my-2">
          <select class="select-pesanan form-control" x-init="initSelect2" :data-index="index">
            <option value=""></option>
          </select>
        </div>
        <div class="col-12 col-md my-2">
          <input type="number" class="form-control" placeholder="jumlah pesanan" x-model="$store.form.jumlah[index]">
        </div>
        <div class="col-12 col-md my-2" x-text="aturSubTotal"></div>
        <div class="col-12 col-md-auto my-2">
          <button type="button" class="btn btn-danger" @click="hapusPesanan">Hapus Pesanan</button>
        </div>
      </div>
    </div>
  </template>
  <!-- <div class="mb-3">
    <div class="row border border-1 rounded py-4 px-3">
      <p class="mb-0">Pesanan Ke 1</p>
      <div class="col-12 col-md my-2">
        <input type="text" class="form-control" placeholder="masukkan menu">
      </div>
      <div class="col-12 col-md my-2">
        <input type="text" class="form-control" placeholder="jumlah pesanan">
      </div>
      <div class="col-12 col-md my-2">
        Sub Total : Rp. 15.000
      </div>
      <div class="col-12 col-md-auto my-2">
        <button type="button" class="btn btn-danger">Hapus Pesanan</button>
      </div>
    </div>
  </div> -->
  <!-- <div class="mb-3">
    <div class="row border border-1 rounded py-4 px-3">
      <p class="mb-0">Pesanan Ke 2</p>
      <div class="col-12 col-md my-2">
        <input type="text" class="form-control" placeholder="masukkan menu">
      </div>
      <div class="col-12 col-md my-2">
        <input type="text" class="form-control" placeholder="jumlah pesanan">
      </div>
      <div class="col-12 col-md my-2">
        Sub Total : Rp. 15.000
      </div>
      <div class="col-12 col-md-auto my-2">
        <button type="button" class="btn btn-danger">Hapus Pesanan</button>
      </div>
    </div>
  </div> -->
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

  document.addEventListener('alpine:init', () => {
    Alpine.store('form', {
      id_menu: [null],
      nama_menu: [null],
      harga_tertentu: [null],
      jumlah: [1]
    })
  })

  $(document).on('change', '.select-pesanan', function(e) {

    const storeForm = Alpine.store('form');
    const data = $(this).select2('data')[0];
    const index = $(this).data('index');
    if (data == null) {
      storeForm.id_menu[index] = null;
      storeForm.nama_menu[index] = null;
      storeForm.harga_tertentu[index] = null;
      return;
    }
    storeForm.id_menu[index] = data.id;
    storeForm.nama_menu[index] = data.nama_menu;
    storeForm.harga_tertentu[index] = data.harga;
  })

  async function tambahPesanan(e) {
    const storeForm = Alpine.store('form');
    let index = this.index;
    await storeForm.id_menu.push(null);
    await storeForm.nama_menu.push(null);
    await storeForm.harga_tertentu.push(null);
    await storeForm.jumlah.push(1);

  }

  async function sinkronSelect() {
    const storeForm = Alpine.store('form');
    document.querySelectorAll(".select-pesanan").forEach(function(element) {
      let index = $(element).data('index');
      if (storeForm.nama_menu[index] == null) {
        element.innerHTML = `<option value=""></option>`
        return;
      }
      var option = new Option(storeForm.nama_menu[index], storeForm.id_menu[index], true, true);
      element.innerHTML = ``;
      element.appendChild(option);
    });
  }

  async function hapusPesanan(e) {
    const storeForm = Alpine.store('form');
    let index = this.index;
    newIdMenu = [...storeForm.id_menu];
    newIdMenu.splice(index, 1);

    newNamaMenu = [...storeForm.nama_menu];
    newNamaMenu.splice(index, 1);

    newHargaTertentu = [...storeForm.harga_tertentu];
    newHargaTertentu.splice(index, 1);

    newJumlah = [...storeForm.jumlah];
    newJumlah.splice(index, 1);

    storeForm.id_menu = newIdMenu;
    storeForm.nama_menu = newNamaMenu;
    storeForm.harga_tertentu = newHargaTertentu;
    storeForm.jumlah = newJumlah;
  }

  function aturSubTotal() {
    // return `tes`;
    const storeForm = Alpine.store('form');
    let index = this.index;
    let id_menu, harga_tertentu, jumlah;
    id_menu = parseInt(storeForm.id_menu[index]);
    harga_tertentu = parseInt(storeForm.harga_tertentu[index]);
    jumlah = parseInt(storeForm.jumlah[index]);

    if (isNaN(id_menu)) id_menu = null;
    if (isNaN(harga_tertentu)) harga_tertentu = null;
    if (isNaN(jumlah)) jumlah = null;

    if (id_menu == null) return 'menu belum dipilih'

    return `Rp. ${harga_tertentu * jumlah}`;
  }

  function initSelect2() {
    const select = $(this.$el);

    const storeForm = Alpine.store('form');
    let index = this.index;

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