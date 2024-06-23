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
                            <div class="mt-4 mb-3">
                                <div class="row justify-content-between">
                                    <div class="col-auto me-auto mb-2">
                                        <div class="h4 text-dark">Perfil de Usuario</div>
                                    </div>
                                    <div class="col-auto">

                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalContrasena" id="boton_contrasena">
                                            <i class="bi bi-key-fill"></i>
                                        </button>

                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar" id="btn_editar">
                                            <i class="bi bi-pencil-fill me-1"></i>Editar
                                        </button>

                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="mt-4">

                                <div class='h2 text-center mb-4'>
                                    <span id='nombre_info'> <?= $info_usuario['nombre'] ?> </span>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        Cedula: <span id='cedula_info'> <?= $info_usuario['cedula'] ?> </span>
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12 text-wrap text-truncate">
                                        Correo Electrónico: <span id='correo_info'> <?= $info_usuario['correo'] ?> </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12 text-wrap text-truncate">
                                        Teléfono: <span id='telefono_info'> <?= $info_usuario['telefono'] ?> </span>
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

    <!-- Modal perfil-->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Perfil</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formEditUser">
                    <div class="modal-body">

                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <input type="text" maxlength="50" class="form-control validar_editar" id="nombre" name="nombre" placeholder="">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <div class="invalid-feedback">Por favor ingrese un nombre válido.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <input type="email" class="form-control validar_editar" id="correo" name="correo" placeholder="">
                                        <label for="correo" class="form-label">Correo</label>
                                        <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <input class="form-control validar_editar" maxlength="11" type="text" name="telefono" id="telefono" placeholder="">
                                        <label for="telefono" class="form-label">Tlf.</label>
                                        <div class="invalid-feedback">Por favor ingrese un teléfono válido.</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id='editar' class="btn btn-primary"><i class="bi bi-pencil-fill me-1"></i>Editar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal contrasena-->
    <div class="modal fade" id="modalContrasena" tabindex="-1" aria-labelledby="modalContrasenaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalContrasenaLabel">Cambiar Contraseña</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formChangePassword">
                    <div class="modal-body">

                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <input class="form-control validar_password" maxlength="30" type="password" name="contrasena_actual" id="contrasena_actual" placeholder="">
                                        <label for="contrasena_actual" class="form-label">Contraseña Actual</label>
                                        <div class="invalid-feedback">Por favor ingrese una contraseña válida.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="form-floating">
                                        <input class="form-control validar_password" maxlength="30" type="password" name="contrasena" id="contrasena" placeholder="">
                                        <label for="contrasena" class="form-label">Contraseña</label>
                                        <div class="invalid-feedback">Por favor ingrese una contraseña válida.</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-md-0">
                                        <input class="form-control validar_password" maxlength="30" type="password" name="contrasena2" id="contrasena2" placeholder="">
                                        <label for="contrasena2" class="form-label">Confirmar Contraseña</label>
                                        <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text mb-3 mb-md-3">Debe contener al menos una mayúscula, un número y mínimo 8 caracteres.</div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" id="showPassword" type="checkbox" value="" />
                                <label class="form-check-label" for="showPassword">Mostrar Contraseña</label>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id='cambiar' class="btn btn-primary"><i class="bi bi-pencil-fill me-1"></i>Cambiar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once('bin/component/scripts.php'); ?>
    <script src="./assets/js/profile.js"></script>


</body>

</html>