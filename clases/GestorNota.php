<?php

class GestorNota {
    
    const TABLA = 'nota';
    private $db;

    function __construct() {
        $this->db = new DataBase();
    }
    /*devuelve los campos de nota sin el id*/
    private static function _getCampos(Nota $objeto) {
        $campos = $objeto->get();
        unset($campos['idnota']);
        return $campos;
    }
    /*añade una nota a la base de datos*/
    function add(Nota $objeto) {
        return $this->db->insertParameters(self::TABLA, self::_getCampos($objeto), false);
    }
    /*borra una nota de la base de datos*/
    function delete($id) {
        return $this->db->deleteParameters(self::TABLA, array('idnota' => $id));
    }
    /*devuelve una nota con el id pasado*/
    function get($id) {
        $this->db->getCursorParameters(self::TABLA, '*', array('idnota' => $id));
        if ($fila = $this->db->getRow()) {
            $objeto = new Nota();
            $objeto->set($fila);
            return $objeto;
        }
        return new Nota();
    }
    /*devuelve una list de nota con el idusuario pasado*/
    function getList($idusuario) {
        $this->db->getCursorParameters(self::TABLA, '*', array('idusuario' => $idusuario ));
        $respuesta = array();
        while ($fila = $this->db->getRow()) {
            $objeto = new Nota();
            $objeto->set($fila);
            $respuesta[] = $objeto;
        }
        return $respuesta;
    }
    /*modifica una imagen con el id pasado*/
    function save(Nota $objeto, $idpk) {
        $campos = $this->_getCampos($objeto);
        return $this->db->updateParameters(self::TABLA, $campos, array('idnota' => $idpk));
        //return $this->db->updateParameters(self::TABLA, $this->_getCampos($objeto), array('email' => $correopk));
    }
}