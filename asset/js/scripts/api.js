/* global moment, URL */

var Api = function () {
//variables globales

   var urlBase = 'https://www.univac.com.ar/apiMiCarrera'; //servidor
   // var urlBase = "http://localhost:8000";       //Local
   //var urlBase = "http://localhost/ProyectoMiCarrera/apiMiCarrera"; //wamp
    //    var token = "";
    //metodos privados

    var login = function (alumno, contrasenia, callback) {
        var respuesta;
        var url = urlBase + '/alumno/login';
        $.ajax({
            url: url,
            headers: {
                "codigo": alumno,
                "contrasenia": contrasenia
            },

            dataType: 'json',
            async: true,
            crossDomain: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            method: 'POST',

            success: function (data) {

                if (!data.error) {
                    console.log(data);
                    console.log(data.datos.codigo);
                    respuesta = new Response(!data.error, "Login");

                    localStorage.setItem('alumno_codigo', data.datos.codigo);
                    localStorage.setItem('colegio', data.datos.colegio);
                    callback(respuesta);
                } else {
                    respuesta = new Response(!data.error, data.mensaje);
                    callback(respuesta);
                }

            },
            error: function (xhr, status) {

                console.log(status);
                respuesta = new Response(false, "Ocurrio un problema en el server.");
                callback(respuesta);
            }//,

        });
    };


    var recuperarContrasenia = function (email, callback) {
        var respuesta;
        var url = urlBase + '/usuario/vinculoContrasenia';
        var formData = new FormData();
        formData.append('correo_electronico', email);
        $.ajax({
            url: url,
            "async": true,
            "crossDomain": true,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (!data.error) {
                    //se envio el mensaje
                    respuesta = new Response(true, data.mensaje);
                    callback(respuesta);
                } else {
                    //no se envio
                    respuesta = new Response(false, data.mensaje);
                    callback(respuesta);
                }
            },
            error: function (xhr, status) {
                console.log(status);
                respuesta = new Response(false, "Ocurrio un problema en el server.");
                callback(respuesta);
            }//,

        });
    };

    var resetContrasenia = function (email, contrasenia, codigo, callback) {
        var respuesta;
        var url = urlBase + '/usuario/restablecerContrasenia';
        var formData = new FormData();
        formData.append('correo_electronico', email);
        formData.append('codigo', codigo);
        formData.append('contrasenia', contrasenia);
        $.ajax({
            url: url,
            "async": true,
            "crossDomain": true,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (!data.error) {
                    //se envio el mensaje
                    respuesta = new Response(true, data.mensaje);
                    callback(respuesta);
                } else {
                    //no se envio
                    respuesta = new Response(false, data.mensaje);
                    callback(respuesta);
                }

            },
            error: function (xhr, status) {
                console.log(status);
                respuesta = new Response(false, "Ocurrio un problema en el server.");
                callback(respuesta);
            }//,

        });
    };


    //METODOS GENERICOS PARA MINIMIZAR LA CANTIDAD DE METODOS
    //GUARDAR IMAGEN 

    var obtenerDatos = function (path_ws, callback) {
        var respuesta;
        var url = urlBase + path_ws;
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            crossDomain: true,
            contentType: 'application/x-www-form-urlencoded',
//            headers: {
//                Authorization: getToken()
//            },
            success: function (data) {
                respuesta = new Response(true, "");
                respuesta.objeto = data.datos;
                callback(respuesta);
            },
            error: function (data) {
                console.log(data);
                respuesta = new Response(false, 'Ocurrio un problema en el servidor.');
                callback(respuesta);
            }
        });
    };

    var guardarDatos = function (objetoPorGuardar, fd, ws, callback) {
        var respuestaJson;
        var url = urlBase + ws;

        for (var pair of fd.entries()) {
            if (typeof pair[1] === 'object') {
                console.log(pair[0] + ', ' + JSON.stringify(pair[1]));
            } else {
                console.log(pair[0] + ', ' + pair[1]);
            }
        }

        $.ajax({
            url: url,
            "async": true,
            "crossDomain": true,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            data: fd,
            dataType: 'json',

            success: function (data) {
                if (!data.error) {
                    //se gaurdo la data
                    respuestaJson = new Response(true, data.mensaje);
                    if (data.hasOwnProperty('datos')) {
                        respuestaJson.objeto = data.datos;
                    } else {
                        if (data.hasOwnProperty('id')) {
                            respuestaJson.objeto = data.id;
                        } else {
                            respuestaJson.objeto = objetoPorGuardar;
                        }
                    }
                    callback(respuestaJson);
                } else {
                    //no se guardo
                    respuestaJson = new Response(true, data.mensaje);
                    callback(respuestaJson);
                }

            },
            error: function (xhr, status) {
                console.log(status);
                respuestaJson = new Response(false, "Ocurrio un problema en el server.");
                callback(respuestaJson);
            }

        });
    };

    var guardarDatosJSON = function (objetoPorGuardar, ws, callback) {
        var respuestaJson;
        var url = urlBase + ws;
        console.log(objetoPorGuardar);
        objetoPorGuardar = JSON.stringify(objetoPorGuardar);

        $.ajax({
            url: url,
            async: true,
            crossDomain: true,
            cache: false,
            contentType: 'application/x-www-form-urlencoded',
            processData: false,
            method: 'POST',
            type: 'POST',
            data: objetoPorGuardar,

//            headers: {
//                Authorization: getToken()
//            },
            success: function (data) {
                console.log(data);
                data = JSON.parse(data);
                if (!data.error) {
                    //se gaurdo la data
                    respuestaJson = new Response(true, data.mensaje);
                    if (data.hasOwnProperty('datos')) {
                        respuestaJson.objeto = data.datos;
                    }
                    if (data.hasOwnProperty('id')) {
                        respuestaJson.objeto = data.id;
                    }
                    callback(respuestaJson);
                } else {
                    //no se guardo
                    respuestaJson = new Response(false, data.mensaje);
                    callback(respuestaJson);
                }

            },
            error: function (xhr, status) {

                console.log(status);
                respuestaJson = new Response(false, "Ocurrio un problema en el server.");
                callback(respuestaJson);
            }

        });
    };


    //FIN METODOS GENERICOS

    //metodos publicos
    return {
        init: function() {

        },
        ingresar: function (alumno, contrasenia, callback) {
            login(alumno, contrasenia, callback);
        },

        getUrlApi: function (tipo) {
            if (tipo === 'TESTING') {
                return urlTesting;
            }
            if (tipo === 'PRODUCCION') {
                return urlProduccion;
            }
            if (tipo === 'ELABORACION') {
                return urlElaboracion;
            }
            if (tipo === 'ACTUAL') {
                return urlBase;
            }
        },

        getStudentData: function (objeto, tipo, callback) {
            var ws;
            switch (tipo) {
                case 'VERIFICAR_CUENTA':
                    ws = '/alumno/verificar-cuenta' +
                            '?codigo=' + objeto.codigo +
                            '&colegio=' + objeto.colegio;
                    break;
                case 'VERIFICAR_CUENTA':
                    ws = '/alumno/verificar-cuenta' +
                            '?codigo=' + objeto.codigo +
                            '&colegio=' + objeto.colegio;
                    break;

                case 'PERFIL':
                    ws = '/alumno/perfil-alumno' +
                            '?codigo=' + objeto.codigo +
                            '&colegio=' + objeto.colegio;
                    break;

                case 'ESTADO_CUENTA':
                    ws = '/estadoCuenta/obtenerListadoEstadoCuenta' +
                            '?codigo=' + objeto.codigo;
                    break;

                case 'VER_MATERIAS_RENDIR':
                    ws = '/materia/obtener-materias-para-rendir' +
                            '?colegio=' + objeto.colegio +
                            '&codigo=' + objeto.codigo;
                    break;

                case 'VERIFICAR_INSCRIPCION_EXAMEN':
                    ws = '/examen/validacion-fecha-examen' +
                            '?colegio=' + objeto.colegio +
                            '&codigo=' + objeto.codigo +
                            '&curso=' + objeto.curso +
                            '&materia=' + objeto.materia; 
                    break;


                case 'VER_CURSADO':
                    ws = '/materia/mostrar-materias-cursando' +
                            '?colegio=' + objeto.colegio +
                            '&codigo=' + objeto.codigo;
                    break;

                case 'PLAN_ESTUDIO':
                    ws = '/materia/mostrar-plan-estudio' +
                            '?codigo=' + objeto.codigo +
                            '&colegio=' + objeto.colegio;
                    break;

                case 'ANALITICO':
                    ws = '/materia/mostrar-analitico' +
                            '?colegio=' + objeto.colegio +
                            '&codigo=' + objeto.codigo;
                    break;

                case 'VER_CONFIGURACION':
                    ws = '/alumno/alumno-configuracion' +
                            '?codigo=' + objeto.codigo;
                    break;

                case 'MENSAJES':
                    ws = '/mensaje/obtener-mensaje' +
                            '?codigo=' + objeto.codigo +
                            '&colegio=' + objeto.colegio;
                    break;

                default:
                    alert('No existe ws');
                    break;
            }
            obtenerDatos(ws, callback);
        },

        getSchoolData: function (objeto, tipo, callback) {
            var ws;
            switch (tipo) {
                case 'VER_NOMBRE_ESTABLECIMIENTO':
                    ws = '/colegio/obtenerColegiosParaSelect';
                    break;

                default:
                    alert('No existe ws');
                    break;
            }
            obtenerDatos(ws, callback);
        },

        setStudentData: function (objeto, tipo, callback) {
            var ws;
            var formData = new FormData();
            var isFormData = false;
            switch (tipo) {
                case 'CREAR_USUARIO':
                    ws = '/alumno/nuevoUsuario';
                    formData.append('codigo', objeto.codigo);
                    formData.append('password', objeto.password);
                    formData.append('correo', objeto.correo);
                    isFormData = true;
                    break;

                case 'VINCULO_CONTRASENIA':
                    ws = '/alumno/vinculoContrasenia';
                    formData.append('correo_electronico', objeto.correo_electronico);
                    formData.append('codigo', objeto.codigo);
                    isFormData = true;
                    break;

                case 'RESTABLECER_CONTRASENIA':
                    ws = '/alumno/restablecerContrasenia';
                    isFormData = true;
                    formData.append('correo_electronico', objeto.correo_electronico);
                    formData.append('codigo', objeto.codigo);
                    formData.append('contrasenia', objeto.contrasenia);
                    formData.append('id', objeto.id);
                    formData.append('rol', objeto.rol);
                    break;

                case 'INSCRIBIR_EXAMEN':
                    ws = '/examen/inscripcion-examen';
                    formData.append('colegio', objeto.colegio);
                    formData.append('codigo', objeto.codigo);
                    formData.append('curso', objeto.curso);
                    formData.append('materia', objeto.materia);
                    formData.append('condicion', objeto.condicion);
                    formData.append('turno_examen', objeto.turno_examen);
                    formData.append('docente', objeto.docente);
                    isFormData = true;
                    break;

                default:
                    MensajeAdvertencia('FALTA WS');
                    return;
            }
            if (isFormData) {
                guardarDatos(objeto, formData, ws, callback);
            } else {
                guardarDatosJSON(objeto, ws, callback);
            }

        }
    };
}();
