<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grizzlys Gym - Error 404 </title>

    <meta name="description" content="Grizzlys Gym">
    <link rel="icon" href="assets/img/icons/logo-oso.png">

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/bootstrap-icons/bootstrap-icons.min.css">

</head>

<body>

    <div id="layoutError">
        <div id="layoutError_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="text-center mt-4">
                                <img class="mb-4 img-error" src="assets/img/error-404-monochrome.svg" />
                                <p class="lead">La solicitud URL no fue encontrada en el servidor.</p>
                                <a href="#" class="text-primary" id='goBack'>
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Volver atras
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutError_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>


    <script type="text/javascript" src="assets/js/librerias/bootstrap.bundle.min.js"></script>
    <script>
        const button = document.querySelector('#goBack');

        button.addEventListener('click', () => window.history.back());
    </script>

</body>

</html>