<?php

use config\components\configSystem as configSystem;
use model\profileModel;

$config = new configSystem;


if (is_file($config->_Dir_View_() . $pagina . $config->_VIEW_())) {

    $obj = new profileModel();

    if (isset($_POST['accion'])) {

        $accion = $_POST['accion'];

        if ($accion == "cambiarContrasena") {

            $cedula_sesion = $_SESSION['cedula'];
            $contrasena_actual = $_POST['contrasena_actual'];
            $contrasena = $_POST['contrasena'];
            $contrasena2 = $_POST['contrasena2'];

            echo $obj->cambiarContrasena($cedula_sesion,$contrasena_actual,$contrasena,$contrasena2);
            exit;
        }

        if ($accion == 'editar') {
            $cedula_sesion = $_SESSION['cedula'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];
            echo $obj->editarPerfil($cedula_sesion, $nombre, $correo,$telefono);
            exit;
        }
    }
    $cedula_sesion = $_SESSION['cedula'];
    $info_usuario = $obj->consultarPerfil($cedula_sesion);

    require_once($config->_Dir_View_() . $pagina . $config->_VIEW_());
} else {
    echo "pagina en construccion";
}
