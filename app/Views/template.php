<!DOCTYPE html>
<html lang="en">

<head>
  <title>Rumah Makan Padang</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/bootstrap/bootstrap.min.css" rel="stylesheet" />
  <link href="/select2/select2.min.css" rel="stylesheet" />
  <link href="/datatable/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link href="/datatable/responsive.bootstrap5.min.css" rel="stylesheet" />
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
 
    <!-- Bootstrap Font Icon CSS -->
    <link rel="stylesheet" href=
"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
  
  <script src="/jquery/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="/datatable/jquery.dataTables.min.js"></script>
  <script src="/datatable/dataTables.bootstrap5.min.js"></script>
  <script src="/datatable/dataTables.responsive.min.js"></script>
  <script src="/datatable/responsive.bootstrap5.js"></script>

  <script src="/sweetalert2/sweetalert2@11.js"></script>
  <script src="/select2/select2.min.js"></script>
  <script defer src="/alpinejs/alpinejs@3.x.x_dist_cdn.min.js"></script>
  <script src="/axios/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg sticky-top bg-primary navbar-dark">
    <div class="container">
      <a class="navbar-brand mb-0 h1" href="#">Rumah Makan Padang</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
          <?php foreach ($menus as $namaMenu => $menu) : ?>
            <?php if (!isset($menu['menus'])) : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($menuAktif == $namaMenu) ? "active" : "" ?>" href="<?= base_url($menu['url']) ?>">
                  <?= $menu['label'] ?>
                </a>
              </li>
            <?php else : ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?= ($menuAktif == $namaMenu) ? "active" : "" ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= $menu['label'] ?></a>
                <ul class="dropdown-menu">
                  <?php foreach ($menu['menus'] as $menuDropdown) : ?>
                    <li><a class="dropdown-item" href="<?= $menuDropdown['url'] ?>"><?= $menuDropdown['label'] ?></a></li>
                  <?php endforeach ?>
                </ul>
              </li>
            <?php endif ?>
          <?php endforeach ?>
        </ul>
      </div>
    </div>
  </nav>

  <?= $this->renderSection('content') ?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-12">
        <p class="text-center">Created with <span>❤️</span> By SnowFall Team</p>
      </div>
    </div>
  </div>
  <?= $this->renderSection('script') ?>
</body>

</html>