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
        var cursado = '--'; 
        if (datos.length > 0) {
            $.each(datos, function (key, d) {

                switch (d.tipoCursado) {
                    case 'A':
                        cursado = 'Anual';
                        break;
                    case 'C':
                        cursado = 'Cuatrimestral';
                        break;
                    case 'T':
                        cursado = 'Taller';
                        break;
                    default:
                        cursado = '--';
                        break;
                }


                var item = itemPadre.clone(true, true);
                item.attr('id', 'row' + (key + 1));
                item.removeClass('hide');
                item.find('.pe_nivel').text(d.c);
                item.find('.pe_nombre').text(d.nombre);
                item.find('.pe_carga').text(d.carga);
                item.find('.pe_cursado').text(cursado);
                contenedor.append(item);
            });
        } else {
            contenedor.html('<p>No hay datos</p>');
        }
    };

    $('.btnDescargarPlanEstudio').click(function(){
        var nombre = localStorage.getItem('nombre') ;  
        var carrera = localStorage.getItem('nombreCarrera') ;  
        var dni = localStorage.getItem('dni') ;  
        
        
        var objeto = {
            colegio: colegio, 
            codigo: codigo, 
            nombre: nombre.trim(), 
            carrera: carrera.trim(), 
            dni: dni, 
        }
        Api.getPathFile(objeto, "PLAN_ESTUDIO_PDF");
        
    }); 




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
                swal(respuesta.mensaje);
            }
        }
    };


}();