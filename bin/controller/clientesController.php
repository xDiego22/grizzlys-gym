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
            $plan = $_POST['planes'];
            $monto = $_POST['monto'];
            $fecha_inicial = $_POST['fecha_inicial'];
            $fecha_limite = $_POST['fecha_limite'];
            $usuario_sesion = $_SESSION['cedula'];
            
            echo $obj->registerClient($cedula,$nombre,$telefono,$plan,$monto,$fecha_inicial,$fecha_limite, $usuario_sesion);
            exit;
        }

        if ($accion === 'editar') {
            
            $id = $_POST['id'];
            $cedula = $_POST['cedula'];
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $plan = $_POST['planes'];
            
            echo $obj->updateClient($id,$cedula,$nombre,$telefono,$plan);
            exit;
        }

        if ($accion === 'valor_plan') {
            
            $plan = $_POST['planes'];
            
            echo $obj->valorPlan($plan);
            exit;
        }

        if ($accion === 'client_pay') {

            $id = $_POST['id'];
            $usuario_sesion = $_SESSION['cedula'];
            $monto = $_POST['monto'];

            echo $obj->clientPay($id, $monto, $usuario_sesion);
            exit;
        }
        if ($accion === 'client_renew') {

            $usuario_sesion = $_SESSION['cedula'];
            $id = $_POST['id'];
            $plan = $_POST['plan'];
            $monto = $_POST['monto'];
            $fecha_inicial = $_POST['fecha_inicial'];
            $fecha_limite = $_POST['fecha_limite'];

            echo $obj->clientRenew($usuario_sesion,$id,$plan, $monto, $fecha_inicial,$fecha_limite);
            exit;
        }

        if ($accion === 'info_client_pay') {
            
            $id = $_POST['id'];
            
            echo $obj->infoClientPay($id);
            exit;
        }

        if ($accion === 'eliminar') {
            
            $id = $_POST['id'];
            
            echo $obj->deleteClient($id);
            exit;
        }
        exit;
    }

    $planes = $obj->getPlanes();

    require_once($config->_Dir_View_() . $pagina . $config->_VIEW_());
} else {
    echo "pagina en construccion";
}
