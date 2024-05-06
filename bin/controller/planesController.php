<?php

use config\components\configSystem as configSystem;
use model\planesModel;

$config = new configSystem;


if (is_file($config->_Dir_View_() . $pagina . $config->_VIEW_())) {

    $obj = new planesModel();

    if (isset($_POST['accion'])) {

        $accion = $_POST['accion'];


        
        if ($accion === 'getPlans') {
            echo $obj->getPlans();
            exit;
        }
        if ($accion === 'eliminar') {
            $id = $_POST['id'];
            echo $obj->deletePlan($id);
            exit;
        }
        if ($accion === 'registrar') {

            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $descripcion = $_POST['descripcion'];


            echo $obj->registerPlan($nombre, $precio, $descripcion);
            exit;
        }
        if ($accion === 'editar') {

            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $descripcion = $_POST['descripcion'];


            echo $obj->updatePlan($id,$nombre, $precio, $descripcion);
            exit;
        }
        exit;
    }

    require_once($config->_Dir_View_() . $pagina . $config->_VIEW_());
} else {
    echo "pagina en construccion";
}
