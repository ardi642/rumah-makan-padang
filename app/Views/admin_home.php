<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<!-- Custom Style -->
<style>
  header{
    background-color: #0D99FF;
  }
  .nav li .nav-link {
    margin-right: 3rem;
  }
  .card-deck .card {
    /*padding-bottom: 0.5rem;*/
    /*padding-top: : 0.5rem;*/
    padding: 1rem;
    margin: 1rem;
    width: 25rem;
    height: 12rem;
  }
</style>
        <div class="container">
          <div class="pt-2 mt-4 mb-0">
            <!-- <p>Selamat Datang <strong></strong></p> -->
            <!-- <h3><?= session()->get('nama_karyawan') ?></h3> -->
            <p>Selamat Datang <strong><?= session()->get('nama_karyawan') ?></strong> <span class="text-muted">(<?= session()->get('level') ?>)</span> </p>
          </div>

          <!-- Menampilkan Grafik -->
          <!-- <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas> -->
          <div class="card-deck row justify-content-between">
            <div class="card col-12 col-lg-4 shadow  bg-success text-white">
              <div class="card-body">
                <h5 class="card-title">Pendapatan</h5>
                <h1>Rp 1,500,000</h1>
              </div>
            </div>
            <div class="card col-12 col-lg-4 shadow bg-warning text-dark">
              <div class="card-body">
                <h5 class="card-title">Uang masuk</h5>
                <h1 class="">Rp 1,500,000</h1>
              </div>
            </div>
            <div class="card col-12 col-lg-4 shadow bg-danger text-white ">
              <div class="card-body">
                <h5 class="card-title">Pengeluaran</h5>
                <h1 class="">RP 1,500,000 </h1>
              </div>
            </div>
          </div>
        </div>
          
        <div class="container mt-5 p-5 border border-1 rounded">
            <div class="row"> 
              <canvas class="my-4 w-100" id="myChart"></canvas>
            </div>
        </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('myChart');

  async function getTransaction() {
    let dataChart = [
      { "day_of_week": "Monday", "total_bayar_sum": "0" },
      { "day_of_week": "Tuesday", "total_bayar_sum": "0" },
      { "day_of_week": "Wednesday", "total_bayar_sum": "0" },
      { "day_of_week": "Thursday", "total_bayar_sum": "0" },
      { "day_of_week": "Friday", "total_bayar_sum": "0" },
      { "day_of_week": "Saturday", "total_bayar_sum": "0" },
    ]
    try {
      const {data} = await axios.get('api/home/transaction')
      data.data.forEach((newItem) => {
        const index = dataChart.findIndex((item) => item.day_of_week === newItem.day_of_week);
        if (index !== -1) {
          dataChart[index].total_bayar_sum = newItem.total_bayar_sum;
        }
      });
      return dataChart
    } catch(error) {
      console.log(error)
    }
  }

  async function createChart() {
    try {
      const data = await getTransaction()
      let totalBayarSumArray = data.map(item => parseInt(item.total_bayar_sum));

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
          datasets: [{
            label: 'Pembelian harian',
            data: totalBayarSumArray,
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    } catch(error) {
      console.log(error)
    }
  }

  createChart()
  </script>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js" integrity="sha384-gdQErvCNWvHQZj6XZM0dNsAoY4v+j5P1XDpNkcM3HJG1Yx04ecqIHk7+4VBOCHOG" crossorigin="anonymous"></script>

<?= $this->endSection() ?>