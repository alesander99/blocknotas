<?php
class ControllerUsuario extends Controller {
    //confirma el usuario que se le ha enviado el mensaje y lo activa
    function doactivar() {
        $email = Request::read('email');
        $iduser = Request::read('iduser');
        $r = $this->getModel()->activarUsuario($email, $iduser);
        if($r>0){
            $this->getModel()->addData('contenido', 'activación realizada correctamente, ya se puede autentificar');
        } else {
            $this->getModel()->addFile('archivo-error',$this->getCarpeta(). 'error');
            $this->getModel()->addData('error', 'activación incorrecta, posiblemente ya estuviera activado' );
        }
    }
    //inserta un usuario en la base de datos
    function doinsert() {
        $usuario = new Usuario();
        $usuario->read();
        $clave2 = Request::read('password2');
        $r = 0;
        if($usuario->isValid() && $usuario->getPassword() === $clave2) {
            $enlace = 'https://proyectoblock-alesander.c9users.io/notas/index.php?ruta=usuario&accion=doactivar&email='
                . $usuario->getEmail() . '&iduser='. sha1($usuario->getEmail() . Constantes::SECRET);
            $enviado = Util::enviarCorreo($usuario->getEmail(), 'Correo de activación', $enlace);
            if($enviado) {
                $r = $this->getModel()->insertUsuario($usuario);
                header('Location: index.php?op=insert&r=' . $r);
                exit();
            }
            $this->getModel()->addFile('archivo-error',$this->getCarpeta(). 'error');
            $this->getModel()->addData('error', 'Registro no valido' );
        }
    }
    /*
    *esta loguea a un usuario leyendo los datos de un formullario de entrada y comrueba que l email junto con la contraseña son correctas 
    */
    function dologin() {
        $usuarioWeb = new Usuario();
        $usuarioWeb->read();
        $usuarioBD = $this->getModel()->getUsuario($usuarioWeb->getEmail());
        //aceso del root puesto por defecto
        if($usuarioWeb->getEmail()==='daniyales@daniyales.com' && $usuarioWeb->getPassword()==='123123'){
                $this->getSession()->setUser($usuarioBD);
                header('Location: index.php?op=login&r=1');
                exit();
        }
        //aceso del root puesto por demas entradas
        if($usuarioWeb->getEmail() === $usuarioBD->getEmail()){
            if(Util::verificarClave($usuarioWeb->getPassword(), $usuarioBD->getPassword()) &&
               $usuarioBD->getEstado()=='1') {
                $this->getSession()->setUser($usuarioBD);
                header('Location: index.php?op=login&r=1');
                exit();
            }
        }
        $this->getModel()->addFile('archivo-error','');
        $this->getModel()->addData('error', '' );
        $this->getSession()->destroy();
    }
    /*
    *esta function quita la seccion del usuario llamando a la clase session 
    */
    function dologout() {
        $this->getSession()->destroy();
        header('Location: index.php');
        exit();
    }
    /*esta funcion devuel en formato json los datos de un usuario que le pedimos esta enlazada
    a una funcion ajax en javascrit*/
    function get(){
        $id=Request::read('id');
        $usuario=$this->getModel()->getUsuario1($id);
        $this->getModel()->addData('json', '{"idusuario" : "'.$usuario->getIdUsuario().'", "email" : "'.$usuario->getEmail().'", "nombre" : "'.$usuario->getNombre().'", "falta" : "'.$usuario->getFalta().'", "tipo" : "'.$usuario->getTipo().'", "estado" : "'.$usuario->getEstado().'"}');
    }
    /*permite hacer modificacion menos avanzadas en el perfil de usuario*/
    function doeditperfil(){
        $nombre= Request::read('nombre');
        $email=Request::read('email');
        $password = Request::read('oldpassword');
        $password1 = Request::read('password');
        $password2 = Request::read('password2');
        $usuarioBD = $this->getSession()->getUser();
        if($password==null){
                if($usuarioBD->getEmail()!=$email){
                    $enlace = 'https://proyectoblock-alesander.c9users.io/notas/index.php?ruta=usuario&accion=doactivar&email='
                    . $email . '&iduser='. sha1($email . Constantes::SECRET);
                    $enviado = Util::enviarCorreo($email, 'Correo de activación', $enlace);
                    if($enviado) {
                        $usuarioBD->setEmail($email);
                    }
                }
                if($usuarioBD->getEmail()!=$email){
                    $enlace = 'https://proyectoblock-alesander.c9users.io/notas/index.php?ruta=usuario&accion=doactivar&email='
                    . $email . '&iduser='. sha1($email . Constantes::SECRET);
                    $enviado = Util::enviarCorreo($email, 'Correo de activación', $enlace);
                    if($enviado) {
                        $usuarioBD->setEmail($email);
                    }
                }
                $r=$this->getModel()->editaPerfil($usuarioBD, $nombre , $password1);
                $this->getSession()->setUser($usuarioBD);
                $this->getModel()->addFile('archivo-mensaje', $this->getCarpeta() . 'mensaje-login');
                header('Location: index.php?op=editPerfil&'.$r);
                exit();
        }
        if(Util::verificarClave($password, $usuarioBD->getPassword())){
            if($usuarioBD->getEmail()!=$email){
                $enlace = 'https://proyectoblock-alesander.c9users.io/notas/index.php?ruta=usuario&accion=doactivar&email='
                . $email . '&iduser='. sha1($email . Constantes::SECRET);
                $enviado = Util::enviarCorreo($email, 'Correo de activación', $enlace);
                if($enviado) {
                    $usuarioBD->setEmail($email);
                }
            }
            if($password1==$password2){
                $r=$this->getModel()->editaPerfil($usuarioBD, $nombre , $password1);
                $this->getSession()->setUser($usuarioBD);
                $this->getModel()->addFile('archivo-mensaje', $this->getCarpeta() . 'mensaje-login');
                header('Location: index.php?ruta=usuario&op=editPerfil&'.$r);
                exit();
            }else{
                header('Location: index.php?ruta=usuario&op=editPerfil&'.$r);
                exit();
            }
        }
        $this->getModel()->addFile('archivo-error',$this->getCarpeta(). 'error');
        $this->getModel()->addData('error', 'error de edicion de perfil' );
    }
    /*esta funcion hace que el admin pueda volver a una ruta anterior*/
    function volver(){
        header('Location: index.php?ruta=usuario&op=editPerfil&'.$r);
        exit();
    }
    /*esta funcion devuelve una lista de usuarios y los muestra como una tabla*/
    function viewlist() {
        //Devuelve la lista de usuarios
        $this->getModel()->addFile('archivo-mensaje', '');
        $this->getModel()->addFile('archivo-contenido', 'iamlogged/admin/contenido');
        $lista = $this->getModel()->getList();
        //Devuelve la plantilla de usuarios
        $this->getModel()->addData('lista-usuarios', $lista);
    }
    
    
    /*esta funcion borra un usuario*/
    function dodelete() {
        $id = Request::read('id');
        $r = $this->getModel()->deleteUsuario($id);
        header('Location: index.php?ruta=usuario&accion=viewlist&op=delete&r=' . $r);
        exit();
    }
    /*esta funcion permite una edicon del usuario mas avanzada que el perfil solo puede ser obtimizada por un administrador*/
    function doedit() {
        $usuario = new Usuario();
        $usuario->read();
        $clave2 = Request::read('password2');
        $email2=  Request::read('emailpk');
        if($usuario->isValid() && $usuario->getPassword() === $clave2) {
            if($usuario->getEmail()!=$email2){
                $enlace = 'https://proyectoblock-alesander.c9users.io/notas/index.php?ruta=usuario&accion=doactivar&email='
                . $usuario->getEmail() . '&iduser='. sha1($usuario->getEmail() . Constantes::SECRET);
                $enviado = Util::enviarCorreo($usuario->getEmail(), 'Correo de activación', $enlace);
                if($enviado) {
                    $usuario->setEmail($usuario->getEmail());
                }
            }
            $r = $this->getModel()->editUsuario($usuario, $usuario->getIdUsuario());
            header('Location: index.php?ruta=usuario&accion=viewlist&op=edit&r=' . $r);
            exit();
        }
        $this->getModel()->addFile('archivo-error',$this->getCarpeta(). 'error');
        $this->getModel()->addData('error', 'error en la edicion de usuarios' );
    }
}