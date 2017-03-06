/* global $ */
(function(){
    window.addEventListener('load', function () {
        
        
        /*pone en todas las clases borrar 'un confirmar'*/
        var confirmarBorrar = function(evento) {
            var objeto = evento.target;
            var r = confirm('¿Borrar?');
            if (r) {
            } else {
                evento.preventDefault();
            }
        }
        /*añade en todas las clases "editar-usuario", un evento que llama una funcion  de ajax*/
        var editar =  function(evento){
            var obgeto=evento.target;
            var id=obgeto.getAttribute('data-id');
            //jsShowWindowLoad();
            llamadaAjaxUsu(id);
        }
        var a = document.getElementsByClassName('borrar');
        
        for (var i = 0; i < a.length; i++) {
            a[i].addEventListener('click', confirmarBorrar, false);
        }
        var ab = document.getElementsByClassName('editar-usuario');
        for (var i = 0; i < ab.length; i++) {
            ab[i].addEventListener('click', editar);
        }
        
        /*llamada a ajax, a la funcion get del controler usuario que nos devuelve un formato de string  json*/
        function llamadaAjaxUsu(id){
            var peticionAsincrona;
            peticionAsincrona = new XMLHttpRequest();
            peticionAsincrona.open("GET","index.php?ruta=ajax&accion=get&id=" + id, true);//{"nombre" : "pepe", "correo": "lfkjdsfklhj"}formato json
            peticionAsincrona.send();
            peticionAsincrona.onreadystatechange = function() {
                if (peticionAsincrona.readyState == 4 && peticionAsincrona.status == 200){
                    var respuesta = peticionAsincrona.responseText;
                    //jsRemoveWindowLoad();
                    procesarUsu(respuesta);
                }
            }
        }
        /*procesa el string json en un modal oculto*/
        function procesarUsu(stringJson) {
            var objetoJson = JSON.parse(stringJson);
            document.getElementById('idusuario').value=objetoJson.idusuario;
            document.getElementById('email1').value=objetoJson.email;
            document.getElementById('nombre1').value=objetoJson.nombre;
            document.getElementById('falta').value=objetoJson.falta;
            document.getElementById('tipo').value=objetoJson.tipo;
            document.getElementById('estado').value=objetoJson.estado;
        }
    });
})();