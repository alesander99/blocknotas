/*global $*/
/*validacion de formulario*/
$(document).ready(function(){
    /*validacion de formulario de inciar sesion de un usuario ya creado*/
   $('#formulariologin').on('submit',function(event){
       var email=$(this).find('#email');
       if(!validarEmail(email.val())){
            email.next().text('*');
            event.preventDefault();
       }else{
           email.next().text('');
       }
       var password=$(this).find('#contraseña');
       if(!validarPasword(password.val())){
           password.next().text('*');
           event.preventDefault();
       }else{
           password.next().text('');
       }
   });
/*validacion de formulario para registrarte como nuevo usuario*/   
   $('#formularioregister').on('submit',function(event){
       var nombre=$(this).find('#nombre');
       var email=$(this).find('#email');
       if(!validarNombre(nombre)){
           nombre.next().text('*');
           event.preventDefault();
       }else{
           nombre.next().text('');
       } 
       
       if(!validarEmail(email.val())){
            email.next().text('*');
            event.preventDefault();
       }else{
           email.next().text('');
       }
       
   });
   /*validacion para modificar los datos de el perfil del usuario*/
   $('#formularioperfil').on('submit',function(event){
       var nombre=$(this).find('#nombre');
       var email=$(this).find('#email');
       if(!validarNombre(nombre)){
           nombre.next().text('*');
           event.preventDefault();
       }else{
           nombre.next().text('');
       } 
       
       if(!validarEmail(email.val())){
            email.next().text('*');
            event.preventDefault();
       }else{
           email.next().text('');
       }
   });
/*editor  de la lista usuario de el administrador*/
   $('#formularioajax').on('submit',function(event){
       var nombre=$(this).find('#nombre');
       var email=$(this).find('#email');
       var tipo=$(this).find('#tipo');
       var password=$(this).find('#contraseña');
       var password2=$(this).find('#contraseña2');
       if(!validarNombre(nombre)){
           nombre.next().text('*');
           event.preventDefault();
       }else{
           nombre.next().text('');
       } 
       
       if(!validarEmail(email.val())){
            email.next().text('*');
            event.preventDefault();
       }else{
           email.next().text('');
       }
       
       if(!validarTipo(tipo.val())){
          tipo.next().text('*');
           event.preventDefault();
       }else{
           tipo.next().text('');
       }
       
   });
   /*funciones de los formularios*/
   function validarEmail(textinput){
    var result=true;
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(textinput=="" || textinput==null){
        result=false;
    }
    if (!expr.test(textinput) ){
        result=false;
    }
    return result;
   }
   
   function validarNombre(textinput){
       var result= true;
       if(textinput==null || textinput==""){
           result=false;
       }
       return result;
   }
   
   function validarPasword(textinput){
       var result= true;
       if(textinput==null || textinput==""){
           result=false;
       }
       return result;
   }
   
   function validarPasword2(textinput,textinput1){
       var result= true;
       if((textinput==null || textinput=="")&&(textinput1==null||textinput1=="")){
           result=false;
           return result;
       }
       if(textinput!=textinput1){
           result=false;
       }
       return result;
   }
   
   function validarTipo(textinput){
       var result= true;
       if(textinput==null || textinput==""){
           result=false;
       }
       switch(textinput){
        case 'administrador':
            return result;
        case 'usuario':
            return result;
        default:
            result=false;
            return result;
       }
   }
});