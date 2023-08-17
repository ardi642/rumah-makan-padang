<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta
      name="author"
      content="Mark Otto, Jacob Thornton, and Bootstrap contributors"
    />
    <meta name="generator" content="Hugo 0.104.2" />
    <title>Rumah Makan Nasi Padang</title>

    <link
      rel="canonical"
      href="https://getbootstrap.com/docs/5.2/examples/sign-in/"
    />

    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet" />

    <script src="../jquery/jquery-3.6.0.min.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>

    <script src="../sweetalert2/sweetalert2@11.js"></script>
    <script src="../select2/select2.min.js"></script>
    <script src="../axios/axios.min.js"></script>
    <script defer src="../alpinejs/alpinejs@3.x.x_dist_cdn.min.js"></script>

    <style>
      [x-cloak] { display: none !important; }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        height: 3rem;
        background-color: rgba(0, 0, 0, 0.1);
        border: solid rgba(0, 0, 0, 0.15);
        border-width: 1px 0;
        box-shadow: inset 0 0.5em 1.5em rgba(0, 0, 0, 0.1),
          inset 0 0.125em 0.5em rgba(0, 0, 0, 0.15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -0.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      html,
      body {
        height: 100%;
      }

      body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 330px;
        padding: 15px;
      }

      .form-signin .form-floating:focus-within {
        z-index: 2;
      }

      .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }

      .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
    </style>

  </head>
  <body class="text-center" x-data x-cloak>
    <main class="form-signin w-100 m-auto">
      <div>
        <img
          class="mb-4" src="../logo/rmnarendra-logo.png" alt="" width="200"
        />
        <h1 class="h3 mb-3 fw-normal">Silahkan Login</h1>

        <div class="form-floating">
          <input type="email" class="form-control" id="floatingInput" placeholder="masukkan email" x-model="$store.data.email" />
          <label for="floatingInput">Email</label>
        </div>
        <div class="alert alert-danger mt-2 p-2" role="alert" x-show="$store.validasi.email != null">
          <span x-text="$store.validasi.email"></span>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" placeholder="masukkan password" x-model="$store.data.password" />
          <label for="floatingPassword">Password</label>
        </div>
        <div class="alert alert-danger mt-2 p-2" role="alert" x-show="$store.validasi.password != null">
        <span x-text="$store.validasi.password"></span>
        </div>

          <template x-if="!$store.state.loading">
          <button class="w-100 btn btn-lg btn-primary" @click="handleLogin">
            <span>Masuk</span>
          </button>
          </template>
          <template x-if="$store.state.loading">
          <button class="w-100 btn btn-lg btn-primary">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            proses...
          </button>
          </template>
          <button class="w-100 btn btn-lg btn-primary mt-2" @click="handleReset">
            <span>Reset</span>
          </button>
        <p class="mt-5 mb-3 text-muted">Created with <span>❤️</span> By SnowFall Team</p>
      </div>
    </main>
  </body>
  <script>
    document.addEventListener("alpine:init", () => {

      Alpine.store("data", {
        email: '',
        password: ''
      })

      Alpine.store("validasi", {
        email: null,
        password: null,
      });

      Alpine.store("state", {
        loading: false
      })
    });

    async function handleLogin() {
      const storeState = Alpine.store('state')
      const storeValidasi = Alpine.store('validasi')
      const data = Alpine.store('data');
      try {
        storeState.loading = true
        const response = await axios.post('/api/login', data);
        location.href = "/";
      }
      catch (error) {
        if (error.response.status == 400) {
          const validasi = error.response.data.validasi
          Alpine.store('validasi', validasi)
        }
      }
      finally {
        storeState.loading = false
      }
    }

    function handleReset() {
      const storeValidasi = Alpine.store('validasi');
      const storeData = Alpine.store('data');
      storeValidasi.email = null;
      storeValidasi.password = null;

      storeData.email = '';
      storeData.password = '';
    }

  </script>
</html>
