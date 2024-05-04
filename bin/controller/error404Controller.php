<?php

use config\components\configSystem as configSystem;

$config = new configSystem;


if (is_file($config->_Dir_View_() . 'error404' . $config->_VIEW_())) {


    require_once($config->_Dir_View_() . 'error404' . $config->_VIEW_());
} else {
    echo "pagina en construccion";
}
