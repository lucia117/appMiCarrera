/* global Api */
var Alumno = function () {
    //variables globales
    $(window).load(function () {
        codigo = localStorage.getItem('alumno_codigo');
        colegio = localStorage.getItem('colegio');
    
        if (codigo !== null && codigo !== '' && colegio !== null && colegio !== '') {
            window.location.assign('cursado.html');
        }
    });
    
    //metodos privados
    var inicializacionDeComponentes = function () {

        $('#botonIngresar').click(function (e) {
            e.preventDefault();
            var alumno = $('#dni').val();
            var contrasenia = $('#pass').val();
         
            if (alumno === '' || contrasenia === '') {

                console.log('AGREGAR ALERTA POR FAVOR');
                swal({
                    icon: "warning",
                    type: 'error',
                    title: 'Falta ingresar algún dato',
                    text: 'Verifique que todos los campos esten cargados',

                });
            } else {
                Api.ingresar(alumno, contrasenia,  Alumno.ingreso);
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

        login: function (respuesta) {

            var done = 0;
            var usuario = document.login.usuario.value;
            var password = document.login.password.value;

            if (usuario == "USUARIO1" && password == "CONTRASEÑA1") {
                window.location = "TU_PAGINA_WEB.HTML";
            }
            if (usuario == "USUARIO2" && password == "CONTRASEÑA2") {
                window.location = "TU_PAGINA_WEB.HTML";
            }
            if (usuario == "" && password == "") {
                window.location = "errorpopup.html";
            }


        },
        ingreso: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                window.location.assign('cursado.html');
            } else {
                swal(respuesta.mensaje);
            }

        },

        setExample: function (g) {
            mostrarExamples(g);
        }
    };


}();

