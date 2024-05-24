<?php

use config\components\configSystem as configSystem;
use model\pagosModel;

$config = new configSystem;


if (is_file($config->_Dir_View_() . $pagina . $config->_VIEW_())) {

    $obj = new pagosModel();

    if (isset($_POST['accion'])) {

        $accion = $_POST['accion'];

        if ($accion === 'getPays') {

            echo $obj->getPays();
            exit;
        }

        if ($accion === 'client_pay') {
            
            $id = $_POST['id'];
            $usuario_sesion = $_SESSION['cedula'];
            $monto = $_POST['monto'];
            
            echo $obj->clientPay($id, $monto,$usuario_sesion);
            exit;
        }

        if ($accion === 'info_client_pay') {

            $id = $_POST['id'];

            echo $obj->infoClientPay($id);
            exit;
        }
    }
    $clientes = $obj->getClients();

    require_once($config->_Dir_View_() . $pagina . $config->_VIEW_());
} else {
    echo "pagina en construccionmm";
}
