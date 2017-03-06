<?php
class Controller {
    /*carga los datos y archivos de la aplicacion*/
    private $carpeta, $modelo, $sesion, $user = null;
    function __construct(Model $modelo) {
        $this->modelo = $modelo;
        $this->sesion = Session::getInstance('appNotas');
        $this->modelo->addData('correo','');
        $this->modelo->addData('nombre','');
        $this->modelo->addData('titulo','Notas');
        $this->carpeta = 'iamnotlogged/';
        $this->modelo->addData('fondo','container');
        if($this->sesion->isLogged()) {
            $this->user = $this->sesion->getUser();
            $this->modelo->addData('correo', $this->user->getEmail());
            $this->modelo->addData('nombre', $this->user->getNombre());
            $this->modelo->addData('fondo','container1');
            $this->carpeta = 'iamlogged/user/';
            if($this->user->getTipo()==='administrador'){
                $this->carpeta = 'iamlogged/admin/';
            }
        }
        $this->modelo->addFile('archivo-acceso', $this->carpeta . 'acceso');
        $this->modelo->addFile('archivo-contenido', '');
        $this->modelo->addFile('archivo-mensaje', $this->carpeta.'mensaje-login');
        $this->modelo->addFile('archivo-modal', $this->carpeta . 'modal');
        $this->modelo->addFile('archivo-error','');
        $this->modelo->addFile('archivo-titulo', '');
    }
    /*te debuelve la carpeta donde se encuentra*/
    function getCarpeta(){
        return $this->carpeta;
    }
    /*te debuelve el model que esta utilizando*/
    function getModel() {
        return $this->modelo;
    }
    /*te debuelve la session activa que esta utilizando*/
    function getSession() {
        return $this->sesion;
    }
    /*te debuelve el usuario de la session activa que esta utilizando*/
    function getUser() {
        return $this->user;
    }

    /* acciones */
    
    /*dejecuta la carga predeterminada de los archivos*/
    function main() {
        $login = Request::read('op');
        $r = Request::read('r');
        if($login === 'login') {
            $this->modelo->addFile('archivo-mensaje', $this->carpeta . 'mensaje-login');
        }
        $this->modelo->addData('contenido', 'Página principal');
    }
    
}