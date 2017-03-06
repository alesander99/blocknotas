<?php
    class Imagen {
        private $idimagen,$idnota, $imagen;
    
        function __construct($idimagen=null,$idnota=null, $imagen = null) {
           $this->idimagen=$idimagen;
           $this->idnota=$idnota;
           $this->imagen=$imagen;
        }
        
        function getIdImagen() {
            return $this->idimagen;
        }
    
        function getIdNota() {
            return $this->idnota;
        }
    
        function getImagen() {
            return $this->imagen;
        }
        
        function setIdImagen($idimagen) {
             $this->idimagen=$idimagen;
        }
    
        function setIdNota($idnota) {
            $this->idnota=$idnota;
        }
    
        function setImagen($imagen) {
             $this->imagen=$imagen;
        }
        
        function __toString() {
            $r = '';
            foreach($this as $key => $valor) {
                $r .= "$key => $valor - ";
            }
            return $r;
        }
        /*lee los datos pasados*/
        function read(ObjectReader $reader = null){
            if($reader===null){
                $reader = 'Request';
            }
            foreach($this as $key => $valor) {
                $this->$key = $reader::read($key);
            }
        }
        /*devuelve los datos de imagen en array*/
        function get(){
            $nuevoArray = array();
            foreach($this as $key => $valor) {
                $nuevoArray[$key] = $valor;
            }
            return $nuevoArray;
        }
        /*inserta una imagen con los valores de array selecionados*/
        function set(array $array, $inicio = 0) {
            $this->idimagen=$array[0+$inicio];
            $this->idnota=$array[1+$inicio];
            $this->imagen=$array[2+$inicio];
        }
    }
?>