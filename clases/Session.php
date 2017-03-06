<?php

class Session {

    private static $instancia = null;

    private function __construct() {
    }
    /*Cierra la session*/
    function close() {
        $this->delete("_usuario");
    }
    /*elimina el nombre de la secion*/
    function delete($nombre) {
        if (isset($_SESSION[$nombre])) {
            unset($_SESSION[$nombre]);
        }
    }
    /*Destruye la session*/
    function destroy() {
        session_destroy();
    }
    /*devuelve el nombre de la session*/
    function get($nombre) {
        if (isset($_SESSION[$nombre])) {
            return $_SESSION[$nombre];
        }
        return null;
    }
    /*devuelve la instancia de la session*/
    static function getInstance($nombre = null) {
        if (self::$instancia === null) {
            if ($nombre !== null) {
                session_name($nombre);
            }
            session_start();
            self::$instancia = new Session();
        }
        return self::$instancia;
    }
    /*devuelve el user enlasado a la session*/
    function getUser() {
        return $this->get("_usuario");
    }
    /*comprueba si estas logueado si no lo estas te hecha*/
    function isLogged() {
        return $this->getUser() !== null;
    }
    /*redireciona a la ruta predefinida*/
    function sendRedirect($destino = "index.php") {
        header("Location: $destino");
        exit();
    }
    /*inserta en session otro nombre*/
    function set($nombre, $valor) {
        $_SESSION[$nombre] = $valor;
    }
    /*sustituye el user de session por uno que le pases*/
    function setUser($usuario) {
        $this->set("_usuario", $usuario);
    }

}