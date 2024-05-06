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
                            <h2 class="card-title mt-2 mb-4">Gestion de Planes</h2>

                            <div class="row ">
                                <div class="col-md-auto">

                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalGestion" id='btn_registrar'>
                                        Registrar
                                    </button>

                                </div>
                            </div>

                            <table id="tablePlans" class="table table-hover table-striped mt-3" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Descripción</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>

                            </table>
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
                    <h1 class="modal-title fs-5" id="modalGestionLabel">Registro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formManageUser">
                    <div class="modal-body">


                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <input type="text" maxlength="50" class="form-control validar" id="nombre" name="nombre" placeholder="">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <div class="invalid-feedback">Por favor ingrese un nombre válido.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <input class="form-control validar" type="number" min="0.0" max="300.0" step="0.1" name="precio" id="precio" placeholder="">
                                        <label for="precio" class="form-label">Precio</label>
                                        <div class="invalid-feedback">Por favor ingrese un precio válido.</div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">


                                        <textarea class="form-control validar" name="descripcion" id="descripcion" placeholder="" style="height: 100px"></textarea>
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <div class="invalid-feedback">Por favor ingrese carácteres válidos.</div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id='registrar' class="btn btn-success">Guardar</button>
                        <button type="submit" id='editar' class="btn btn-success">Editar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once('bin/component/scripts.php'); ?>
    <script src="./assets/js/planes.js"></script>

</body>

</html>