<?php
use config\components\configSystem as configSystem;
use model\loginModel as loginModel;

$config = new configSystem;


if (is_file($config->_Dir_View_() . $pagina . $config->_VIEW_())) {

    $objeto = new loginModel();
    
    if (isset($_POST['accion'])) {

        $accion = $_POST['accion'];

        if ($accion === 'login') {

            $cedula = $_POST['cedula'];
            $contrasena = $_POST['contrasena'];

            $dataLogin = json_decode($objeto->login($cedula, $contrasena), true);

            if($dataLogin['statusMessage'] === "success") {
                session_start();
                $_SESSION['cedula'] = $dataLogin["cedula"];
                $_SESSION['nombre'] = $dataLogin["nombre"];
                $_SESSION['token'] = $dataLogin["jwt"];
                
            }else {
                http_response_code(400);
            }

            die( $dataLogin["message"] ?? 'error');

        }
        exit;
    }
    require_once($config->_Dir_View_() . $pagina . $config->_VIEW_());
} else {
    echo "pagina en construccion";
}
