<?php

class GestorImagen {
    
    const TABLA = 'imagen';
    private $db;

    function __construct() {
        $this->db = new DataBase();
    }
    /*devuelve los campos de imagen sin el id*/
    private static function _getCampos(Imagen $objeto) {
        $campos = $objeto->get();
        unset($campos['idimagen']);
        return $campos;
    }
    /*añade una imagen a la base de datos*/
    function add(Imagen $objeto) {
        return $this->db->insertParameters(self::TABLA, self::_getCampos($objeto), false);
    }
    /*borra una imagen de la base de datos*/
    function delete($id) {
        return $this->db->deleteParameters(self::TABLA, array('idimagen' => $id));
    }
    /*devuelve una imgen con el id pasado*/
    function get($id) {
        $this->db->getCursorParameters(self::TABLA, '*', array('idnota' => $id));
        if ($fila = $this->db->getRow()) {
            $objeto = new Imagen();
            $objeto->set($fila);
            return $objeto;
        }
        return new Imagen();
    }
    /*devuelve una list de imagen con el idnota pasado*/
    function getList($id) {
        $this->db->getCursorParameters(self::TABLA,'*', array('idnota' => $id));
        $respuesta = array();
        while ($fila = $this->db->getRow()) {
            $objeto = new Imagen();
            $objeto->set($fila);
            $respuesta[] = $objeto;
        }
        return $respuesta;
    }
    /*modifica una imagen con el id pasado*/
    function save(Imagen $objeto, $idpk) {
        $campos = $this->_getCampos($objeto);
        return $this->db->updateParameters(self::TABLA, $campos, array('idimagen' => $idpk));
        //return $this->db->updateParameters(self::TABLA, $this->_getCampos($objeto), array('email' => $correopk));
    }
}