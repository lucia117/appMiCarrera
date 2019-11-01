/* global Api */


var cursado = function () {
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

        Api.getStudentData(objeto, 'VER_CURSADO', cursado.cargarMaterias);
        Api.getStudentData(objeto, 'PERFIL', cursado.cargarPerfil);
        HoldOnOn();
    };


    $('#cerrarSesion').click(function (e) {
        e.preventDefault();
        localStorage.clear();
        location.href = "index.html";
    });

    var mostrarPerfil = function (datos) {
        $('#alumnoNombre').text(datos.nombre);
        $('#alumnoDni').text(codigo);
        $('#alumnoColegio').text(datos.colegio);
    };


    var mostrarNotasCursado = function (datos) {
        var contenedor = $('#bodyCursado');
        var itemPadre = $('#row_0');

        if (datos.length > 0) {
            $.each(datos, function (key, d) {

                //var notas = [d.p1, d.p2, d.p3, d.p4, d.p5];

                notas = new Array(5);
                notas[0] = d.p1;
                notas[1] = d.p2;
                notas[2] = d.p3;
                notas[3] = d.p4;
                notas[4] = d.p5;


                var htmlNotas = '';
                notas.forEach(function (elemento, indice, array) {

                    if (elemento === 99) {
                        notas[indice] = 'Ausente';
                        htmlNotas += ' <strong> nota </strong> ' + (indice + 1) + ': Ausente <br>';
                    }
                    if (elemento === 98) {
                        notas[indice] = 'Aprobado';
                        htmlNotas += ' <strong> nota </strong> ' + (indice + 1) + ': Aprobado <br>';
                    }
                    if (elemento === 97) {
                        notas[indice] = 'No Aprobado';
                        htmlNotas += ' <strong> nota </strong> ' + (indice + 1) + ': No Aprobado <br>';
                    }
                    if (elemento === 0) {
                        notas[indice] = ' <strong> nota </strong> ' + (indice + 1);
                        //htmlNotas += ' nota ' + (indice + 1) + ': No Aprobado <br>'
                    }

                    if (elemento > 0 && elemento < 97) {
                        // notas[indice] = ' nota ' + (indice + 1) ;
                        htmlNotas += '<strong> nota </strong> ' + (indice + 1) + ': ' + notas[indice] + ' <br>'
                    }


                });

                var item = itemPadre.clone(true, true);
                item.attr('id', 'row' + (key + 1));
                item.removeClass('hide');
                item.find('.materiaMje').html('<h5>' + d.nombre + '</h5>');
                item.find('.notasMje').html(htmlNotas);
                item.find('.inasistenciasMje').html('Inasistencias:' + d.faltas);
                contenedor.append(item);

            });
        } else {
            contenedor.html('<tr><td>No cursa ninguna materia</td></tr>');
        }

    };

    //metodos publicos
    return {

        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },
        cargarMaterias: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                mostrarNotasCursado(respuesta.objeto);
            } else {
                alert(respuesta.mensaje);
            }
        },
        cargarPerfil: function (respuesta) {
            if (respuesta.estado) {
                mostrarPerfil(respuesta.objeto);
            } else {
                alert(respuesta.mensaje);
            }
        },

    };


}();
