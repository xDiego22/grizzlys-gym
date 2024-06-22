<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Principal</div>
                <a class="nav-link" href="?pagina=home">
                    <div class="sb-nav-link-icon">
                        <i class="bi bi-house" style="color:#fff"></i>
                    </div>
                    Inicio
                </a>

                <!-- --- -->
                <div class="sb-sidenav-menu-heading">Modulos</div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts1">
                    <div class="sb-nav-link-icon">
                        <i class="bi bi-columns" style="color:#fff"></i>
                    </div>
                    Gestion
                    <div class="sb-sidenav-collapse-arrow">
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </a>

                <div class="collapse" id="collapseLayouts1" aria-labelledby="heading1" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="?pagina=clientes">Clientes</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="?pagina=planes">Planes</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="?pagina=membresias">Membresias</a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="?pagina=pagos">Pagos</a>
                    </nav>
                </div>



                <!-- --- -->


                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts2">
                    <div class="sb-nav-link-icon">
                        <i class="bi bi-gear" style="color:#fff"></i>
                    </div>
                    Seguridad
                    <div class="sb-sidenav-collapse-arrow">
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </a>

                <div class="collapse" id="collapseLayouts2" aria-labelledby="heading2" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="?pagina=usuarios">Usuarios</a>
                    </nav>
                </div>
                <!-- <div class="sb-sidenav-menu-heading">Addons</div>
                <a class="nav-link" href="charts.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Charts
                </a> -->
            </div>
        </div>
        <div class="sb-sidenav-footer text-break text-white">
            <div class="small">Iniciado como:</div>
            <?= $_SESSION["nombre"] ?? NULL;?>
        </div>
    </nav>
</div>