<?php
/*carga todo los archivos de clases*/
class AutoLoad {
    static function load($clase) {
        $archivo = './clases/' . $clase . '.php';
        if(file_exists($archivo)){
            require_once $archivo;
        }
    }
}

spl_autoload_register('AutoLoad::load');