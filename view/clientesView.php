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
                                    <h2 class="card-title">Gestión de Clientes</h2>
                                </div>
                                <div class="col-auto">

                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalGestion" id='btn_registrar'>
                                        Registrar
                                    </button>

                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tableClients" class="table table-hover table-striped mt-3" style="width: 100%;" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="d-none">id_planes</th>
                                            <th>Cedula</th>
                                            <th>Nombre</th>
                                            <th>Tlf.</th>
                                            <th>Plan</th>
                                            <th>Desde</th>
                                            <th>Hasta</th>
                                            <th>Dias Rest.</th>
                                            <th>Saldo</th>
                                            <th>Estado</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <!-- Aquí van las filas de la tabla -->
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
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <input class="form-control validar" maxlength="11" type="number" name="telefono" id="telefono" placeholder="">
                                        <label for="telefono" class="form-label">Tlf.</label>
                                        <div class="invalid-feedback">Por favor ingrese un teléfono válido.</div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-8">
                                    <div class="form-floating mb-3">

                                        <select class="form-control validar" name="planes" id="planes" aria-label="Floating label planes">
                                            <option value="" hidden selected>Seleccionar opcion</option>

                                            <?php foreach ($planes as $plan) : ?>

                                                <option value="<?= $plan['id'] ?>"> <?= $plan['nombre'] ?> </option>

                                            <?php endforeach ?>
                                        </select>
                                        <label for="planes">Plan</label>
                                        <div class="invalid-feedback">Por favor ingrese una opcion válida.</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating mb-3">

                                        <input class="form-control" disabled type="text" id="precio_plan" placeholder="">
                                        <label for="precio_plan" class="form-label">Precio</label>

                                    </div>
                                </div>
                            </div>

                            <div class="row" id="fila_monto">
                                <div class="col-8">
                                    <div class="form-floating mb-3">

                                        <input class="form-control validar" type="number" min="0.0" max="200.0" name="monto" id="monto" placeholder="">
                                        <label for="monto" class="form-label">Monto a pagar</label>
                                        <div class="invalid-feedback">Por favor ingrese un monto válido.</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating mb-3">

                                        <input class="form-control" disabled type="text" id="saldo" placeholder="">
                                        <label for="saldo" class="form-label">Saldo</label>

                                    </div>
                                </div>

                            </div>

                            <div class="row" id="fila_fecha">
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
                        <button type="submit" id='registrar' class="btn btn-success">Guardar</button>
                        <button type="submit" id='editar' class="btn btn-primary">Editar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal pagar -->

    <!-- Modal -->
    <div class="modal fade" id="modalPay" tabindex="-1" aria-labelledby="modalPayLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalPayLabel">Pagar Deuda</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formUserPay">
                    <div class="modal-body">


                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">

                                        <input class="form-control" name="cedulaPay" id="cedulaPay" placeholder="" readonly disabled>
                                        <label for="cedulaPay" class="form-label">Cedula</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">

                                        <input class="form-control" name="nombrePay" id="nombrePay" placeholder="" readonly disabled>
                                        <label for="nombrePay" class="form-label">Nombre</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="planPay" id="planPay" placeholder="" readonly disabled>
                                        <label for="planPay" class="form-label">Plan</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="precioPay" id="precioPay" placeholder="" readonly disabled>
                                        <label for="precioPay" class="form-label">Precio</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="saldoPay" id="saldoPay" placeholder="" readonly disabled>
                                        <label for="saldoPay" class="form-label">Saldo Antes</label>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="saldoNewPay" id="saldoNewPay" placeholder="" disabled>
                                        <label for="saldoNewPay" class="form-label">Saldo Nuevo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" name="montoPay" id="montoPay" type="number" min="0.0" max="200.0" placeholder="">
                                        <label for="montoPay" class="form-label">Monto a pagar</label>
                                        <div class="invalid-feedback">Por favor ingrese un monto válido.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id='pagar' class="btn btn-success">Pagar $</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once('bin/component/scripts.php'); ?>
    <script src="./assets/js/clientes.js"></script>

</body>

</html>