<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Rumah Makan Padang</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
    
  </head>

  <body>
    <!-- Section: Design Block -->
    <section class="text-center">
      <!-- Background image -->
      <div
        class="p-5 bg-image"
        style="
          background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
          height: 300px;
        "
      ></div>

      <div
        class="card mx-4 mx-auto shadow-5-strong mb-5"
        style="
          margin-top: -100px;
          background: hsla(0, 0%, 100%, 0.8);
          backdrop-filter: blur(30px);
          max-width: 600px;
        "
      >
        <div class="card-body py-5" x-data>
          <h2 class="fw-bold mb-5">Sign up now</h2>
          <form id="form">
            <div class="form-floating">
              <input type="email" id="email" class="form-control" placeholder="name@example.com" name="password" x-model="$store.form.email">
              <label for="email">Email</label>
            </div>
            <div class="form-floating mt-3">
              <input type="password" id="password" class="form-control" placeholder="Password" name="password" x-model="$store.form.password">
              <label for="password">Password</label>
            </div>
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mt-4 py-3" style="width: 100%;" @click="handleSubmit">
                  Sign up
            </button>
          </form>
        </div>
      </div>
    </section>

    <script>
    document.addEventListener('alpine:init', () => {
      Alpine.store('form', {
        email: '',
        password: ''
      })
      // Alpine.store('validasi', {})
      // Alpine.store('state', {
      //   loading: false,
      //   status: null
      // })
    })
      async function handleSubmit(e) {
        e.preventDefault()
        const form = document.getElementById('form')
        const data = Alpine.store('form')

        try {
          const res = await axios.post('/api/login', data)
          console.log(res)
        } catch(error) {

        }
      }
    </script>
  </body>
</html>
