<?php

class ModelUsuario extends Model {
    /*llama al gestor usuario para realizar la activacion*/
    function activarUsuario($email, $iduser) {
        $gestor = new GestorUsuario();
        return $gestor->activarUsuario($email, $iduser);
    }
    /*llama al gestor usuario para realizar la edicion del perfil*/
     function editaPerfil(Usuario $usuario, $nombre , $password) {
        if($password==null){
            $usuario->setNombre($nombre);
            $usuario->setPassword($password);
            $gestor = new GestorUsuario();
            return $gestor->saveUsuario($usuario, $usuario->getIdUsuario());
        }else{
            $usuario->setNombre($nombre);
            $usuario->setPassword(Util::encriptar($password));
            $gestor = new GestorUsuario();
            return $gestor->saveUsuario($usuario, $usuario->getIdUsuario());
        }
    }
    /*llama al gestor usuario para que te devuelva un usuario a partir del email*/
    function getUsuario($email){
        $gestor = new GestorUsuario();
        return $gestor->get($email);
    }
    /*llama al gestor usuario para que te devuelva un usuario a partir del id*/
    function getUsuario1($id){
        $gestor = new GestorUsuario();
        return $gestor->get1($id);
    }
    /*llama al gestor usuario para insertar un usuario*/
    function insertUsuario(Usuario $usuario){
        date_default_timezone_set('Europe/Madrid');
        $usuario->setFalta(date('Y-m-d'));
        $usuario->setTipo('usuario');
        $usuario->setEstado(0);
        $usuario->setPassword(Util::encriptar($usuario->getPassword()));
        $gestor = new GestorUsuario();
        return $gestor->add($usuario);
    }
    /*llama al gestor usuario para que te devuelva una list usuario*/
    function getList(){
        $gestor = new GestorUsuario();
        return $gestor->getList();
    }
    /*llama al gestor usuario para que te borre un usuario a partir del id*/
    function deleteUsuario($id){
        $gestor=new GestorUsuario();
        return $gestor->delete($id);
    }
    /*llama al gestor usuario para que te modifique un usuario a partir del id*/
    function editUsuario(Usuario $usuario, $id){
        $gestor = new GestorUsuario();
        return $gestor->save($usuario, $id);
    }
}