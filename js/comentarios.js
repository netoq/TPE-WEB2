document.addEventListener('DOMContentLoaded', () => {
    "use strict"

    const baseURL = 'api/comentarios';
    const contenedor = document.querySelector('#listaComentario');
    let id_producto = contenedor.dataset.producto;
    console.log(id_producto);
    let permiso = contenedor.dataset.permiso;
    const div = document.querySelector("#cargando");

  
    /*********************************************************************/
    /************************* TRAER COMENTARIOS *************************/
    /*********************************************************************/
  


    cargarComentarios(id_producto);   

    function cargarComentarios(id_producto) {       
        div.innerHTML = "Imprimiendo...";
        fetch('api/productos/'+id_producto+'/comentarios', {
            method: "GET",
        })
            .then(function (r) {
                if (!r.ok) {
                    console.log("error");
                }
                return r.json();
            })
            .then(function (json) {
                console.log(json);
                div.innerHTML = "";
                contenedor.innerText = "";
                renderAdmin(json);
            })
    }
    
    /*********************************************************************/
    /*********************** AGREGAR COMENTARIOS *************************/
    /*********************************************************************/

    function agregarFila() {
        let comentario = {
            "titulo": document.querySelector("#tituloComentario").value,
            "texto": document.querySelector("#descriptionComentario").value,
            "puntuacion": document.querySelector("#puntajeComentario").value,//poner paserINT?
            "id_usuario": 2,                                            //INVESTIGAR cuando me logue , el api trae el usuario, Json, Guardo en una variable y lo uso aca
            "id_producto": 3                                            //obtener datos de la barra del navegador
        };

        fetch(baseURL, {
            "method": "POST",
            "headers": { "Content-Type": "application/json" },
            "body": JSON.stringify(comentario)
        }).then(function (r) {
            return r.json()
        }).then(function (json) {
            console.log(json);
            renderAdmin(json)
            vaciarInput();
        }).catch(function (e) {
            console.log(e)
        })
    }

    function vaciarInput() {
        document.querySelector("#tituloComentario").value = "";
        document.querySelector("#descriptionComentario").value = "";
        document.querySelector("#puntajeComentario").value = "";
    }

    let form = document.querySelector('#formComentario');
    if(form){
        form.addEventListener('submit', e => {
        e.preventDefault();//evita el envio del formulario
         agregarFila();        
     })
    }
    
    /*********************************************************************/
    /*********************** BORRAR COMENTARIOS *************************/
    /*********************************************************************/

    function borrarComentarios(id_comentario,id_producto) {
        // div.innerHTML = "Borrando...";
        console.log('hola');
        fetch(baseURL +'/' + id_comentario, {
            "method": "DELETE",
            "headers": { "Content-Type": "application/json" },
        }).then(function(r){
            return r.json()
        }).then(function(json) {
                cargarComentarios(id_producto);         
        }).catch(function(e){
            console.log(e);
        })

    }
    
    /*********************************************************************/
    /*********************** CREACION DE DIV COMENTARIOS *************************/
    /*********************************************************************/
    function renderAdmin(json) {

        for (let i = 0; i < json.length; i++) {
            let id_comentario = json[i].id_comentario;
             let div = document.createElement('div');
             div.classList.add("cajaComentario");
 
             let h3 = document.createElement('h3');
             h3.innerText = json[i].titulo;
             div.appendChild(h3);
             h3.classList.add("tituloComentario");
 
             let p = document.createElement('p');
             p.innerText = json[i].texto + ' puntuacion: '+ json[i].puntuacion ;
             div.appendChild(p);

             let ul = document.createElement('ul');
             let li = document.createElement('li');
             li.appendChild(div);
             ul.appendChild(li);
            
             if(permiso == 1){
                let btn = document.createElement("button");
                btn.innerText = "Eliminar";
                btn.addEventListener("click" , function(){
                     borrarComentarios(id_comentario , id_producto);
            
                    });
                btn.classList.add("btnEliminarComentario");    
                li.appendChild(btn);
                }

            
             ul.classList.add("listaCajasComentario")
             contenedor.appendChild(ul);
        }
    }

  //  setInterval(function () { cargarComentarios(id_producto) }, 30000);
});