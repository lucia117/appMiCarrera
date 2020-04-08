/* global Api */
var prematriculacion = function () {
    //variables globales

    //metodos privados
    var inicializacionDeComponentes = function () {
        colegio = localStorage.getItem('colegio');
        codigo = localStorage.getItem('alumno_codigo');
        var objeto = {
            colegio: colegio,
            codigo: codigo
        };
        Api.getStudentData(objeto, 'VER_MATERIAS_INSCRIBIR', prematriculacion.cargarMaterias);
        Api.getStudentData(objeto, 'VER_DATOS_PERSONAS', prematriculacion.cargarModal);
        HoldOnOn();

    };



    var mostrarMaterias = function (datos) {
        var contenedor = $('#bodyPlanEstudio');
        var itemPadre = $('#row_0');
        var cursado = '--';
        if (datos.length > 0) {
            $.each(datos, function (key, d) {

                var item = itemPadre.clone(true, true);
                item.attr('id', 'row' + (key + 1));
                item.removeClass('hide');
                item.find('.pe_inscribir').attr({ 'id': 'materia' + (key + 1), 'materia': d.materia, 'curso': d.codigo });
                item.find('.pe_nivel').text(d.cur);
                item.find('.pe_nombre').text(d.nombre);
                item.find('.condicion').attr('id', 'condicion' + (key + 1));
                contenedor.append(item);
            });
        } else {
            contenedor.html('<p>No hay datos</p>');
        }
    };

    var cargarVistaModal = function (datos) {
        $('#direccion').val(datos.direccion);
        $('#codigoPostal').val(datos.cod_ciu);
        $('#telefono').val(datos.te);

    }


    $('.btnInscribir').click(function () {
        $('.btnInscribir').prop("disabled", true);
        var materiasInscribir = [];
        var cantMaterias = $('.pe_inscribir').length;

        colegio = localStorage.getItem('colegio');
        codigo = localStorage.getItem('alumno_codigo');
        clectivo = localStorage.getItem('clectivo');
        direccion = $('#direccion').val();
        codigoPostal = $('#codigoPostal').val();
        telefono = $('#telefono').val();


        for (let i = 0; i < cantMaterias; i++) {
            if ($('#materia' + i).prop('checked')) {
                var materiaInscribir = new Object();
                materiaInscribir.curso = $('#materia' + i).attr("curso");
                materiaInscribir.materia = $('#materia' + i).attr("materia");
                if ($('#condicion' + i).val() === 'Regular') {
                    materiaInscribir.condicion = 'REG.';
                } else {
                    materiaInscribir.condicion = 'LIB.';
                }
                //alert(materiaInscribir.materia + ' ' + materiaInscribir.curso + ' ' + materiaInscribir.condicion);
                materiasInscribir.push(materiaInscribir);
            }

        }

        if (materiasInscribir.length === 0) {
            $('.btnInscribir').prop("disabled", false);
            swal('Atencion!', 'debe seleccionar materias para generar la preincripcion', 'warning');
        } else {
            if (direccion === null || direccion === undefined || direccion === '' || codigoPostal === null || codigoPostal === undefined || codigoPostal === '' || telefono === null || telefono === undefined || telefono === '') {
                swal('Atención', 'Verifica que esten todos los dato cargados', 'warning')
            } else {

                var objeto = {
                    colegio: colegio,
                    codigo: codigo,
                    clectivo: clectivo,
                    direccion : direccion,
                    codigoPostal : codigoPostal,
                    telefono : telefono,
                    materias: JSON.stringify(materiasInscribir)
                };
                Api.setStudentData(objeto, 'PREINSCRIBIR_MATERIAS', prematriculacion.prematriculacionOk);
                // Api.getStudentData(objeto, 'PREMATRICULACIONPRUEBA', prematriculacion.prematriculacionOk);
                HoldOnOn();
            }




        }


    });


    //metodos publicos
    return {
        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },
        cargarMaterias: function (respuesta) {
            HoldOnOff();

            if (respuesta.estado) {
                mostrarMaterias(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }
        },


        cargarModal: function (respuesta) {

            if (respuesta.estado) {
                cargarVistaModal(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }
        },
        prematriculacionOk: function (respuesta) {
            HoldOnOff();
            $('.btnInscribir').prop("disabled", false);
            $('.pe_inscribir').prop('checked', false);
            if (respuesta.estado) {
                
                
                swal("Registro con exito!","","success");
                $('#modalRegisterForm').modal('hide')


                /* swal({
                     title: "Registro con exito",
                     text: "Deseas descargar la constancia?",
                     icon: "success",
                     buttons: true,
                     dangerMode: false,
                 })
                     .then((descargar) => {
                         if (descargar) {
                             swal("En breve se descargará la constancia", {
                                 icon: "success",
                             });
                             //Api.getFile(datosConstancia, 'CONSTANCIA_EXAMEN');
 
                         } else {
 
                         }
                     });*/
            } else {
                swal("Atención!","warning");
            }
        },

    };


}();