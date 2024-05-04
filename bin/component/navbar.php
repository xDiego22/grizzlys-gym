<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="?pagina=home">
        <img class="me-2" src="./assets/img/icons/logo-oso.png" width="30px">
        Grizzlys Gym
    </a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><span class="navbar-toggler-icon"></span></button>
    <!-- Navbar Search-->
    <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
    </div>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">

        
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2 d-none d-lg-inline text-white small">
                    <?= $_SESSION['nombre'] ?? NULL; ?>
                </span>
                <img class="rounded-circle" src="./assets/img/profile.webp" alt="img profile" width="30px">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item" href="?pagina=profile">
                        <i class="bi bi-person-fill mr-2"></i>
                        Perfil
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li>
                    <a class="dropdown-item" href="?pagina=logout">
                        <i class="bi bi-box-arrow-in-left mr-2"></i>
                        Salir
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>