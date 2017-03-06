<?php
    class ControllerNotas extends Controller {
        
        //crea una nota
        function doCrearNota(){
            $asunto= Request::read('asunto');
            $file= new FileUpload('dato');
            $texto = Request::read('texto');
            $nota=new Nota();
            /*creamos de forma predeterminada una nota vacia y le pedimos que devuelva su id*/
            $id = $this->getModel()->insertNota($nota, $this->getUser()->getIdUsuario());
            /*pedimos la nota que se creo para poder hacer modificaciones esto se hace porque 
            si crearmos una nota por defecto no tiene id nota y ese campo es obligatorio a 
            la hora de insertar documentos*/
            $nota =$this->getModel()->getNota($id);
            $filenombre=$file->getName().''.$this->getUser()->getNombre();
            //guarda los ficheros en una carpeta seleccionada dependiendo de que tipo sean
            if($file->getExtencion()==="mp4" || $file->getExtencion()==="avi"){
                $file->setTarget('usuarios/video/');
                //le dices la politica que quieras que siga en la subida de archivos
                $file->setPolicy(FileUpload::RENOMBRAR);
                //pone el nombre del archiv
                $file->setName($filenombre.'.'.$file->getExtencion());
                if($file->upload()){
                    $nota->setVideo($file->getTarget().''.$file->getName());
                }
            }
            if($file->getExtencion()==="mp3"){
                $file->setTarget('usuarios/sonido/');
                //le dices la politica que quieras que siga en la subida de archivos
                $file->setPolicy(FileUpload::RENOMBRAR);
                //pone el nombre del archivo
                $file->setName($filenombre.'.'.$file->getExtencion());
                if($file->upload()){
                    $nota->setSonido($file->getTarget().''.$file->getName());
                }
            }
            if($file->getExtencion()==="png" || $file->getExtencion()==="gif" || $file->getExtencion()==="jpg"|| $file->getExtencion()==="jpeg"){
                $file->setTarget('usuarios/imagen/');
                $file->setName($filenombre.'.'.$file->getExtencion());
                //le dices la politica que quieras que siga en la subida de archivos
                $file->setPolicy(FileUpload::RENOMBRAR);
                //pone el nombre del archivo
                if($file->upload()){
                    $imagen=new Imagen();
                    $imagen->setIdNota($nota->getIdNota());
                    $imagen->setImagen($file->getTarget().''.$file->getName());
                    $gestor=new GestorImagen();
                    $gestor->add($imagen);
                }
            }
            $nota->setIdUsuario($this->getUser()->getIdUsuario());
            $nota->setAsunto($asunto);
            $nota->setTexto($texto);
            $r=$this->getModel()->editNota($nota, $id);
            /*le decimos que renvie a ver notas  para observar las modificaciones*/
            header('Location: index.php?ruta=nota&accion=viewlistNotas&op=edit&r=' . $r);
            exit();
        }
        //muestra la lista de notas
        function viewlistNotas() {
            $this->getModel()->addFile('archivo-mensaje', '');
            $this->getModel()->addFile('archivo-contenido', $this->getCarpeta().'notas');
            $lista = $this->getModel()->getList($this->getUser()->getIdUsuario());
            //Devuelve la plantilla de notas
            if($this->getUser()->getTipo()==="administrador"){
                $this->getModel()->addData('lista-notas-admin', $lista);
            }else{
                $this->getModel()->addData('lista-notas-user', $lista);
            }
            
        }
        
        //edita las notas de un usuario
        function doeditarNota() {
            $asunto= Request::read('asunto');
            $file= new FileUpload('dato');
            $texto = Request::read('texto');
            $idpk=Request::read('idpk');
            $filenombre=$file->getName().''.$this->getUser()->getNombre();
            /*le pedimos la nota que modificamos par sobreescribir los datos que barian*/
            $nota =$this->getModel()->getNota($idpk);
            //guarda los ficheros en una carpeta seleccionada
            if($file->getExtencion()==="mp4" || $file->getExtencion()==="avi"){
                $file->setTarget('usuarios/video/');
                //le dices la politica que quieras que siga en la subida de archivos
                $file->setPolicy(FileUpload::RENOMBRAR);
                //pone el nombre del archivo
                $file->setName($filenombre.'.'.$file->getExtencion());
                if($file->upload()){
                    $nota->setVideo($file->getTarget().''.$file->getName());
                }
            }
            if($file->getExtencion()==="mp3"){
                $file->setTarget('usuarios/sonido/');
                //le dices la politica que quieras que siga en la subida de archivos
                $file->setPolicy(FileUpload::RENOMBRAR);
                //pone el nombre del archivo
                $file->setName($filenombre.'.'.$file->getExtencion());
                if($file->upload()){
                    $nota->setSonido($file->getTarget().''.$file->getName());
                }
            }
            if($file->getExtencion()==="png" || $file->getExtencion()==="gif" || $file->getExtencion()==="jpg"|| $file->getExtencion()==="jpeg"){
                $file->setTarget('usuarios/imagen/');
                //le dices la politica que quieras que siga en la subida de archivos
                $file->setPolicy(FileUpload::RENOMBRAR);
                //pone el nombre del archivo
                $file->setName($filenombre.'.'.$file->getExtencion());
                if($file->upload()){
                    $imagen=new Imagen();
                    $gestor=new GestorImagen();
                    $imagen=$gestor->get($idpk);
                    if($imagen->getIdImagen()===null){
                        $imagen->setIdNota($idpk);
                        $imagen->setImagen($file->getTarget().''.$file->getName());
                        $gestor->add($imagen);
                    }
                    $imagen->setImagen($file->getTarget().''.$file->getName());
                    $gestor->save($imagen, $imagen->getIdImagen());
                }
            }
            $nota->setIdUsuario($this->getUser()->getIdUsuario());
            $nota->setAsunto($asunto);
            $nota->setTexto($texto);
            $r = $this->getModel()->editNota($nota, $idpk);
            header('Location: index.php?ruta=nota&accion=viewlistNotas&op=edit&r=' . $r);
            exit();
        }
        
        //borra las imagenes de una nota
        function doborrarImagen(){
            $id=Request::read('id');
            $r = $this->getModel()->deleteImagen($id);
            header('Location: index.php?ruta=nota&accion=viewlistNotas&r=' . $r);
            exit();
        }
        //borra notas
        function dodeleteNota() {
            $id = Request::read('idpk');
            $r = $this->getModel()->deleteNota($id);
            header('Location: index.php?ruta=nota&accion=viewlistNotas&r=' . $r);
            exit();
        }
        //borra los audios de una nota
        function doborrarAudio(){
            $id=Request::read('id');
            $nota=$this->getModel()->getNota($id);
            $nota->setSonido('');
            $r = $this->getModel()->editNota($nota, $idpk);
            header('Location: index.php?ruta=nota&accion=viewlistNotas&r=' . $r);
            exit();
        }
        //borra los videos de una nota
        function doborrarVideo(){
            $id=Request::read('id');
            $nota=$this->getModel()->getNota($id);
            $nota->setVideo('');
            $r = $this->getModel()->editNota($nota, $idpk);
            header('Location: index.php?ruta=nota&accion=viewlistNotas&r=' . $r);
            exit();
        }
    }
?>