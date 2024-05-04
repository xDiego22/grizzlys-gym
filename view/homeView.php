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
                    <h1 class="mt-4">Principal</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Principal</li>
                    </ol>

                </div>
                
            </main>

            <?php require_once('bin/component/footer.php'); ?>
        </div>

    </div>

    <?php require_once('bin/component/scripts.php'); ?>

</body>

</html>