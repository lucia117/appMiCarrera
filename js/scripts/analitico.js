/* global Api */
var analitico = function () {
    //variables globales
    var codigo;
    var colegio;

    //metodos privados
    var inicializacionDeComponentes = function () {
        colegio = localStorage.getItem('colegio');
        codigo = localStorage.getItem('alumno_codigo');

        var objeto = {
            colegio: colegio,
            codigo: codigo
        };

        Api.getStudentData(objeto, 'ANALITICO', analitico.cargarAnalitico);
        HoldOnOn();
        //hodooor()
    };


    var mostrarAnalitico = function (datos) {
        var contenedor = $('#body');
        var itemPadre = $('#row_0');
        if (datos.length > 0) {
            $.each(datos, function (key, d) {
                var item = itemPadre.clone(true, true);
                item.attr('id', 'row' + (key + 1));
                item.removeClass('hide');
                item.find('.a_nivel').text(d.nivel);
                item.find('.a_fecha').text(d.fecha);
                item.find('.a_nombre').text(d.nombre);
                item.find('.a_condicion').text(d.condicion);
                item.find('.a_nota').text(d.numeros);
                contenedor.append(item);
            });
        } else {
            contenedor.html('<p>No hay datos</p>');
        }
    };


    //metodos publicos
    return {
        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },

        cargarMateriasParaRendir: function (respuesta) {
            if (respuesta.estado) {
                mostrarMateriasParaRendir(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }
        },
        
        cargarAnalitico: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                mostrarAnalitico(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }
        }
        

        
        
    };


}();