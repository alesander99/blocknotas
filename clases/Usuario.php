<?php

class Usuario {
    
    private $idusuario,$email, $password, $nombre, $falta, $tipo, $estado;
    
    function __construct($idusuario=null, $email = null, $password = null, $usuario=null, $falta = null, $tipo = null, $estado = null) {
        $this->idusuario=$idusuario;
        $this->email = $email;
        $this->password = $password;
        $this->nombre=$nombre;
        $this->falta = $falta;
        $this->tipo = $tipo;
        $this->estado = $estado;
    }
    
    function getIdUsuario(){
        return $this->idusuario;    
    }
    
    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }
    
    function getNombre(){
        return $this->nombre;
    }
    
    function getFalta() {
        return $this->falta;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getEstado() {
        return $this->estado;
    }

     function setIdUsuario($idusuario){
        $this->idusuario=$idusuario;    
    }
    
    
    function setEmail($email) {
        $this->email = $email;
    }

    function setPassword($password) {
        $this->password = $password;
    }
    
    function setNombre($nombre){
        $this->nombre=$nombre;
    }
    
    function setFalta($falta) {
        $this->falta = $falta;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }
    
    function __toString() {
        $r = '{';
        foreach($this as $key => $valor) {
            $r .= '"$key" : "$valor",';
        }
        $r.='}';
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
    /*devuelve los datos de usuario en array*/
    function get(){
        $nuevoArray = array();
        foreach($this as $key => $valor) {
            $nuevoArray[$key] = $valor;
        }
        return $nuevoArray;
    }
    /*inserta un usuario con los valores de array selecionados*/
    function set(array $array, $inicio = 0) {
        $this->idusuario=$array[0+$inicio];
        $this->email = $array[1 + $inicio];
        $this->password = $array[2 + $inicio];
        $this->nombre=$array[3 + $inicio];
        $this->falta = $array[4 + $inicio];
        $this->tipo = $array[5 + $inicio];
        $this->estado = $array[6 + $inicio];
    }
    
    function isValid() {
        if($this->email === null || $this->password === null){
            return false;
        }
        return true;
    }

}