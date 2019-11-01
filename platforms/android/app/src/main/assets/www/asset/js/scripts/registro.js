/* global Api */

var Registro = function () {
    //variables globales
    var COLEGIO;

    //metodos privados
    var inicializacionDeComponentes = function () {


        var objeto = {

        };
        Api.getSchoolData(objeto, 'VER_NOMBRE_ESTABLECIMIENTO', Registro.obtenerEstablecimientos);
        HoldOnOn();
    };

    var mostrarEstablecimientos = function (datos) {

        $.each(datos, function (index, value) {
            $('#selectEstablecimientos').append($("<option></option>")
                    .attr("value", value.codigo)
                    .text(value.COLEGIO));

        });
    };

    $('#botonBuscarAlumno').click(function (e) {
        e.preventDefault();

        var Establecimiento = $('#selectEstablecimientos').val();
        var Codigo = $('#codigoAlumno').val();

        if (Establecimiento !== "") {
            if (Codigo !== "") {
                COLEGIO = Establecimiento;
                var alumno = {
                    codigo: Codigo,
                    colegio: Establecimiento
                };
                Api.getStudentData(alumno, 'VERIFICAR_CUENTA', Registro.verificarCuenta);
                HoldOnOn();
            } else {
                swal("Debe ingresar un DNI");
            }

        } else {
            //alert("Seleccione Esablecimiento ");
            swal("Seleccione establecimiento");
        }

    });

    var obtenerNombreAlumno = function (datos) {

        if (datos.esAlumno !== null) {
            if (datos.tieneCuenta === null) {
                $('#panel-cargar').removeClass('hide');
                $('#nombreAlumno').val(datos.nombre);

            } else {
                swal("El alumno ya tiene una cuenta creada. Quiere recuperar contraseña? ingrese aqui")
                swal({
                    title: "El alumno ya tiene una cuenta creada",
                    text: "Quiere recuperar contraseña? ",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                        .then(function (isConfirm) {
              if (!isConfirm) {
                  window.location.assign('http://micarrera.elcolegioencasa.edu.ar/');
              } else {
                 window.location.assign('http://micarrera.elcolegioencasa.edu.ar/recuperar-pass.html');
              }
          });
          
//                (willDelete) => {
//                            if (willDelete) {
//                                window.location.assign('recuperar-pass.html');
//                            } else {
//                                window.location.assign('index.html');
//
//                            }
//                        }
//                                );
            }

        } else {
            swal('El alumno no corresponde al establecimiento ');
        }

    };

    $('#botonRegistrar').click(function (e) {
        e.preventDefault();

        var codigo = $('#codigoAlumno').val();
        var correo = $('#correoAlumno').val();
        var password1 = $('#pass1').val();
        var password2 = $('#pass2').val();

        if (codigo !== "" || correo !== "" || password1 !== "" || password2 !== "") {
            if (password1 === password2) {
                var alumno = {
                    codigo: codigo,
                    password: password1,
                    correo: correo,
                    colegio: COLEGIO
                };
                Api.setStudentData(alumno, 'CREAR_USUARIO', Registro.usuarioOk);
                HoldOnOn();
                //enviar correo que se creo cuuenta 
            } else {
                alert('Las contraseñas no coinciden ');
            }
        }else {alert('Rellene todos los datos'); }


        
    });


    $('#selectEstablecimientos').change(function () {
        $('#contenedorDatosAlumno').removeClass('hide');
    });





    //metodos publicos
    return {

        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },

        obtenerEstablecimientos: function (respuesta) {
            HoldOnOff(); 
            if (respuesta.estado) {
                mostrarEstablecimientos(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }

        },

        verificarCuenta: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                obtenerNombreAlumno(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }
        },

        usuarioOk: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                swal("Registro con exito!")
                        .then((value) => window.location.assign('http://micarrera.elcolegioencasa.edu.ar'));


            } else {
                swal("Atención!");
            }
        },

        setExample: function (g) {
            mostrarExamples(g);
        }
    };


}();


