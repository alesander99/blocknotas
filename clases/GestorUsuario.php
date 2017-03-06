<?php

class GestorUsuario {
    
    const TABLA = 'usuario';
    private $db;

    function __construct() {
        $this->db = new DataBase();
    }
    /*devuelve los campos de usuario sin el id*/
    private static function _getCampos(Usuario $objeto) {
        $campos = $objeto->get();
        unset($campos['idusuario']);
        return $campos;
    }
    /*añade un usuario a la base de datos*/
    function add(Usuario $objeto) {
        return $this->db->insertParameters(self::TABLA, self::_getCampos($objeto), false);
    }
    /*borra unusuario de la base de datos*/
    function delete($id) {
        return $this->db->deleteParameters(self::TABLA, array('idusuario' => $id));
    }
    /*activa un usuario de la base de datos a travez de script modificado por nosotros a traves de la funcion send de la Database*/
    function activarUsuario($email, $iduser) {
        $sql = 'update usuario '.
               'set estado = 1 '.
               'where email = :email ';
        $parametros = array('email' => $email);
        if ($this->db->send($sql, $parametros)){
            return $this->db->getCount();
        }
        return -1;
    }
    /*devuelve un usuario con el email enviado*/
    function get($id) {
        $this->db->getCursorParameters(self::TABLA, '*', array('email' => $id));
        if ($fila = $this->db->getRow()) {
            $objeto = new Usuario();
            $objeto->set($fila);
            return $objeto;
        }
        return new Usuario();
    }
    /*devuelve un usuario con el id enviado*/
    function get1($id) {
        $this->db->getCursorParameters(self::TABLA, '*', array('idusuario' => $id));
        if ($fila = $this->db->getRow()) {
            $objeto = new Usuario();
            $objeto->set($fila);
            return $objeto;
        }
        return new Usuario();
    }
    /*devuelve una list usuario de la base de datos*/
    function getList() {
        $this->db->getCursorParameters(self::TABLA);
        $respuesta = array();
        while ($fila = $this->db->getRow()) {
            $objeto = new Usuario();
            $objeto->set($fila);
            $respuesta[] = $objeto;
        }
        return $respuesta;
    }
    /*modifica todos los datos del usuario pasado con el id correspondiente*/
    function save(Usuario $objeto, $id) {
        $campos = $this->_getCampos($objeto);
        unset($campos['falta']);
        if($objeto->getPassword() == null || $objeto->getPassword() == ''){
            unset($campos['password']);
        }
        return $this->db->updateParameters(self::TABLA, $campos, array('idusuario' => $id));
        //return $this->db->updateParameters(self::TABLA, $this->_getCampos($objeto), array('email' => $correopk));
    }
    /*modifica los datos relevantes del usuario pasado con el id correspondiente*/
    function saveUsuario(Usuario $objeto, $idpk) {
        $campos = $this->_getCampos($objeto);
        unset($campos['tipo']);
        unset($campos['estado']);
        unset($campos['falta']);
        if($objeto->getPassword() === null || $objeto->getPassword() === ''){
            unset($campos['password']);
        }
        return $this->db->updateParameters(self::TABLA, $campos, array('idusuario' => $idpk));
    }

}