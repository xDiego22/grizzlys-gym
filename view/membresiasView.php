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
            <main class="bg-color-gray">
                <!-- Aqui va todo el contenido -->
                <div class="container-fluid px-3">

                    <div class="card shadow-sm rounded mt-3">
                        <div class="card-body">
                            <div class="row mt-2 mb-2">
                                <div class="col-auto me-auto mb-2">
                                    <h2 class="card-title">Gestión de Membresias</h2>
                                </div>
                                <div class="col-auto">

                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalGestion" id='btn_registrar'>
                                        Registrar
                                    </button>

                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tableMemberships" class="table table-hover table-striped mt-3" style="width: 100%;" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="d-none">id</th>
                                            <th>Cedula</th>
                                            <th>Nombre</th>
                                            <th>Desde</th>
                                            <th>Hasta</th>
                                            <th>Dias Rest.</th>
                                            <th>Estado</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>

            </main>

        </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalGestion" tabindex="-1" aria-labelledby="modalGestionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalGestionLabel">Editar Membresia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formManageMemberships">
                    <div class="modal-body">

                        <div class="container-fluid">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <select class="form-control validar" name="cliente" id="cliente" aria-label="Floating label cliente" disabled>
                                            <option value="" hidden selected>Seleccionar opcion</option>

                                            <?php foreach ($clientes as $cliente) : ?>

                                                <option value="<?= $cliente['id'] ?>"> <?= $cliente['cedula'] ?> - <?= $cliente['nombre'] ?> </option>

                                            <?php endforeach ?>
                                        </select>
                                        <label for="cliente">Cliente <span class="text-danger">*</span></label>
                                        <div class="invalid-feedback">Por favor ingrese una opcion válida.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fecha_inicial" class="form-label">F. Inicial</label>
                                    <input class="form-control validar" type="date" name="fecha_inicial" id="fecha_inicial">
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_limite" class="form-label">F. Limite</label>
                                    <input class="form-control validar" type="date" name="fecha_limite" id="fecha_limite">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id='editar' class="btn btn-primary">Editar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php require_once('bin/component/scripts.php'); ?>
    <script src="./assets/js/membresias.js"></script>

</body>

</html>