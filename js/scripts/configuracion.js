/* global Api */

var configuracion = function () {
//variables globales
    //var codigo;
    //metodos privados
    var inicializacionDeComponentes = function () {
        codigo = localStorage.getItem('alumno_codigo');

        var objeto = {
            codigo: codigo
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
        var codigo = localStorage.getItem('alumno_codigo');
        //validarEmail(dato);

        if (validarEmail(correo) == true) {
            var objeto = {
                codigo: codigo,
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
        var codigo = localStorage.getItem('alumno_codigo');

        if (contrasenia1 === contrasenia2) {
            if (contrasenia1.length >= 6) {
                var objeto = {
                    contraseniaNueva: contrasenia1,
                    contrasenia: contrasenia,
                    codigo: codigo
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

        $('.alumnoNombre').text(datos.nombre);
        $('.alumnoDni').text(datos.codigo);
        $('.alumnoDireccion').text(datos.direccion);
        $('.alumnoTelefono').text(datos.te);
        $('.alumnoCorreo').text(datos.email);
        $('.alumnoContrasenia').text(datos.password);
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
                swal("Registro con exito!");
                $('.inputCorreo').val("");
                location.reload();

            } else {
                swal("Atención!");
            }
        },
        telefonoOk: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                swal("Registro con exito!");
                $('.inputTelefono').val("");
                location.reload();
            } else {
                swal("Atención!");
            }
        },
        contraseniaOk: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                swal(respuesta.mensaje);

                swal(hola,{
                    closeOnClickOutside: false,
                });

                $('.contrasenia').val("");
                $('.contrasenia1').val("");
                $('.contrasenia2').val("");
               // location.reload();
            } else {
                swal("Atención!",{
                    icon: "error",
                });
            }
        }


    };
}();