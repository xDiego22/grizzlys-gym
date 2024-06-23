<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once('bin/component/head.php'); ?>
</head>

<body>
    <?php require_once('bin/component/navbar.php'); ?>

    <div id="layoutSidenav">

        <?php require_once('bin/component/SideBar.php'); ?>

        <div id="layoutSidenav_content">
            <main>
                <!-- Aqui va todo el contenido -->
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Bienvenido ¡<?= $_SESSION['nombre'] ?>! </h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>

                    <div class="row mt-4">

                        <!-- Ingreso (mensual) Card-->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border border-2 border-success rounded-4 shadow h-100 py-2 bg-opacity-10 bg-success">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col me-2">
                                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                                Ingreso Mensual (<?= $mesActual ?? NULL ?>)
                                            </div>
                                            <div class="h5 mb-0 fw-bold text-dark"><?= $info["ingreso"] ?? 0 ?> $</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Clientes Activos Card-->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border border-2 border-primary rounded-4 shadow h-100 py-2 bg-opacity-10 bg-primary">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col me-2">
                                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                                Clientes Activos
                                            </div>
                                            <div class="h5 mb-0 fw-bold text-dark"><?= $info["activos"] ?? 0 ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-xl-12">
                            <div class="card mb-4" >
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Ganancias por año <select name="year_chart" id="year_chart">
                                        <?php foreach ($years as $year) : ?>
                                            <option value="<?= $year['anio'] ?>"><?= $year['anio'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <div style="width: 100%; height: 350px;">

                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

            <?php require_once('bin/component/footer.php'); ?>
        </div>

    </div>

    <?php require_once('bin/component/scripts.php'); ?>
    <script src="./assets/js/home.js"></script>


</body>

</html>