<?php

class Nota {
    
    private $idnota,$idusuario, $asunto, $texto, $video, $sonido, $fmodificacion;
    
    function __construct($idnota=null,$idusuario=null, $asunto = null, $texto = null, $video=null, $sonido = null, $fmodificacion = null) {
       $this->idnota=$idnotas;
       $this->idusuario=$idusuario;
       $this->asunto=$asunto;
       $this->texto=$texto;
       $this->video=$video;
       $this->sonido=$sonido;
       $this->fmodificacion=$fmodificacion;
    }
    
    function getIdNota() {
        return $this->idnota;
    }

    function getIdUsuario() {
        return $this->idusuario;
    }

    function getAsunto() {
        return $this->asunto;
    }

    function getTexto() {
        return $this->texto;
    }

    function getVideo() {
        return $this->video;
    }

    function getSonido() {
        return $this->sonido;
    }

    function getFmodificacion() {
        return $this->fmodificacion;
    }

    function setIdNota($idnotas) {
        $this->idnota = $idnotas;
    }

    function setIdUsuario($idusuario) {
        $this->idusuario = $idusuario;
    }

    function setAsunto($asunto) {
        $this->asunto = $asunto;
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

    function setVideo($video) {
        $this->video = $video;
    }

    function setSonido($sonido) {
        $this->sonido = $sonido;
    }

    function setFmodificacion($fmodificacion) {
        $this->fmodificacion = $fmodificacion;
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
    /*inserta una nota con los valores de array selecionados*/
    function set(array $array, $inicio = 0) {
        $this->idnota=$array[0+$inicio];
        $this->idusuario=$array[1+$inicio];
        $this->asunto=$array[2+$inicio];
        $this->texto=$array[3+$inicio];
        $this->video=$array[4+$inicio];
        $this->sonido=$array[5+$inicio];
        $this->fmodificacion=$array[6+$inicio];
    }
}