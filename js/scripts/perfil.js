/* global Api */

var Perfil = function () {
    //variables globales

    //metodos privados
    var inicializacionDeComponentes = function () {

        var colegio = localStorage.getItem('colegio');
        var codigo = localStorage.getItem('alumno_codigo');
        
        var objeto = {
            colegio: colegio,
            codigo: codigo
        };

        //Api.getStudentData(objeto, 'PERFIL', Perfil.mostrarBienvenida);
        mostrarDatosBienvenida();
        cargarCarreras();
    };

    var mostrarDatosBienvenida = function (datos) {
        var nombreAlumnoPerfil = localStorage.getItem('nombre'); 
        var nombreColegioPerfil = localStorage.getItem('nombreColegio');
        var nombreCarreraPerfil =localStorage.getItem('nombreCarrera');
        
        $('#alumnoPerfilNombre').text(nombreAlumnoPerfil);
        $('#alumnoPerfilCarrera').text(nombreCarreraPerfil.trim());
        $('#alumnoPerfilColegio').text(nombreColegioPerfil.trim());
        $('#colegioPerfil').text('datos.colegio'); 
    };

    var cargarCarreras = function(){
        var carreras =  localStorage.getItem('carreras');
        carreras = JSON.parse(carreras);
        
        if(carreras.length > 0 ){
            if (carreras.length>1) {
                $('#selectCarreras').removeClass('hide');
                $('#selectCarreras').append($("<option>Seleccione Carrera</option>"));
                
                $.each(carreras, function (index, carrera) {
                    $('#selectCarreras').append($("<option></option>")
                            .attr("codigo", carrera.codigo)
                            .attr("colegio", carrera.colegio)
                            .attr("clectivo", carrera.clectivo)
                            .attr("nombreCarrera", carrera.nombreCarrera)
                            .attr("nombreColegio", carrera.nombreColegio)
                            .text(carrera.nombreCarrera));
                });
            }

        }else{
            alert('no tiene habilitada ninguna carrera, cominiquese con la institucion '); 
        }
    }; 

    $('#selectCarreras').change(function(){
       //console.log($(this).children("option:selected").attr('nombreCarrera') +'' +$(this).children("option:selected").attr('nombreColegio'));
      
       var alumno_codigo = $(this).children("option:selected").attr('codigo');
       var colegio = $(this).children("option:selected").attr('colegio');
       var nombreColegio = $(this).children("option:selected").attr('nombreColegio');
       var nombreCarrera = $(this).children("option:selected").attr('nombreCarrera');
       var clectivo = $(this).children("option:selected").attr('clectivo');

       localStorage.setItem('alumno_codigo', alumno_codigo);
       localStorage.setItem('colegio', colegio);
       localStorage.setItem('nombreColegio', nombreColegio);
       localStorage.setItem('nombreCarrera', nombreCarrera);
       localStorage.setItem('clectivo', clectivo);

       window.location.reload(); 
    });

    
    
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