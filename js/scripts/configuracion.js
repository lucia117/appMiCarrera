/* global Api */

var configuracion = function () {
//variables globales
    var codigo;
    var dni; 
    //metodos privados
    var inicializacionDeComponentes = function () {
        codigo = localStorage.getItem('alumno_codigo');
        dni = localStorage.getItem('dni'); 
        var objeto = {
            codigo: codigo,
            dni: dni
        };

        Api.getStudentData(objeto, 'VER_CONFIGURACION', configuracion.cargarConfiguracion);

        $('.btn-mod-tel').click(function () {
            $('#modalTelefono').modal();
        });

        $('.btn-mod-correo').click(function () {
            $('#modalCorreo').modal();
        });

        $('.btn-mod-contrasenia').click(function () {
            $('#modalContrasenia').modal();
        });



    };

    $('.btnCorreo').click(function () {
        var correo = $('.inputCorreo').val();


        if (validarEmail(correo) == true) {
            var objeto = {
                codigo: dni,
                correo_electronico: correo
            };
            Api.setStudentData(objeto, 'MODIFICAR_CORREO', configuracion.correoOk);
            HoldOnOn();
        }
    });

    $('.btnTelefono').click(function () {
        var telefono = $('.inputTelefono').val();
        var colegio = localStorage.getItem('colegio');
        var codigo = localStorage.getItem('alumno_codigo');
        if (validarTelefono(telefono) == true) {
              
            var objeto = {
                codigo: codigo,
                telefono: telefono,
                colegio: colegio
            };

            Api.setStudentData(objeto, 'MODIFICAR_TELEFONO', configuracion.telefonoOk);
            HoldOnOn();
        } else {
            swal("Opss!", "debe ingresar 10 caracteres ej: 3576458875 ", {
                    icon: "error",
                });
        }

    });

    $('.btnContrasenia').click(function () {
        var contrasenia1 = $('.contrasenia1').val();
        var contrasenia2 = $('.contrasenia2').val();
        var contrasenia = $('.contrasenia').val();

        if (contrasenia1 === contrasenia2) {
            if (contrasenia1.length >= 6) {
                var objeto = {
                    contraseniaNueva: contrasenia1,
                    contrasenia: contrasenia,
                    codigo: dni
                };
                Api.setStudentData(objeto, 'MODIFICAR_CONTRASENIA', configuracion.contraseniaOk);
                HoldOnOn();
            } else {
                swal("la cotraseña debe tener al menos 6 caracteres");
            }
        } else {
            swal('Las contraseñas no coinciden');
        }

    });

    var mostrarConfiguracion = function (datos) {

        $('.alumnoNombre').text(datos.datosPersonales.nombre);
        $('.alumnoDni').text(dni);
        $('.alumnoDireccion').text(datos.datosPersonales.direccion);
        $('.alumnoTelefono').text(datos.datosPersonales.te);
        $('.alumnoCorreo').text(datos.datosCuenta.email);
        $('.alumnoContrasenia').text(datos.datosCuenta.password);
    };

    //metodos publicos
    return {
        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },
        cargarConfiguracion: function (respuesta) {
            //chauHodooor(); 
            if (respuesta.estado) {
                mostrarConfiguracion(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }

        },
        correoOk: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                $('.inputCorreo').val("");
                swal('',"Registro con exito!", 'success')
                .then((value) => {
                    location.reload();
                });
                
                

            } else {
                swal("Atención!");
            }
        },
        telefonoOk: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                $('.inputTelefono').val("");
                swal('',"Registro con exito!",'success')
                .then((value) => {
                    location.reload();
                });
            } else {
                swal("Atención!");
            }
        },
        contraseniaOk: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {

                $('.contrasenia').val("");
                $('.contrasenia1').val("");
                $('.contrasenia2').val("");
                swal('',respuesta.mensaje, 'success')
                .then((value) => {
                    location.reload();
                });
               // location.reload();
               

                $('.contrasenia').val("");
                $('.contrasenia1').val("");
                $('.contrasenia2').val("");
               
            } else {
                swal("Atención!",{
                    icon: "error",
                });
            }
        }


    };
}();