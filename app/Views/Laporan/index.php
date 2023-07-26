<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<!-- Container 1 untuk  bagian button dsb-->
<div class="container mt-5 p-5 border border-1 rounded">
<p class="fs-3">Filter Laporan</p>
  <div x-data>
    <div class="row">
      <div class="col-4">
        <div class="row">
          <div class="col-4">tipe Waktu</div>
          <div class="col-8">
          <select class="form-select" x-model="$store.state.tipeWaktu" id="tipeWaktu" @change="handleChangeTipeWaktu">
            <option value="date">hari</option>
            <option value="month">bulan</option>
          </select>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="row">
          <div class="col-4">Dari Waktu</div>
          <div class="col-8">
            <div class="mb-2 mb-md-0">
              <input :type="$store.state.tipeWaktu" class="form-control" id="tanggalDari" x-model="$store.state.tanggalDari">            
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="row">
          <div class="col-4">Sampai Waktu</div>
          <div class="col-8">
            <div class=" mb-2 mb-md-0">
              <input :type="$store.state.tipeWaktu" class="form-control" id="tanggalSampai" x-model="$store.state.tanggalSampai">
            </div>
          </div>
        </div>
      </div>
      
    </div>
    <div class="row mt-5">
      <div class="col-4">
        <div class="row">
          <div class="col-4">label pesanan</div> 
          <div class="col-8">
            <select class="form-select" id="id-label-pesanan" x-model="$store.state.idLabelPesanan">
            </select>
          </div>
        </div>
      </div>

      <div class="col-4">
        <div class="row">
          <div class="col-4">Label pengeluaran</div>
          <div class="col-8 ">
            <select class="form-select" id="id-label-pengeluaran" x-model="$store.state.idLabelPengeluaran">
            </select>
          </div>
        </div>
      </div>

      <div class="col-4 d-grid gap-2">
        <div class="row">
          <div class="col-6 d-grid">
            <button class="btn btn-primary" id="btn-tampilkan" @click="handleReset">Reset</button>
          </div>
          <div class="col-6 d-grid">
            <button class="btn btn-primary" id="btn-tampilkan" @click="handleFilter">Filter</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div x-init="tabel.draw()"></div>
  <div></div>
</div>

<!-- Container ke dua untuk menampilkan data -->
<div class="container mt-5 p-5 border border-1 rounded" x-data>
  <p class="fs-3">Laporan pemasukan dan pengeluaran</p>
  <template x-if="$store.informasi.show">
    <div>
      <div class="row mb-2">
        <div class="col-6 col-md-2">Dari Waktu</div>
        <div class="col-6 col-md-2"><span x-text="$store.informasi.tanggalDari"></span></div>
        <div class="col-6 col-md-2">Sampai Waktu</div>
        <div class="col-6 col-md-2"><span x-text="$store.informasi.tanggalSampai"></span></div>
      </div>
      <div class="row mb-2">
        <div class="col-6 col-md-2">Label Pesanan</div>
        <div class="col-6 col-md-2"><span x-text="$store.informasi.labelPesanan"></span></div>
        <div class="col-6 col-md-2">Label Pengeluaran</div>
        <div class="col-6 col-md-2"><span x-text="$store.informasi.labelPengeluaran"></span></div>
      </div>
      <div class="row mb-2">
        <div class="col-6 col-md-2">Total Uang Masuk</div>
        <div class="col-6 col-md-2">Rp. <span x-text="$store.informasi.totalUangMasuk"></span></div>
        <div class="col-6 col-md-2">Total Pengeluaran</div>
        <div class="col-6 col-md-2">Rp. <span x-text="$store.informasi.totalPengeluaran"></span></div>
      </div>
      <div class="row mb-2">
        <div class="col-6 col-md-2">Total Pendapatan</div>
        <div class="col-6 col-md-2">Rp. <span x-text="$store.informasi.totalPendapatan"></span></div>
      </div>
    </div>
  </template>
  <div class="table-responsive">
    <table id="tabel" class="table table-striped  dataTable">
      <thead>
        <tr>
          <th>No</th>
          <th>Uang Masuk</th>
          <th>Pengeluaran</th>
          <th>Pendapatan</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
      <tr>
        <th>No</th>
        <th>Uang Masuk</th>
        <th>Pengeluaran</th>
        <th>Pendapatan</th>
        <th>Waktu</th>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
</div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>

  function aturWaktuLaporanDefault() {
    // const storeState = Alpine.store('state');

    // const tanggalSekarang = dayjs()
    // const tanggalAwal = tanggalSekarang.startOf("month");

    // storeState.tipeWaktu = 'date';
    // storeState.tanggalDari = tanggalAwal.format("YYYY-MM-DD");
    // storeState.tanggalSampai = tanggalSekarang.format("YYYY-MM-DD");
  }

  function handleChangeTipeWaktu() {
    const storeState = Alpine.store('state');
    let tanggalSekarang = dayjs()
    let tanggalAwal;
    if (storeState.tipeWaktu == 'date') {
      tanggalAwal = tanggalSekarang.startOf("month");
      storeState.tanggalDari = null;
      storeState.tanggalSampai = null;
      storeState.tanggalDari = tanggalAwal.format("YYYY-MM-DD");
      storeState.tanggalSampai = tanggalSekarang.format("YYYY-MM-DD");
    }

    else if (storeState.tipeWaktu == 'month') {
      tanggalAwal = tanggalSekarang.startOf('year');
      storeState.tanggalDari = null;
      storeState.tanggalSampai = null;
      storeState.tanggalDari = tanggalAwal.format("YYYY-MM");
      storeState.tanggalSampai = tanggalSekarang.format("YYYY-MM");
    }
  }

  function aturWaktuDari() {
    const storeState = Alpine.store('state');
    if (storeState.tanggalDari == "")
    return "-";
    const waktu = dayjs(storeState.tanggalDari);

    if (storeState.date == 'month')
      return waktu.format("MMMM YYYY")

    return waktu.format("DD MMMM YYYY");
  }

  function aturWaktuSampai() {
    const storeState = Alpine.store('state');
    let waktuDari = $("#tanggalDari").val();
    if (waktuDari == "")
      return "-";
    const waktu = dayjs(waktuDari);

    if (storeState.date == 'month')
      return waktu.format("MMMM YYYY")

    return waktu.format("DD MMMM YYYY");
  }

  document.addEventListener('alpine:init', () => {
    Alpine.store('state', {
      tipeWaktu: 'date',
      tanggalDari: null,
      tanggalSampai: null
    })

    Alpine.store('informasi', {
      show: false,
      tanggalDari: null,
      tanggalSampai: null,
      labelPesanan: null,
      labelPengeluaran: null,
      TotalUangMasuk: null,
      TotalPendapatan: null,
      TotalPengeluaran: null
    })

    handleChangeTipeWaktu();
  })

  function handleFilter() {
    tabel.draw();
  }

  function handleReset() {
    const storeState = Alpine.store('state');
    $("#id-label-pesanan").val(null).trigger('change');
    $("#id-label-pengeluaran").val(null).trigger('change'); 
    storeState.tipeWaktu = 'date';
    handleChangeTipeWaktu();
  }
  
  const elIdLabelPesanan = $("#id-label-pesanan");
  const elIdLabelPengeluaran = $("#id-label-pengeluaran");

  elIdLabelPesanan.select2({
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

  elIdLabelPengeluaran.select2({
    allowClear: true,
    placeholder: 'pilih label pengeluaran',
    ajax: {
      url: "/api/LabelPengeluaran/findByFilters",
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

  let tabel = $('#tabel').DataTable({
    drawCallback: function( settings ) {
      const storeState = Alpine.store('state');
      const storeInformasi = Alpine.store('informasi');

      if (!storeInformasi.show) storeInformasi.show = true;

      const rows = tabel.rows().data();
      const labelPesanan = $("#id-label-pesanan").select2('data')?.[0]?.text ?? 'semua';
      const labelPengeluaran = $("#id-label-pengeluaran").select2('data')?.[0]?.text ?? 'semua';

      storeInformasi.labelPesanan = labelPesanan;
      storeInformasi.labelPengeluaran = labelPengeluaran

      if (storeState.tanggalDari == "")
        storeInformasi.tanggalDari = "-";
      else if (storeState.tipeWaktu == "date")
        storeInformasi.tanggalDari = dayjs(storeState.tanggalDari).format("DD MMMM YYYY")
      else if (storeState.tipeWaktu == "month")
        storeInformasi.tanggalDari = dayjs(storeState.tanggalDari).format("MMMM YYYY")

      if (storeState.tanggalSampai == "")
        storeInformasi.tanggalSampai = "-";
      else if (storeState.tipeWaktu == "date")
        storeInformasi.tanggalSampai = dayjs(storeState.tanggalSampai).format("DD MMMM YYYY")
      else if (storeState.tipeWaktu == "month")
        storeInformasi.tanggalSampai = dayjs(storeState.tanggalSampai).format("MMMM YYYY")

      storeInformasi.totalUangMasuk = rows.reduce(function(total, row) {
        return total + parseInt(row.pemasukan);
      }, 0);

      storeInformasi.totalPengeluaran = rows.reduce(function(total, row) {
        return total + parseInt(row.pengeluaran);
      }, 0); 

      storeInformasi.totalPendapatan = storeInformasi.totalUangMasuk - storeInformasi.totalPengeluaran;

      

    },
    ajax: {
      url: "<?= base_url('/api/laporan/selectDatatable') ?>",
      data: function(d) {
        let tipe_waktu = $("#tipeWaktu").val();
        let tanggal_dari = $('#tanggalDari').val();
        let tanggal_sampai = $('#tanggalSampai').val();
        let id_label_pesanan = $('#id-label-pesanan').val();
        let id_label_pengeluaran = $('#id-label-pengeluaran').val();

        if (tipe_waktu != null) {
          d.tipe_waktu = tipe_waktu;
        }

        if (tanggal_dari != "")
          d.tanggal_dari = tanggal_dari;

        if (tanggal_sampai != "")
          d.tanggal_sampai = tanggal_sampai;

        if (id_label_pesanan != null) {
          d.id_label_pesanan = id_label_pesanan
        }

        if (id_label_pengeluaran != null) {
          d.id_label_pengeluaran = id_label_pengeluaran
        }

      },
    },
    processing: true,
    serverSide: true,
    paging: false,
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {data: 'pemasukan'},
      {data: 'pengeluaran'},
      {data: 'selisih'},
      {
        data: 'waktu',
        render: function(data, type, row, meta) {
          const storeState = Alpine.store('state');
          if (storeState.tipeWaktu == 'month') {
            const waktu = dayjs(data).format("MMMM YYYY")
            return waktu;
          }
            return dayjs(data).format("DD MMMM YYYY");
        }
      },
    ]
  });
  
</script>
<?= $this->endSection() ?>