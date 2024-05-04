<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once('bin/component/head.php'); ?>

</head>

<body style='background-color: #D6DCE5;
    background-image: linear-gradient(to bottom, #d9e8ff, #D6DCE5); '>

    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header">
                            <h3 class="text-center font-weight-light my-4">¡Bienvenido!</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="" id="formLogin">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <div class="form-floating ">
                                        <input class="form-control validar" id="cedula" name="cedula" type="text" placeholder="" />
                                        <label for="cedula">Cedula</label>

                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <div class="form-floating">
                                        <input class="form-control validar" id="contrasena" name="contrasena" type="password" placeholder="" />
                                        <label for="contrasena">Contraseña</label>
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" id="showPassword" type="checkbox" value="" />
                                    <label class="form-check-label" for="showPassword">Mostrar Contraseña</label>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <a class="small" href="?pagina=forgotPassword">Olvidaste tu constraseña?</a>
                                    <button type="submit" id='entrar' class="btn btn-primary">Entrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once('bin/component/scripts.php'); ?>
    <script type="text/javascript" src="assets/js/login.js"></script>

</body>

</html>