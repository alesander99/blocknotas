<?php

class View {

    private $modelo;

    function __construct(Model $modelo) {
        $this->modelo = $modelo;
    }

    function getModel() {
        return $this->modelo;
    }
    /*visualiza todos los archivos predefinidos y cuando acaba rvisualiza las partes que contienen datos*/
     function render() {
        $plantilla = './templates/materialize';
        $this->getModel()->addData('plantilla', $plantilla);
        $archivos = $this->getModel()->getFiles();
        foreach($archivos as $placeholder => $archivo) {
            $this->getModel()->addData($placeholder, 
                Util::renderFile($plantilla . '/' . $archivo . '.html', $this->getModel()->getData()));
        }
         //Vista de usuarios
        $this->renderLista();
        $this->renderListaNotasAdmin();
        $this->renderListaNotasUser();
        return Util::renderFile($plantilla . '/index.html', $this->getModel()->getData());
    }
    
    //Devuelve la vista de los usuarios
    private function renderLista(){
        $datos = $this->getModel()->getData();
        //Si tiene la lista de usuarios la muestra
        if(isset($datos['lista-usuarios'])){
            
            $r = '';
            $lista= $datos['lista-usuarios'];
            foreach ($lista as $usuario) {
              $r .= Util::renderFile( 'templates/materialize/iamlogged/admin/lista-usuarios.html', 
                        array('idusuario'=>$usuario->getIdUsuario(),'email'=> $usuario->getEmail(),'nombre'=>$usuario->getNombre(),'falta'=>$usuario->getFalta(),'tipo'=>$usuario->getTipo(),'estado'=>$usuario->getEstado()));
            }
            $this->getModel()->addData('lista', $r);
        }
    }
    //Devuelve la vista de las notas user
    private function renderListaNotasUser(){
        $datos = $this->getModel()->getData();
        //Si tiene la lista de usuarios la muestra
        $gestorI=new GestorImagen();
        if(isset($datos['lista-notas-user'])){
            $r = '';
            $lista= $datos['lista-notas-user'];
            foreach ($lista as $nota) {
                if($nota->getSonido()!=null){
                    $audio='<audio src="'.$nota->getSonido().'" controls autoplay loop>
                    <p>Tu navegador no implementa el elemento audio</p>
                    </audio><a class="borrar" href="?ruta=nota&accion=doborrarAudio&id="'.$nota->getIdNota().'">X</a>';
                }
                    
                if($nota->getVideo()!=null){
                    $video='<video src="'.$nota->getVideo().'" controls>
                    Tu navegador no implementa el elemento <code>video</code>
                    </video><a class="borrar" href="?ruta=nota&accion=doborrarVideo&id='.$nota->getIdNota().'">X</a>';
                }
                
                $imagen=$gestorI->get($nota->getIdNota());
                $textoimg='<img src="'.$imagen->getImagen().'"/>';
                
                $r .= Util::renderFile( 'templates/materialize/iamlogged/user/nota.html', 
                        array('idnota'=>$nota->getIdNota(),'asunto'=>$nota->getAsunto(),'video'=>$video,'audio'=>$audio,'texto'=>$nota->getTexto(),'imagenes'=>$textoimg));
            }
            $this->getModel()->addData('nota', $r);
        }
    }
    //Devuelve la vista de las notas user admin
    private function renderListaNotasAdmin(){
        $datos = $this->getModel()->getData();
        //Si tiene la lista de usuarios la muestra
        $gestorI=new GestorImagen();
        if(isset($datos['lista-notas-admin'])){
            $r = '';
            $lista= $datos['lista-notas-admin'];
            foreach ($lista as $nota) {
                if($nota->getSonido()!=null){
                    $audio='<audio src="'.$nota->getSonido().'" controls autoplay loop>
                    <p>Tu navegador no implementa el elemento audio</p>
                    </audio><a class="borrar" href="?ruta=nota&accion=doborrarAudio&id="'.$nota->getIdNota().'">X</a>';
                }
                    
                if($nota->getVideo()!=null){
                    $video='<video src="'.$nota->getVideo().'" controls>
                    Tu navegador no implementa el elemento <code>video</code>
                    </video><a class="borrar" href="?ruta=nota&accion=doborrarVideo&id='.$nota->getIdNota().'">X</a>';
                }
                $imagen=$gestorI->get($nota->getIdNota());
                $textoimg='<img src="'.$imagen->getImagen().'"/>';
                
                $r .= Util::renderFile( 'templates/materialize/iamlogged/user/nota.html', 
                        array('idnota'=>$nota->getIdNota(),'asunto'=>$nota->getAsunto(),'video'=>$video,'audio'=>$audio,'texto'=>$nota->getTexto(),'imagenes'=>$textoimg));
            }
            
            $this->getModel()->addData('nota', $r);
        }
    }
}
?>