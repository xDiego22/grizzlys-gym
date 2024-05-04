<?php

use config\components\configSystem as configSystem;
use model\clientesModel;

$config = new configSystem;


if (is_file($config->_Dir_View_() . $pagina . $config->_VIEW_())) {

    $obj = new clientesModel();

    if (isset($_POST['accion'])) {

        $accion = $_POST['accion'];


        if ($accion === 'getClients') {


            echo $obj->getClients();
            exit;
        }
        if ($accion === 'registrar') {
            
            $cedula = $_POST['cedula'];
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $membresia = $_POST['membresias'];
            
            echo $obj->registerClient($cedula,$nombre,$telefono,$membresia);
            exit;
        }
        if ($accion === 'valor_membresia') {
            
            $membresia = $_POST['membresias'];
            
            echo $obj->valorMembresia($membresia);
            exit;
        }
        exit;
    }

    $membresias = $obj->getMembresias();

    require_once($config->_Dir_View_() . $pagina . $config->_VIEW_());
} else {
    echo "pagina en construccion";
}
