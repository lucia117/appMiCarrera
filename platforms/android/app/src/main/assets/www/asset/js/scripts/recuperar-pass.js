/* global Api, swal */

var RecuperarPass = function () {
    //variables globales

    //metodos privados
    var inicializacionDeComponentes = function () {
        //var codigo = localStorage.getItem('alumno_codigo');

        $('#botonRecuperar').click(function (e) {
            e.preventDefault();
            var codigo = $('#codigoRec').val();
            var correo = $('#emailRec').val();
            
            console.log(codigo + ' ' + correo);
            
            if ( correo === '' || codigo === '') {
                swal({
                    icon: "warning",
                    type: 'error',
                    title: 'Falta ingresar algun dato',
                    text: 'Verifique que todos los campos esten cargados',

                });
            } else {
                var alumno = {
                    correo_electronico: correo,
                    codigo: codigo
                };
               
                Api.setStudentData(alumno, 'VINCULO_CONTRASENIA', RecuperarPass.recuperarOk);
                HoldOnOn();
            }
        });

    };

    //metodos publicos
    return {
        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },

        usuarioOk: function (respuesta) {
            if (respuesta.estado) {
                swal("Good job!", respuesta.mensaje, "success");
            } else {
                swal("Atención!", respuesta.mensaje, "warning");
            }
        },

        recuperarOk: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                swal(respuesta.mensaje)
                        .then((value) => 
                                window.location.assign('http://micarrera.elcolegioencasa.edu.ar/')
                                );

            } else {
                swal("Atención!", respuesta.mensaje, "warning");
            }
        },

        setExample: function (g) {
            mostrarExamples(g);
        }
    };


}();


