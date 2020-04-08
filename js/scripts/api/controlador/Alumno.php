<?php

header('Content-Type: text/html; charset=UTF-8');
require 'dao/AlumnoDao.php';
require_once 'vendor/PHPMailer-master/PHPMailerAutoload.php';

class Alumno {

    /**
     * Login 
     * 
     * @url POST /alumno/login
     */
    public function login() {

        $headers = $this->getRequestHeaders();

        foreach ($headers as $name => $value) {
            switch ($name) {
                case 'Codigo':
                    $codigo = $value;
                    break;
                default:
                    break;
                case 'Usuario':
                    $usuario = $value;
                    break;
                case 'Rol':
                    $rol = $value;
                    break;
                case 'Contrasenia':
                    $contrasenia = $value;
                    break;
            }
        }

        $alumnoDao = new AlumnoDao();
        $resultado = $alumnoDao->login($codigo, $usuario, $rol, $contrasenia);

        if (empty($resultado)) {
            return ['error' => true, 'mensaje' => 'Usuario o contraseña incorrecta'];
        } else {
            return array_merge(['error' => false, 'datos' => $resultado]);
        }
    }

    /**
     * Activar cuenta  - Nuevo usuario
     * 
     * @url POST /alumno/nuevoUsuario
     */
    public function activarCuenta() {

//        $usuario = json_decode(urldecode(filter_input(INPUT_POST, 'json_usuario')), TRUE);
//        $codigo = $usuario['codigo'];
//        $nivel = $usuario['nivel'];
//        $password = $usuario['password'];
//        $rol = $usuario['rol'];
//        $correo = $usuario['correo'];

        $codigo = filter_input(INPUT_POST, 'codigo');
        $nivel = filter_input(INPUT_POST, 'nivel');
        $password = filter_input(INPUT_POST, 'password');
        $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);
        $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);


        $alumnoDao = new AlumnoDao();
        $alumnoDao->comenzarTransaccion();
        try {
            //$idProcedimiento = $procedimientoDao->nuevo($idInstancia, $categoria, $subcategoria, $nombre, $especialista, $precios, $recordatorio, $aplicacion);
            return $alumnoDao->activarCuenta($codigo, $nivel, $password, $rol, $correo);
            $alumnoDao->finalizarTransaccion(TRUE);
            return ['error' => FALSE, 'mensaje' => 'El usuario se guardó correctamente.'];
        } catch (mysqli_sql_exception $exc) {
            $alumnoDao->finalizarTransaccion(FALSE);
            return ['error' => TRUE, 'mensaje' => 'No se pudo completar el registro' . $exc->getMessage()];
        }
    }

    /////**** SECCION CONTRASEÑA ****/////
    
    /**
     * Envío de vínculo para restablecer contraseña
     * 
     * @url POST /alumno/vinculoContrasenia
     */
    public function vinculoContrasenia() {
        $correoElectronico = filter_input(INPUT_POST, 'correo_electronico', FILTER_SANITIZE_EMAIL);
        $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);

        $alumnoDao = new AlumnoDao();
        $alumno = $alumnoDao->obtenerPorCorreoElectronico($codigo, $rol, $correoElectronico);

        if (count($alumno) > 0) {
            $alumnoDao->comenzarTransaccion();

            $id = $alumno['sql_rowid'];
            $cod = hash('sha256', $correoElectronico . '-' . date('Y-m-d H:i:s') . '-' . rand(0, 1000000));
            $fechaExp = date('Y-m-d H:i:s', strtotime('+1 day'));

            //$vinculo = "http://localhost:8383/"."gestionAlumno/restablecer-pass.html?correo_electronico=$correoElectronico&codigo=$cod&rol=$rol&id=$id";
            //$vinculo = "http://www.elcolegioencasa.edu.ar/" . "restablecer-pass.html?correo_electronico=$correoElectronico&codigo=$cod&rol=$rol&id=$id";
            $vinculo = "https://autogestionpadres.elcolegioencasa.edu.ar/" . "restablecer-pass.html?correo_electronico=$correoElectronico&codigo=$cod&rol=$rol&id=$id";



            $res = $this->correoContrasenia($correoElectronico, $vinculo);

            if ($res === 1) {
                $alumnoDao->guardarVinculoContrasenia($id, $cod, $fechaExp);

                $alumnoDao->finalizarTransaccion(TRUE);

                return ['error' => FALSE, 'mensaje' => 'Se envió un correo electrónico con instrucciones para recuperar su contraseña'];
            } else {
                $alumnoDao->finalizarTransaccion(FALSE);
                return ['error' => TRUE, 'mensaje' => 'No se pudo enviar el correo electrónico'];
            }
        } else {
            return ['error' => TRUE, 'mensaje' => 'La dirección no se encuentra registrada'];
        }
    }

    /**
     * Obtener Categorias Procedimientos para select
     * 
     * @url GET /alumno/obtenerDatos
     */
    public function obtenerDatos() {
        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $idNivel = filter_input(INPUT_GET, 'nivel');
        $alumnoDao = new AlumnoDao();
        $datosAlumno = $alumnoDao->obtenerDatos($idCodigo, $idNivel);
        return ['datos' => $datosAlumno];
    }

    /**
     * Obtener primary key de alumno
     * 
     * @url GET /alumno/obtener-id-alumno
     */
    public function obtenerIdAlumno() {
        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $idNivel = filter_input(INPUT_GET, 'nivel');

        $alumnoDao = new AlumnoDao();
        $datosAlumno = $alumnoDao->obtenerIdAlumno($idCodigo, $idNivel);


        return ['datos' => $datosAlumno];
    }

    /**
     * Obteber nombre alumno con dni  para select
     * 
     * @url GET /alumno/obtenerNombreConDni
     */
    public function obtenerNombreConDni() {
        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $idNivel = filter_input(INPUT_GET, 'nivel');
        $alumnoDao = new AlumnoDao();
        $nombreAlumno = $alumnoDao->obtenerNombreConDni($idCodigo, $idNivel);
        return ['datos' => $nombreAlumno];
    }

    /**
     * Obteber nombre alumno con dni  para select
     * 
     * @url GET /alumno/obtenerNombreTutorConDni
     */
    public function obtenerNombreTutorConDni() {

        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $idDni = filter_input(INPUT_GET, 'dni', FILTER_SANITIZE_NUMBER_INT);
        $idRol = filter_input(INPUT_GET, 'rol', FILTER_SANITIZE_NUMBER_INT);
        $idNivel = filter_input(INPUT_GET, 'nivel');

        $alumnoDao = new AlumnoDao();
        $nombreTutor = $alumnoDao->obtenerNombreTutor($idCodigo, $idDni, $idRol, $idNivel);
        return ['datos' => $nombreTutor];
    }

    private function correoContrasenia($correoElectronico, $vinculo) {
        $mensaje = "Estimado/a:
                    Por favor, visite el siguiente enlace para restablecer su contraseña:\n
                    $vinculo \n
                    El enlace caducará después de 24 horas por razones de seguridad.\n
                    Gracias,\n
                    El colegio en Casa.";

        $cuerpo = "Recuperar Contraseña\n";
        //$cuerpo .= "Cod.: ". $codigoMensaje . "\n";
        //$cuerpo .= "Fecha: ". $fechaFormat . "\n";
        //$cuerpo .= "Nombre: " . $nombre . "\n";
        $cuerpo .= "Email: " . $correoElectronico . "\n";
        $cuerpo .= "Mensaje: " . $mensaje . "\n";

        $mailDestino = $emailRecepcion;
        //mando el correo...
        if (mail($correoElectronico, "El colegio en casa - Recuperar contraseña", $cuerpo)) {
            return 1;
            //exit("Mensaje enviado.");
        } else {
            return 0;
            //exit("Error al enviar mensaje");
        }
    }

    private function correoContrasenia1($correoElectronico, $vinculo) {

        $cuerpo = "Estimado/a:<br />
                    Por favor, visite el siguiente enlace para restablecer su contraseña:\n<br />
                    <a href=\"$vinculo\"> Restablecer contraseña $vinculo</a>\n<br />
                    El enlace caducará después de 24 horas por razones de seguridad.\n<br />
                    Gracias,\n<br />
                    El colegio en Casa.";




        $email = new PHPMailer();

        $email->From = 'univac.isi@gmail.com';
        $email->FromName = 'El colegio en casa';
        $email->Subject = 'Recupero de contraseña';
        $email->CharSet = 'UTF-8';
        $email->isSMTP();
        $email->SMTPDebug = 3;
        $email->Host = 'smtp.gmail.com';

        $email->Port = 465;
        $email->SMTPSecure = 'ssl';
        $email->SMTPAuth = TRUE;
        $email->Username = 'univac.isi@gmail.com'; 
        $email->Password = '0235univac';  
        $email->Body = $cuerpo;

        $email->AddAddress($correoElectronico);
        $email->IsHTML(true);


        if ($email->Send()) {
            return 1;
        } else {
            echo $email->ErrorInfo;
            return 0;
        }
    }

    /**
     * Verificar Vínculo contraseña
     * 
     * @url POST /alumno/verificarVinculoContrasenia
     */
    public function verificarVinculoContrasenia() {
        $correoElectronico = filter_input(INPUT_POST, 'correo_electronico', FILTER_SANITIZE_EMAIL);
        $codigo = filter_input(INPUT_POST, 'codigo');
        $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $alumnoDao = new AlumnoDao();

        if ($alumnoDao->verificarVinculoContrasenia($correoElectronico, $codigo, $rol, $id)) {
            return ['error' => FALSE, 'mensaje' => 'El vínculo es válido, puede proceder'];
        } else {
            return ['error' => TRUE, 'mensaje' => 'El vínculo no es válido'];
        }
    }

    /**
     * restablecer contraseña
     * 
     * @url POST /alumno/restablecerContrasenia
     */
    public function restablecerContrasenia() {
        $correoElectronico = filter_input(INPUT_POST, 'correo_electronico', FILTER_SANITIZE_EMAIL);
        $codigo = filter_input(INPUT_POST, 'codigo');
        $contrasenia = filter_input(INPUT_POST, 'contrasenia');
        $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_NUMBER_INT);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $alumnoDao = new AlumnoDao();

        if ($alumnoDao->verificarVinculoContrasenia($correoElectronico, $codigo, $rol, $id)) {
            //if ($alumnoDao->verificarVinculoContrasenia($correoElectronico, $codigo)) {
            try {
                $alumnoDao->comenzarTransaccion();
                $alumnoDao->actualizarContrasenia($contrasenia, $id, $rol);
                $alumnoDao->finalizarTransaccion(TRUE);
                return ['error' => FALSE, 'mensaje' => 'La contraseña se cambió correctamente'];
            } catch (mysqli_sql_exception $exc) {
                return ['error' => TRUE, 'mensaje' => 'No se pudo cambiar la contraseña, error en BD'];
            }
        } else {
            return ['error' => TRUE, 'mensaje' => 'No se pudo cambiar la contraseña, vínculo no válido'];
        }
    }

    private function getRequestHeaders() {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }

            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }

        return $headers;
    }

}
