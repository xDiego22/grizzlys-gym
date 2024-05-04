<?php

use config\components\configSystem as configSystem;

$config = new configSystem;


if (is_file($config->_Dir_View_().$pagina.$config->_VIEW_())) {


    if(isset($_POST['accion'])){			
 
        $accion = $_POST['accion'];

        if($accion == 'busqueda'){
            
           
            exit;
        }		
        

	}
    
    require_once($config->_Dir_View_().$pagina.$config->_VIEW_());
} else {
    echo "pagina en construccionmm";
}