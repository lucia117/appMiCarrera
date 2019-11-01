/* global Api */

var configuracion = function () {
//variables globales
    var codigo;
    //metodos privados
    var inicializacionDeComponentes = function () {
        codigo = localStorage.getItem('alumno_codigo');
        var objeto = {
            codigo: codigo
        };
        Api.getStudentData(objeto, 'VER_CONFIGURACION', configuracion.cargarConfiguracion);
        //hodooor();


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
        var dato = $('.inputCorreo').val();
        //validarEmail(dato);
        if (validarEmail(dato) == true) {

            Api.setStudentData(objeto, 'MODIFICAR_CORREO', configuracion.cargarConfiguracion);
            //llamar a api 
        }
    });

    $('.btnTelefono').click(function () {
        var dato = $('.inputTelefono').val();
        if (validarTelefono(dato) == true) {
            alert('telefono fine');
            //llamarapi 
        } else {
            alert('bad bad')
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
                alert(respuesta.mensaje);
            }

        },
    
        correoOk: function (respuesta) {
            //chauHodooor();
            if (respuesta.estado) {
                  alert("Registro con exito!");
                $('#modalCorreo').modal('.disabled'); 
                
            } else {
                alert("Atención!");
            }
        }

    };
}();