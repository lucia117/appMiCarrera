<?php

header('Content-Type: text/html; charset=UTF-8');
require_once 'mysql.php';

class AlumnoDao extends mysql {

    public function login($codigo, $usuario, $rol, $contrasenia) {

//        switch ($rol) {
//            case 1:
//                $query = 'SELECT codigo, doc_pad, SUBSTRING(alumnos.clave,10,1) AS nivel, password, colegio
//                          FROM alumnos WHERE borra=0 and codigo=?  and doc_tut=? and password=?';
//                break;
//            case 2:
//                $query = 'SELECT codigo, doc_pad, SUBSTRING(alumnos.clave,10,1) AS nivel, password, colegio
//                          FROM alumnos WHERE borra=0 and codigo=?  and doc_mad=? and password=?';
//                break;
//            case 3:
//                $query = 'SELECT codigo, doc_pad, SUBSTRING(alumnos.clave,10,1) AS nivel, password, colegio
//                          FROM alumnos WHERE borra=0 and codigo=?  and doc_pad=? and password=?';
//                break;
//            default :
//                return [];
//        }
          switch ($rol) {
            case 1:
                $query = 'SELECT codigo, doc_tut, SUBSTRING(alumnos.clave,10,1) AS nivel, pass_tut as password, colegio
                          FROM alumnos WHERE borra=0 and codigo=?  and doc_tut=? and pass_tut=?';
                break;
            case 2:
                $query = 'SELECT codigo, doc_mad, SUBSTRING(alumnos.clave,10,1) AS nivel, pass_mad as password, colegio
                          FROM alumnos WHERE borra=0 and codigo=?  and doc_mad=? and pass_mad=?';
                break;
            case 3:
                $query = 'SELECT codigo, doc_pad, SUBSTRING(alumnos.clave,10,1) AS nivel, pass_pad as password, colegio
                          FROM alumnos WHERE borra=0 and codigo=?  and doc_pad=? and pass_pad=?';
                break;
            default :
                return [];
        }

        $tipos = 'iis';
        $respuesta = $this->consultaRegistroArrayParam($query, [&$tipos, &$codigo, &$usuario, &$contrasenia]);

        if (empty($respuesta)) {
            return [];
        } else {
            if ((string) $contrasenia == (string) $respuesta['password']) {
                unset($respuesta['password']);
                return $respuesta;
            } else {
                return [];
            }
        }
    }

    public function obtenerIdAlumno($codigo, $nivel) {
        $queryPrimaryKey = 'SELECT sql_rowid FROM alumnos WHERE alumnos.codigo=? and borra=0 and SUBSTRING(alumnos.clave,10,1)=? and SUBSTRING(alumnos.cursado,3,1)=" "';
        $tipos = 'is';
        $respuesta = $this->consultaRegistroArrayParam($queryPrimaryKey, [&$tipos, &$codigo, &$nivel]);

        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }

    public function activarCuenta($codigo, $nivel, $password, $rol, $correo) {

        $queryPrimaryKey = 'SELECT sql_rowid FROM alumnos WHERE alumnos.codigo=? and borra=0 and SUBSTRING(alumnos.clave,10,1)=? and SUBSTRING(alumnos.cursado,3,1)=" "';

        $tipos = 'is';
        $respuesta = $this->consultaRegistroArrayParam($queryPrimaryKey, [&$tipos, &$codigo, &$nivel]);

        if (empty($respuesta)) {
            return [];
        } else {
            switch ($rol) {
                case 1:
                    $query = 'UPDATE alumnos SET alumnos.pass_tut= ?, alumnos.email_tut=? WHERE alumnos.sql_rowid=?';
                    break;
                case 2:
                    $query = 'UPDATE alumnos SET alumnos.pass_mad= ?, alumnos.email_mad=? WHERE alumnos.sql_rowid=?';
                    break;
                case 3:
                    $query = 'UPDATE alumnos SET alumnos.pass_pad= ?, alumnos.email_pad=? WHERE alumnos.sql_rowid=?';
                    break;
                default :
                    return [];
            }

            $tipos = 'ssi';
            $res = $this->realizarOperacionParam($query, [&$tipos, &$password, &$correo, &$respuesta['sql_rowid']], FALSE);
            return $res;
        }
    }

    public function obtenerDatos($codigo, $nivel) {
        $query = 'SELECT
            alumnos.codigo, 
            alumnos.nombre, 
            parametros.colegio, 
            substring(alumnos.clave,1,2) AS grado, 
            substring(alumnos.clave,3,2) AS division, 
            substring(alumnos.clave,5,1) AS turno
            FROM 
            alumnos 
            LEFT JOIN 
            parametros
            ON parametros.CODIGO = alumnos.colegio
            WHERE alumnos.codigo=? and borra=0 and SUBSTRING(alumnos.clave,10,1)=? and SUBSTRING(alumnos.cursado,3,1)=" " ';

        $tipo = 'is';

        $respuesta = $this->consultaRegistroArrayParam($query, [&$tipo, &$codigo, &$nivel]);

        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }

    public function obtenerNombreConDni($codigo, $nivel) {
        $query = 'select nombre, password from alumnos where borra=0 and codigo=? and SUBSTRING(cursado,3,1)=" " and SUBSTRING(clave,10,1)=?';
        $tipo = 'is';

        $respuesta = $this->consultaRegistroArrayParam($query, [&$tipo, &$codigo, &$nivel]);

        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }

    public function obtenerNombreTutor($codigo, $dni, $rol, $nivel) {

        switch ($rol) {
            case '1':
                $query = 'SELECT nom_tut  AS nombre, email_tut AS correo, pass_tut AS password FROM alumnos where codigo=? and doc_tut=? and SUBSTRING(clave,10,1)=?';

                break;
            case '2':
                $query = 'SELECT nom_mad AS nombre, email_mad AS correo, pass_mad AS password   FROM alumnos where codigo=? and doc_mad=? and SUBSTRING(clave,10,1)=?';

                break;
            case '3':
                $query = 'SELECT nom_pad AS nombre, email_pad AS correo, pass_pad AS password  FROM alumnos where codigo=? and doc_pad=? and SUBSTRING(clave,10,1)=?';

                break;
            default :
                return [];
        }
        $tipo = 'iis';
        $respuesta = $this->consultaRegistroArrayParam($query, [&$tipo, &$codigo, &$dni, &$nivel]);


        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }

    public function password($codigo) {
        $query = 'select password from alumnos where borra=0 and codigo=?';
        $tipo = 'i';

        $respuesta = $this->consultaRegistroArrayParam($query, [&$tipo, &$codigo]);

        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }

    /* --------------- REGION CONTRASEÑA ---------------------------------------- */

    public function guardarVinculoContrasenia($idUsuario, $codigo, $fechaExp) {
        $query = 'INSERT INTO recuperacion_contrasenia (id_usuario, codigo, expiracion, rol) VALUES (?, ?, ?, ?)';

        $tipos = 'issi';
        $respuesta = $this->realizarOperacionParam($query, [&$tipos, &$idUsuario, &$codigo, &$fechaExp, $rol], FALSE);

        return $respuesta;
    }

    public function verificarVinculoContrasenia($correoElectronico, $codigo, $rol, $id) {

        switch ($rol) {
            case 1:
                $query = 'SELECT r.id FROM recuperacion_contrasenia r
			INNER JOIN alumnos a ON a.sql_rowid = r.id_usuario
			WHERE r.codigo = ? AND a.email_tut = ? AND a.sql_rowid= ? AND expiracion > NOW()';
                break;
            case 2:
                $query = 'SELECT r.id FROM recuperacion_contrasenia r
			INNER JOIN alumnos a ON a.sql_rowid = r.id_usuario
			WHERE r.codigo = ? AND a.email_mad = ? AND a.sql_rowid= ? AND  expiracion > NOW()';

                break;
            case 3:
                $query = 'SELECT r.id FROM recuperacion_contrasenia r
			INNER JOIN alumnos a ON a.sql_rowid = r.id_usuario
			WHERE r.codigo = ? AND a.email_pad = ? AND a.sql_rowid= ? AND expiracion > NOW()';

                break;

            default:
                return[];
        }

        $tipos = 'ssi';
        $respuesta = $this->consultaRegistroArrayParam($query, [&$tipos, &$codigo, &$correoElectronico, &$id]);

        if (count($respuesta) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function actualizarContrasenia($contrasenia, $id, $rol) {

      //  $query = 'UPDATE alumnos SET password =? WHERE  sql_rowid=?';
       
        
        switch ($rol) {
            case 1:
                $query = 'UPDATE alumnos SET pass_tut = ? WHERE  sql_rowid=?';
                break;
            case 2:
                $query = 'UPDATE alumnos SET pass_mad = ? WHERE  sql_rowid=?';

                break;
            case 3:
                $query = 'UPDATE alumnos SET pass_pad = ? WHERE  sql_rowid=?';

                break;

            default:
                return[];
        }
    
        $tipos = 'si';
        //$c = password_hash($contrasenia, PASSWORD_DEFAULT);

        $this->realizarOperacionParam($query, [&$tipos, &$contrasenia, &$id], FALSE);

        $queryCod = 'DELETE  FROM recuperacion_contrasenia WHERE id_usuario=?';
        $tiposCod = 'i';

        $this->realizarOperacionParam($queryCod, [&$tiposCod, &$id], FALSE);
    }

    public function obtenerPorCorreoElectronico($idCodigo, $rol, $correoElectronico) {

        switch ($rol) {
            case 1:
                $query = 'select sql_rowid from alumnos where email_tut = ? and codigo = ? and borra = 0';
                break;
            case 2:
                $query = 'select sql_rowid from alumnos where email_mad = ? and codigo = ? and borra = 0';
                break;
            case 3:
                $query = 'select sql_rowid from alumnos where email_pad = ? and codigo = ? and borra = 0';
                break;
            default :
                return [];
        }

        $tipos = 'si';
        $respuesta = $this->consultaRegistroArrayParam($query, [&$tipos, &$correoElectronico, &$idCodigo]);

        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }

}
