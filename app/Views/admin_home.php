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
      <div class="row">
        <main class="">
          <div class="pb-2 mt-3 mb-3">
            <p>Selamat Datang</p>
            <h3>Nama Kamu</h3>
          </div>

          <!-- Menampilkan Grafik -->
          <!-- <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas> -->
          <div class="card-deck row justify-content-center">
            <div class="card col-3 shadow  bg-success text-white">
              <div class="card-body">
                <h5 class="card-title">Pendapatan</h5>
                <h1>Rp 1,500,000</h1>
              </div>
            </div>
            <div class="card col-3 shadow bg-warning text-dark">
              <div class="card-body">
                <h5 class="card-title">Uang masuk</h5>
                <h1 class="">Rp 1,500,000</h1>
              </div>
            </div>
            <div class="card col-3 shadow bg-danger text-white ">
              <div class="card-body">
                <h5 class="card-title">Penegluaran</h5>
                <h1 class="">RP 1,500,000 </h1>
                <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>
              </div>
            </div>
          </div>


          
          

            
  
  <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js" integrity="sha384-gdQErvCNWvHQZj6XZM0dNsAoY4v+j5P1XDpNkcM3HJG1Yx04ecqIHk7+4VBOCHOG" crossorigin="anonymous"></script><script src="dashboard.js"></script>


<?= $this->endSection() ?>