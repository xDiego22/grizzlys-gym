<?php

use config\components\configSystem as configSystem;
use model\membresiasModel;

$config = new configSystem;


if (is_file($config->_Dir_View_() . $pagina . $config->_VIEW_())) {

    $obj = new membresiasModel();

    if (isset($_POST['accion'])) {

        $accion = $_POST['accion'];

        if ($accion == 'getClients') {

            echo $obj->getClients();

            exit;
        }
        if ($accion == 'editar') {

            $id = $_POST['id'];
            $fecha_inicial = $_POST['fecha_inicial'];
            $fecha_limite = $_POST['fecha_limite'];
            echo $obj->updateMembership($id,$fecha_inicial,$fecha_limite);

            exit;
        }
    }

    $clientes = $obj->clientsInfo();
    require_once($config->_Dir_View_() . $pagina . $config->_VIEW_());
} else {
    echo "pagina en construccion";
}
