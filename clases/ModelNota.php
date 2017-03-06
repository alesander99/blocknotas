<?php
class ModelNota extends Model {
    function getList($id){
        $gestor = new GestorNota();
        return $gestor->getList($id);
    }
    /*llama al gestor nota para insertar una nota e inserta dentro de la nota la fecha de la creacion o modificacion 
    y el id usuario*/
    function insertNota(Nota $nota, $id){
        date_default_timezone_set('Europe/Madrid');
        $nota->setFmodificacion(date('Y-m-d'));
        $nota->setIdUsuario($id);
        $gestor = new GestorNota();
        return $gestor->add($nota);
    }
    /*llama al gestor nota para modificar una nota e inserta dentro de la nota la fecha de la creacion o modificacion 
    */
    function editNota(Nota $nota, $id){
        date_default_timezone_set('Europe/Madrid');
        $nota->setFmodificacion(date('Y-m-d'));
        $gestor = new GestorNota();
        return $gestor->save($nota, $id);
    }
    /*llama al gestor nota para borrar una nota */
    function deleteNota($id){
        
        $gestor=new GestorNota();
        return $gestor->delete($id);
    }
    /*llama al gestor nota para borrar una imagen */
    function deleteImagen($id){
        $gestor=new GestorImagen();
        return $gestor->delete($id);
    }
    /*llama al gestor nota para que te debuelva una imagen */
    function getNota($id){
        $gestor = new GestorNota();
        return $gestor->get($id);
    }
}
?>