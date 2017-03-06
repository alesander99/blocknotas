<?php

class Route {

    private $model;
    private $view;
    private $controller;

    function __construct($modelo, $vista, $controlador) {
        $this->model = $modelo;
        $this->view = $vista;
        $this->controller = $controlador;
    }
    /*devuelve el modelo que necesita la ruta*/
    function getModel() {
        return $this->model;
    }
    /*devuelve el view que necesita la ruta*/
    function getView() {
        return $this->view;
    }
    /*devuelve el controller que necesita la ruta*/
    function getController() {
        return $this->controller;
    }

}