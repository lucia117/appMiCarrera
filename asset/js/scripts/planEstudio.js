/* global Api */
var planEstudio = function () {
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

        Api.getStudentData(objeto, 'PLAN_ESTUDIO', planEstudio.cargarPalnEstudio);
        HoldOnOn();
    };

    var mostrarPlanEstudio = function (datos) {
        var contenedor = $('#bodyPlanEstudio');
        var itemPadre = $('#row_0');
        if (datos.length > 0) {
            $.each(datos, function (key, d) {
                var item = itemPadre.clone(true, true);
                item.attr('id', 'row' + (key + 1));
                item.removeClass('hide');
                item.find('.pe_nivel').text(d.c);
                item.find('.pe_nombre').text(d.nombre);
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
        
        cargarPalnEstudio: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                mostrarPlanEstudio(respuesta.objeto);
            } else {
                alert(respuesta.mensaje);
            }
        }
    };


}();