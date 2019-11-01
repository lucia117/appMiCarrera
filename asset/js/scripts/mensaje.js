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
                alert(respuesta.mensaje);
            }
        },

        setExample: function (g) {
            mostrarExamples(g);
        }
    };


}();






