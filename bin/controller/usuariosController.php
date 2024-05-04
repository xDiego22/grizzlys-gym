<?php

use config\components\configSystem as configSystem;
use model\usuariosModel;

$config = new configSystem;


if (is_file($config->_Dir_View_() . $pagina . $config->_VIEW_())) {

    $obj = new usuariosModel();

    if (isset($_POST['accion'])) {

        $accion = $_POST['accion'];
        

        if ($accion === 'getUsers') {


            echo $obj->getUsers();
            exit;
        }
        if ($accion === 'registrar') {

            $cedula = $_POST['cedula'];
            $nombre = $_POST['nombre'];
            $contrasena = $_POST['contrasena'];
            $contrasena2 = $_POST['contrasena2'];
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];


            echo $obj->registerUser($cedula,$nombre,$contrasena,$contrasena2,$correo,$telefono);
            exit;
        }
        if ($accion === 'editar') {

            $cedula = $_POST['cedula'];
            $nombre = $_POST['nombre'];
            $contrasena = $_POST['contrasena'];
            $contrasena2 = $_POST['contrasena2'];
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];


            echo $obj->updateUser($cedula,$nombre,$contrasena, $contrasena2,$correo,$telefono);
            exit;
        }
        if ($accion === 'eliminar') {

            $cedula = $_POST['cedula'];


            echo $obj->deleteUser($cedula);
            exit;
        }
        exit;
    }
    
    require_once($config->_Dir_View_() . $pagina . $config->_VIEW_());
} else {
    echo "pagina en construccion";
}
