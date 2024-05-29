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
                            <div class="row mt-2 mb-4">
                                <div class="col-auto me-auto mb-2">
                                    <h2 class="card-title">Gestión de Pagos</h2>
                                </div>
                                <div class="col-auto">

                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPay" id='btn_registrar'>
                                        Registrar
                                    </button>

                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tablePagos" class="table table-hover table-striped mt-3" style="width: 100%;" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Registrado por</th>
                                            <th>Cedula</th>
                                            <th>Nombre</th>
                                            <th>Fecha de Pago</th>
                                            <th>Monto</th>
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
    <div class="modal fade" id="modalPay" tabindex="-1" aria-labelledby="modalPayLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalPayLabel">Registrar Pago</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="formUserPay">
                    <div class="modal-body">


                        <div class="container-fluid">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-floating mb-3">

                                        <select class="form-control validar" name="cliente" id="cliente" aria-label="Floating label cliente">
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
                                        <input class="form-control validar" name="montoPay" id="montoPay" type="number" step="0.1" min="0.0" max="200.0" placeholder="">
                                        <label for="montoPay" class="form-label">Monto a pagar <span class="text-danger">*</span></label>
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
    <script src="./assets/js/pagos.js"></script>

</body>

</html>