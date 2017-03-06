<?php

require 'clases/AutoLoad.php';

$ruta = Request::read("ruta");
$accion = Request::read("accion");

$frontController = new FrontController(new Router(), $ruta, $accion);

echo $frontController->output();