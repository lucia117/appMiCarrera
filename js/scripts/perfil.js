/* global Api */

var Perfil = function () {
    //variables globales

    //metodos privados
    var inicializacionDeComponentes = function () {

        colegio = localStorage.getItem('colegio');
        codigo = localStorage.getItem('alumno_codigo');
        var objeto = {
            colegio: colegio,
            codigo: codigo
        };

        Api.getStudentData(objeto, 'PERFIL', Perfil.mostrarBienvenida);
    };

    var mostrarDatosBienvenida = function (datos) {
        $('#alumnoPerfilNombre').text(datos.nombre);
        $('#colegioPerfil').text(datos.colegio); 
    };
    
    
    //metodos publicos
    return {
        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },

        mostrarBienvenida: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                mostrarDatosBienvenida(respuesta.objeto);
            } else {
                swal(respuesta.mensaje);
            }
        },

        setExample: function (g) {
            mostrarExamples(g);
        }
    };


}();