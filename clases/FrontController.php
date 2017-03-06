<?php

class FrontController {

    private $controlador;
    private $modelo;
    private $vista;

    public function __construct(Router $router, $nombreRuta = null, $accion = null) {
        $nombreRuta = strtolower($nombreRuta);

        $nombreModelo = $ruta->getModel();
        $nombreVista = $ruta->getView();
        $nombreControlador = $ruta->getController();

        $this->modelo = new $nombreModelo();
        $this->vista = new $nombreVista($this->modelo);
        $this->controlador = new $nombreControlador($this->modelo);

        if (method_exists($this->controlador, $accion)) {
            $this->controlador->$accion();
        } else {
            $this->controlador->main();
        }
    }
    /*funcion que ejecuta la vista que pongamos en router*/
    public function output() {
        return $this->vista->render();
    }

}