<?php

class DataBase {
    private $conexion, $consulta;
    private $depurando = true;
    
    function __construct($clase = 'Constantes') {
        try {
            $this->conexion = new PDO(
                                'mysql:host=' . $clase::SERVER . ';' . 'dbname=' . $clase::DATABASE,
                                $clase::DBUSER,
                                $clase::DBPASSWORD,
                                array(
                                    PDO::ATTR_PERSISTENT => true,
                                    PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
                                );
            return true;
        } catch (PDOException $e) {
            if ($this->depurando) {
                echo '<hr>Error de conexión: ';
                var_dump($e);
                echo '<hr>';
            }
            return false;
        }
    }
    /*cierra la conexion de la base de datos*/
    function close() {
        $this->conexion = null;
    }
    /*devuelve el numero de fila incerta*/
    function getCount() {
        return $this->consulta->rowCount();
    }
    /*devuelve los errores producidos por msql*/
    function getErrorConnection() {
        return $this->conexion->errorInfo();
    }
    /*devuelve los errores producidos por el scrith de msql*/
    function getErrorQuery() {
        return $this->consulta->errorInfo();
    }
    /*devuelve el ultimo id de la ultima fila insertada*/
    function getId() {
        return $this->conexion->lastInsertId();
    }
     /*devuelve la ultima fila insertada*/
    function getRow() {
        $r = $this->consulta->fetch();
        if ($r === null) {
            $this->consulta->closeCursor();
        }
        return $r;
    }
    /*formatra un array de parametros y lo convierte en una condicion de msql*/
    private static function createCondition(array $parametros) {
        $condicion = '';
        foreach ($parametros as $nombreParametro => $valorParametro) {
            $condicion .= "$nombreParametro = :$nombreParametro and ";
        }
        $condicion = trim(substr($condicion, 0, -4));
        return $condicion;
    }
    /*formatea los errores producidos por la base de datos*/
    private function seeError($sql, array $parametros, $r) {
        $error = $this->getErrorQuery();
        if ($error[0] !== '00000' || $r <= 0) {
            echo '<hr>Parámetros:<br><br> ';
            var_dump($parametros);
            echo '<br><br>sql: ';
            echo 'sql: '.$sql;
            echo '<br><br>error: ';
            var_dump($this->getErrorQuery());
            echo '<br><hr>';
        }
    }
    /*ejecuta un sql formateado por nosotros*/
    function send($sql, array $parametros = array()) {
        if ($parametros === null) {
            $parametros = array();
        }
        $this->consulta = $this->conexion->prepare($sql);
        foreach ($parametros as $nombreParametro => $valorParametro) {
            $this->consulta->bindValue($nombreParametro, $valorParametro);
        }
        $r = $this->consulta->execute();
        if ($this->depurando === true) {
            $this->seeError($sql, $parametros, $r);
        }
        return $r;
    }
    /*realiza un select count de datos enviados*/
    function count($tabla, $condicion = null, array $parametros = array()) {
        $sql = "select count(*) from $tabla";
        if ($condicion !== null && trim($condicion) !== "") {
            $sql .= " where $condicion";
        }
        $r = $this->send($sql, $parametros);
        if ($r) {
            $row = $this->getRow();
            return $row[0];
        }
        return -1;
    }
    /*realiza un select count de datos enviados con un formateo mas general*/
    function countParameters($tabla, array $parametros = array()) {
        $condicion = self::createCondition($parametros);
        return $this->count($tabla, $condicion, $parametros);
    }
    /*devuelve si existe una tabla*/
    function exist($tabla, $condicion = null, array $parametros = array()) {
        return $this->count($tabla, $condicion, $parametros) > 0;
    }
    /*devuleve si existe los parametros pasados*/
    function existParameters($tabla, array $parametros = array()) {
        return $this->countParameters($tabla, $parametros) > 0;
    }
    /*borra un elemento de la tabla que pasemos bajo las condicones pasadas*/
    function delete($tabla, $condicion, array $parametros = array()) {
        if ($condicion === null || trim($condicion) === '') {
            $condicion = '1<>1';
        }
        $sql = "delete from $tabla where $condicion;";
        $r = $this->send($sql, $parametros);
        if (!$r) {
            var_dump($r);
            exit();
            return -1;
        }
        return $this->getCount();
    }
    /*borra un elemento de la tabla que pasemos bajo las condicones pasadas con un formateo mas general*/
    function deleteParameters($tabla, array $parametros = array()) {
        $condicion = self::createCondition($parametros);
        return $this->delete($tabla, $condicion, $parametros);
    }
    /*inserta un elemento de la tabla que pasemos bajo las condiciones pasadas*/
    function insert($tabla, $asignacion, array $parametros = array(), $autoincrement = true, $ignore = false) {
        return $this->insertProjection($tabla, $asignacion, '', $parametros, $autoincrement, $ignore);
    }
    /*inserta un elemento de la tabla que pasemos bajo las condiciones pasadas con un formateo mas general*/
    function insertParameters($tabla, array $parametros = array(), $autoincrement = true, $ignore = false) {
        $proyeccion = '';
        $asignacion = '';
        foreach ($parametros as $nombreParametro => $valorParametro) {
            $proyeccion .= "$nombreParametro,";
            $asignacion .= ":$nombreParametro,";
        }
        $proyeccion = trim(substr($proyeccion, 0, -1));
        $asignacion = trim(substr($asignacion, 0, -1));
        
        return $this->insertProjection($tabla, $asignacion, $proyeccion, $parametros, $autoincrement, $ignore);
    }
    /*inserta un elemento de la tabla que pasemos bajo las condiciones pasadas con un formateo mas especifico que actua solo en casos nesesarios*/
    function insertProjection($tabla, $asignacion, $proyeccion = '', array $parametros = array(), $autoincrement = true, $ignore = false) {
        if ($ignore === true) {
            $ignore = 'ignore';
        } else {
            $ignore = '';
        }
        if ($proyeccion === null || trim($proyeccion) === '') {
            $sql = "insert $ignore into $tabla values ($asignacion)";
        } else {
            $sql = "insert $ignore into $tabla ($proyeccion) values ($asignacion)";
        }
        $r = $this->send($sql, $parametros);
        if (!$r) {
            var_dump($r);
            exit();
            return -1;
        } else if ($autoincrement === true) {
            
            return $this->getId();
        } else {
            return $this->getId();
        }
    }
    /*actualiza un elemento o varios de una tabla baajo las condiciones que pongamos*/
    function update($tabla, $asignacion, $condicion = '', array $parametros = array()) {
        $sql = "update $tabla set $asignacion";
        if ($condicion !== null && trim($condicion) !== '') {
            $sql .= " where $condicion";
        }
        $r = $this->send($sql, $parametros);
        if (!$r) {
            var_dump($r);
            exit();
            return -1;
        }
        return $this->getCount();
    }
    /*actualiza un elemento o varios de una tabla baajo las condiciones que pongamos con un formato mas generalizado*/
    function updateParameters($tabla, array $parametrosSet = array(), array $parametrosWhere = array()) {
        $asignacion = '';
        $condicion = '';
        $parametros = array();
        foreach ($parametrosSet as $nombreParametros => $valorParametro) {
            $asignacion .= $nombreParametros . ' = :' . $nombreParametros . ',';
            $parametros[$nombreParametros] = $valorParametro;
        }
        $asignacion = trim(substr($asignacion, 0, -1));
        foreach ($parametrosWhere as $nombreParametros => $valorParametro) {
            $condicion .= $nombreParametros . ' = :_' . $nombreParametros . ' and ';
            $parametros['_' . $nombreParametros] = $valorParametro;
        }
        $condicion = trim(substr($condicion, 0, -4));
        return $this->update($tabla, $asignacion, $condicion, $parametros);
    }
    
    function getCursor($tabla, $proyeccion = '*', $condicion = '', array $parametros = array(), $orderby = '1', $limit = '') {
        if ($condicion === null || trim($condicion) === '') {
            $sql = "select $proyeccion from $tabla order by $orderby $limit";
        } else {
            $sql = "select $proyeccion from $tabla where $condicion order by $orderby $limit";
        }
        return $this->send($sql, $parametros);
    }
    
    function getCursorParameters($tabla, $proyeccion = '*', array $parametros = array(), $orderby = '1', $limit = '') {
        $condicion = self::createCondition($parametros);
        return $this->getCursor($tabla, $proyeccion, $condicion, $parametros, $orderby, $limit);
    }
    
    function getData($tabla, $condicion = null, array $parametros = array(), $orderby = '1', $limit = '') {
        return $this->getDataProjection($tabla, '*', $condicion, $parametros, $orderby, $limit);
    }
    
    function getDataParameters($tabla, array $parametros = array(), $orderby = '1', $limit = '') {
        $condicion = self::createCondition($parametros);
        return $this->getDataProjection($tabla, '*', $condicion, $parametros, $orderby, $limit);
    }
    
    function getDataProjection($tabla, $proyeccion = '*', $condicion = null, array $parametros = array(), $orderby = '1', $limit = '') {
        $list = array();
        $r = $this->getCursor($tabla, $proyeccion, $condicion, $parametros, $orderby, $limit);
        if ($r) {
            while ($fila = $this->getRow()) {
                $list[] = $fila;
            }
        }
        return $list;
    }
}