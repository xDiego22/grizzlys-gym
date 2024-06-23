<?php

use config\components\configSystem as configSystem;
use model\homeModel;

$config = new configSystem;


if (is_file($config->_Dir_View_().$pagina.$config->_VIEW_())) {
    $obj = new homeModel();

    // Array de los meses en español
    $meses = [
        1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    $numeroMes = date('n');

    $mesActual = $meses[$numeroMes];

    if(isset($_POST['accion'])){			
 
        $accion = $_POST['accion'];

        if($accion == 'busqueda'){
            
           
            exit;
        }		
        if($accion == 'historypays'){
            
            $anio = $_POST['anioActual'];
            $historypays = $obj->getHistoryPay($anio);
            echo $historypays;
           
            exit;
        }		
        

	}
    $years = $obj->getYearsPays();
    $info = $obj->getInfoDashboard();
    require_once($config->_Dir_View_().$pagina.$config->_VIEW_());
} else {
    echo "pagina en construccion";
}