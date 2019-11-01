/* global Api */


var estadoCuenta = function () {
    //variables globales
    var codigo;


    //metodos privados
    var inicializacionDeComponentes = function () {
        codigo = localStorage.getItem('alumno_codigo');
        var objeto = {
            codigo: codigo
        };

        Api.getStudentData(objeto, 'ESTADO_CUENTA', estadoCuenta.cargarEstadoCuenta);
        HoldOnOn();
    };


    $('#cerrarSesion').click(function (e) {
        e.preventDefault();
        localStorage.clear();
        location.href = "acceso-padres.html";
    });
    
    
  
    var mostrarEstadoDeCuenta = function (datos) {
        var contenedor = $('#bodyEstadoCuentas');
        var itemPadre = $('#row_0');
        var total= 0; 
        
        if (datos.length > 0) {
            $.each(datos, function (key, d) {
               var item = itemPadre.clone(true, true);
               item.attr('id', 'row'+(key+1));
               item.removeClass('hide');
               item.find('.cocept').text(d.concepto);
               item.find('.import').text(d.importe);
               item.find('.assign').text(d.asignacion);
               contenedor.append(item);
               total= total+d.importe; 
            });
        } else {
            contenedor.html('<tr><td>No hay deuda pendiente</td></tr>');
        }
        $(".totalEstadoCuenta").text(total); 
    };
     
  


    //metodos publicos
    return {

        //main function to initiate the module
        init: function () {
            inicializacionDeComponentes();
        },
        cargarEstadoCuenta: function (respuesta) {
            HoldOnOff();
            if (respuesta.estado) {
                mostrarEstadoDeCuenta(respuesta.objeto);
            } else {
                alert(respuesta.mensaje);
            }

        },

    };


}();