/* global Api */


var mensaje = function () {
    //variables globales
    var codigo;
    var Colegio;



    //metodos privados
    var inicializacionDeComponentes = function () {
        codigo = localStorage.getItem('alumno_codigo');
        Colegio = localStorage.getItem('colegio');

        var objeto = {
            codigo: codigo,
            colegio: Colegio,
        };
        Api.getStudentData(objeto, 'MENSAJES', mensaje.cargarMensajes);
        HoldOnOn();

    };

    $('.de').click(function () {
        var titulo =  this.getAttribute('titulo');
        var fecha =   this.getAttribute('fecha_hora'); 
        var escrito_por = this.getAttribute('escrito_por'); 
        var mensaje = this.getAttribute('mensaje');
        
        $('.mensajeTitulo').text(titulo.replace(/\b\w/g, l => l.toUpperCase()));
        $('.mensajeNombre').text(escrito_por); 
        $('.mensajeFecha').text(fecha); 
        $('.mensajeCuerpo').text(mensaje);

    });
    
     $('.titulo').click(function () {
        var titulo =  this.getAttribute('titulo');
        var fecha =   this.getAttribute('fecha_hora'); 
        var escrito_por = this.getAttribute('escrito_por'); 
        var mensaje = this.getAttribute('mensaje');
        
        $('.mensajeTitulo').text(titulo.replace(/\b\w/g, l => l.toUpperCase()));
        $('.mensajeNombre').text(escrito_por); 
        $('.mensajeFecha').text(fecha); 
        $('.mensajeCuerpo').text(mensaje);

    });




    var mostrarMensajes = function (datos) {

        var contenedor = $('#bodyMensajes');
        var itemPadre = $('#row_0');
  
        if (datos.length > 0) {
            $.each(datos, function (key, d) {
                var item = itemPadre.clone(true, true);
                item.attr('id', 'row' + (key + 1));
                item.removeClass('hide');
                item.find('.titulo').text(d.titulo);
                item.find('.fecha').text(d.fecha_hora);
                item.find('.de').text(d.escrito_por);
                item.find('.mensaje').text(d.texto);

                if(d.nuevo === 1){
                    item.find('.nuevo').removeClass('hide'); 
                }

                
                item.find('.de').attr('titulo', d.titulo);
                item.find('.de').attr('fecha_hora', d.fecha_hora);
                item.find('.de').attr('escrito_por', d.escrito_por);
                item.find('.de').attr('mensaje', d.texto);
                
                item.find('.titulo').attr('titulo', d.titulo);
                item.find('.titulo').attr('fecha_hora', d.fecha_hora);
                item.find('.titulo').attr('escrito_por', d.escrito_por);
                item.find('.titulo').attr('mensaje', d.texto);

                contenedor.append(item);

            });
        } else {
            contenedor.html('<tr><td>No hay Mensajes</td></tr>');
        }

    };
    //metodos publicos
    return {
        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },
        cargarMensajes: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                mostrarMensajes(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }
        },
        setExample: function (g) {
            mostrarExamples(g);
        }
    };


}();






