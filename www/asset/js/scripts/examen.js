 /* global Api */
var examen = function () {
//variables globales
    var codigo;
    var colegio;
    var datosInscripcion = {
        colegio: 0,
        codigo: 0,
        curso: '',
        materia: '',
        condicion: '',
        turno_examen: 0,
        fecha: '',
        docente: 0
    };
    //metodos privados
    var inicializacionDeComponentes = function () {
        colegio = localStorage.getItem('colegio');
        codigo = localStorage.getItem('alumno_codigo');
        var objeto = {
            colegio: colegio,
            codigo: codigo
        };
        Api.getStudentData(objeto, 'VER_MATERIAS_RENDIR', examen.cargarMateriasParaRendir);
        HoldOnOn();

        $('.btnInscribir').click(function () {
            var nombre = $(this).attr('nombre');
            var materia = $(this).attr('materia');
            var curso = $(this).attr('curso');
            var condicion = $(this).attr('condicion');

            $('#modNombreMateria').text(nombre);
            var objeto = {
                colegio: colegio,
                codigo: codigo,
                curso: curso,
                materia: materia,
                condicion: condicion
            };
            $('.btnInscribirMod').attr('condicion', condicion);
            Api.getStudentData(objeto, 'VERIFICAR_INSCRIPCION_EXAMEN', examen.cargarMateriasParaInscribir);
            HoldOnOn();
            $('#modalInscripcion').modal();
        });

        $('.btnInscribirMod').click(function () {

            var docente = $('#selectDocenteMod').val();
            var condicion = $(this).attr('condicion');

            if (docente !== ' ') {
                var objeto = {
                    colegio: datosInscripcion.colegio,
                    codigo: datosInscripcion.codigo,
                    curso: datosInscripcion.curso,
                    materia: datosInscripcion.materia,
                    condicion: condicion,
                    turno_examen: datosInscripcion.turno_examen,
                    docente: docente
                };

                Api.setStudentData(objeto, 'INSCRIBIR_EXAMEN', examen.examenOk);
                HoldOnOn();

            } else {
                swal('debe seleccionar docente para realizar la inscripcion ');
            }


        });



    };
    var mostrarMateriasParaRendir = function (datos) {

        if (datos["mensajeFecha"] == undefined) {
            if (datos["mensajeValidacion"] == undefined) {
                var contenedor = $('#bodyMaterias');
                var itemPadre = $('#row_0');
                if (datos.length > 0) {
                    $.each(datos, function (key, d) {
                        var item = itemPadre.clone(true, true);
                        item.attr('id', 'row' + (key + 1));
                        item.removeClass('hide');
                        item.find('.nombreMateria').text(d.nombre);
                        item.find('.condicion').text(d.condicion);
                        item.find('.curso').text(d.nivel);
                        item.find('.c_lectivo').text(d.c_lectivo);
                        item.find('.btnInscribir').attr('nombre', d.nombre);
                        item.find('.btnInscribir').attr('curso', d.curso);
                        item.find('.btnInscribir').attr('materia', d.materia);
                        item.find('.btnInscribir').attr('condicion', d.condicion);

                        contenedor.append(item);
                    });
                } else {
                    contenedor.html('<p>No hay fechas de examenes disponibles </p>');
                }
            } else {
                //$('#mensaje').text(datos["mensajeValidacion"]);
                swal(datos["mensajeValidacion"]);

            }

        } else {
            //$('#mensaje').text(datos["mensajeFecha"]);
            swal(datos["mensajeFecha"]);
        }

    };
    var mostrarMateriasParaInscribir = function (datos) {
        $('.materiaInscripcion').addClass('hide');
        $('.mostrarMensaje').addClass('hide');
        $('.btnInscribirMod').attr('disabled', 'disabled');


        if (datos.mensajeValidacion === 'null') {
            $('.materiaInscripcion').removeClass('hide');
            $('.btnInscribirMod').removeAttr('disabled');

            $('#modTurno').text(datos.fechaExamen.nombre);
            $('#selectDocenteMod').children().remove();
            $('#selectDocenteMod').append($("<option>  </option>")
                    .attr("value", ' '));
            $.each(datos.docente, function (index, value) {
                $('#selectDocenteMod').append($("<option></option>")
                        .attr("value", value.codigo)
                        .text(value.nombre));
            });

            datosInscripcion.codigo = localStorage.getItem('alumno_codigo');
            datosInscripcion.colegio = localStorage.getItem('colegio');
            datosInscripcion.curso = datos.curso;
            datosInscripcion.materia = datos.materia;
            datosInscripcion.condicion = $(this).attr('condicion');
            datosInscripcion.turno_examen = datos.fechaExamen.turnoActual;

            $('.btnInscribirMod').attr('curso', datos.curso);
            $('.btnInscribirMod').attr('turnoExamen', datos.turnoExamen);
            $('.btnInscribirMod').attr('materia', datos.materia);
            $('.btnInscribirMod').attr('docente', datos.turnoExamen);
        } else {
            $('.mostrarMensaje').removeClass('hide');
            $('.mod-mje').text(datos.mensajeValidacion);
        }


    };
    //metodos publicos
    return {
        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },
        cargarMateriasParaRendir: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                mostrarMateriasParaRendir(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }
        },
        cargarMateriasParaInscribir: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                mostrarMateriasParaInscribir(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }
        },
        examenOk: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {

                swal("Registro con exito!");
                $('#modalInscripcion').modal('hide');
                //       .then((value) => {
                //         window.location.assign('examenes.html');
                //   });
            } else {
                swal("Atención!");
            }
        }



    };
}();