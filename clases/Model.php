<?php

class Model {

    private $data = array();
    private $files = array();

    function __construct() {
    }
    //añade un elemento al array data
    function addData($name, $data) {
        $this->data[$name] = $data;
    }
    //añade un elemento al array files
    function addFile($name, $data) {
        $this->files[$name] = $data;
    }
    //devuelve el array de data
    function getData() {
        return $this->data;
    }
    //devuelve el array de file
    function getFiles() {
        return $this->files;
    }

}
