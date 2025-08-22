<!-- app/Views/layout/navbar.php -->
<style>
  .bg-custom {
    background-color: whitesmoke;
  }

  .text-orange {
    color: orange !important;
  }
</style>

<nav class="navbar navbar-expand-lg bg-custom sticky-top shadow">
  <div class="container">
    <a class="navbar-brand" href="/">
      <img src="/img/logo.png" alt="Logo" height="55">
    </a>

    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-#000080" href="/home">Tracer Study</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-#000080" href="/tentang">Tentang</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-#000080" href="/kontak">Kontak</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-#000080" href="#">Respon TS</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-#000080" href="laporan">Laporan TS</a>
        </li>
      </ul>
    </div>
  </div>
</nav>