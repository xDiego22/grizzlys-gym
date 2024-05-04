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
                            <h2 class="card-title mt-2 mb-4">Gestion de usuarios</h2>

                            <div class="row ">
                                <div class="col-md-auto">

                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalGestion" id = 'btn_registrar'>
                                        Registrar
                                    </button>

                                </div>
                            </div>

                            <table id="tableUsers" class="table table-hover table-striped mt-3" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Cedula</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Tlf.</th>
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

                                        <input class="form-control validar" maxlength="10" type="number" name="cedula" id="cedula" placeholder="">
                                        <label for="cedula" class="form-label">Cedula</label>
                                        <div class="invalid-feedback">Por favor ingrese una cedula válida.</div>
                                    </div>
                                </div>
                            </div>


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
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="form-floating">
                                        <input class="form-control validar" maxlength="30" type="password" name="contrasena" id="contrasena" placeholder="">
                                        <label for="contrasena" class="form-label">Contraseña</label>
                                        <div class="invalid-feedback">Por favor ingrese una contraseña válida.</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-md-0">
                                        <input class="form-control validar" maxlength="30" type="password" name="contrasena2" id="contrasena2" placeholder="">
                                        <label for="contrasena2" class="form-label">Confirmar Contraseña</label>
                                        <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text mb-3 mb-md-3">Debe contener al menos una mayúscula, un número y mínimo 8 caracteres.</div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <input type="email" class="form-control validar" id="correo" name="correo" placeholder="">
                                        <label for="correo" class="form-label">Correo</label>
                                        <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <input class="form-control validar" maxlength="11" type="number" name="telefono" id="telefono" placeholder="">
                                        <label for="telefono" class="form-label">Tlf.</label>
                                        <div class="invalid-feedback">Por favor ingrese un teléfono válido.</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer" >
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id='registrar' class="btn btn-success">Guardar</button>
                        <button type="submit" id='editar' class="btn btn-success">Editar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once('bin/component/scripts.php'); ?>
    <script src="./assets/js/usuarios.js"></script>

</body>

</html>