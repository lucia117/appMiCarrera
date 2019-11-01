function Response(estado, mensaje) {
    this.estado = estado;
    this.mensaje = mensaje;
    this.objeto;
}



function checkPass()
{
    //Store the password field objects into variables ...
    var pass1 = document.getElementById('pass1');
    var pass2 = document.getElementById('pass2');
    //Store the Confimation Message Object ...
    var message = document.getElementById('confirmMessage');
    //Set the colors we will be using ...
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    //Compare the values in the password field 
    //and the confirmation field
    if (pass1.value === pass2.value) {
        //The passwords match. 
        //Set the color to the good color and inform
        //the user that they have entered the correct password 
        pass2.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = "Las contraseñas coinciden!";
        $('#botonRegistrar').prop('disabled', false);
    } else {
        //The passwords do not match.
        //Set the color to the bad color and
        //notify the user.
        pass2.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Las contraseñas no coinciden";
    }
}

function valida(e) {
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla === 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros
    patron = /[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function validarEmail(valor) {
    if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(valor)) {
        //alert("La dirección de email " + valor + " es correcta!.");
        return true; 
    } else {
        alert("La dirección de email es incorrecta!.");
        return false; 
    }
}; 


function validarTelefono(p) {
  var phoneRe = /^[2-9]\d{2}[2-9]\d{2}\d{4}$/;
  var digits = p.replace(/\D/g, "");
  return phoneRe.test(digits);
}; 

function formato(texto) {
    return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
}
;

//$(document).ready(function() {
// // executes when HTML-Document is loaded and DOM is ready
// alert("document is ready");
//});

     
function HoldOnOn() {
    var options = {
     //theme:"sk-cube-grid",
     theme:"sk-bounce",
     message:'',
     backgroundColor:"#000000",
     //textColor:"white"
};

    HoldOn.open(options);
};

function HoldOnOff() {
    HoldOn.close();
};

$('#cerrarSesion').click(function (e) {
        e.preventDefault();
        localStorage.clear();
        location.href = "index.html";
    });

