<?php

header('Content-Type: text/html; charset=UTF-8');

class MySQL {

    private $conexion;
    private $archivo_config;
    public $archivo_config_api;
    private $parametros;
    private static $instancia; //The single instance
    public $parametros_api;

    public static function getInstance() {
        if (!self::$instancia) { // If no instance then make one
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function __construct() {
        //$this->archivo_config = "/home/lu/Documentos/Desarrollo/WEB/apiGestionAlumno/config.ini"; //UBUNTU
        //$this->archivo_config = "/home/lu/Documentos/Desarrollo/Univac/config.ini";//
        //$this->archivo_config = "C:/Users/Lucia/OneDrive/Documents/Desarrollo/php/api/config.ini";//WINDOWS
        $this->archivo_config = "../../public_html/api/config.ini"; //SERVIDOR


        if (!isset($this->conexion)) {
            $this->parametros = $this->parametrosDB();
            $this->conexion = mysqli_connect($this->parametros["host"], $this->parametros["usuario"], $this->parametros["contrasenia"], $this->parametros["base"]);
            if (mysqli_connect_error()) {
                die('Could not connect to the database');
            }
            mysqli_query($this->conexion, "SET NAMES 'utf8'");
            mysqli_query($this->conexion, "SET AUTOCOMMIT=0;");
        }
    }

    private function parametrosDB() {
        $array_ini = parse_ini_file($this->archivo_config, true);
        return $array_ini["base_de_datos"];
    }

//    public function consultaRegistroArray($query) {
//        $datos = array();
//
//        $result = mysqli_query($this->conexion, $query);
//        if (mysqli_num_rows($result) == 1) {
//
//            while ($arr = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//                $datos[] = $arr;
//            }
//        }
//
//        return($datos);
//    }

    public function consultaArray($query) {
        $datos = array();

        $result = mysqli_query($this->conexion, $query);
        if (mysqli_num_rows($result) > 0) {

            while ($arr = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $datos[] = $arr;
            }
        }
        return $datos;
    }

//    public function realizarOperacion($query, $permitirCeroAfectados) {
//
//        $result = mysqli_query($this->conexion, $query) or $this->onDieOperacion();
//        if (!$permitirCeroAfectados) {
//            if ($result && mysqli_affected_rows($this->conexion) > 0) {
//                return mysqli_insert_id($this->conexion);
//            } 
//        } else {
//            if ($result) {
//                return mysqli_insert_id($this->conexion);
//            } 
//        }
//        
//        return -1;
//    }

    public function realizarOperacionSinDieError($query, $permitirCeroAfectados) {

        try {
            $result = mysqli_query($this->conexion, $query);
            if (!$permitirCeroAfectados) {
                if ($result && mysqli_affected_rows($this->conexion) > 0) {
                    return mysqli_insert_id($this->conexion);
                }
            } else {
                if ($result) {
                    return mysqli_insert_id($this->conexion);
                }
            }

            return -1;
        } catch (Exception $e) {
            return -1;
        }
    }

    public function comenzarTransaccion() {
        $sql = "BEGIN;";
        $resultado = mysqli_query($this->conexion, $sql);
        return $resultado;
    }

    public function finalizarTransaccion($resultado) {
        if ($resultado) {
            $sql = "COMMIT";
            $resultado = mysqli_query($this->conexion, $sql);
        } else {
            $sql = "ROLLBACK;";
            $resultado = mysqli_query($this->conexion, $sql);
        }

        return $resultado;
    }

    private function onDieOperacion() {
        $error = "Error: ";
        switch (mysqli_errno($this->conexion)) {
            case 1062:
                $error .= "C��digo o parametro duplicado. Verificar los datos ingresados.";
                break;

            case 1451:
                $error .= "C��digo o parametro no habilitado para modificarlo y/o eliminarlo. Verificar los datos ingresados.";
                break;

            default:
                $error .= "Error al realizar la operaci��n. Verificar los datos ingresados. Error Nro: " . mysqli_errno($this->conexion);
                break;
        }
        die($error);
    }

    public function cerrarConexion() {
        mysqli_close($this->conexion);
    }

    public function setConexion($conexion) {
        $this->conexion = $conexion;
        mysqli_query($this->conexion, "SET NAMES 'utf8'");
        mysqli_query($this->conexion, "SET AUTOCOMMIT=0;");
    }

//    	private function parametrosDB($tipoParam = 1) {
//		$r = [];
//		switch ($tipoParam) {
//			case self::PARAM_CONFIG_INI:
//				$array_ini = parse_ini_file($this->archivo_config, true);
//				$r = $array_ini["base_de_datos"];
//				break;
//			case self::PARAM_RDS:
//				$r = ['host' => $_SERVER['RDS_HOSTNAME'],
//						'usuario' => $_SERVER['RDS_USERNAME'],
//						'contrasenia' => $_SERVER['RDS_PASSWORD'],
//						'base' => $_SERVER['RDS_DB_NAME'],
//				];
//				break;
//
//			default:
//				throw new Exception('No se encuentran los par��metros.');
//		}
//
//		return $r;
//	}

    private function parametrosAPI() {
        $array_ini = parse_ini_file($this->archivo_config_api, true);
        return $array_ini["api"];
    }

    public function consultaRegistroArray($query) {
        $datos = array();
        $result = mysqli_query($this->conexion, $query);
        if (mysqli_num_rows($result) == 1) {
            while ($arr = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $datos[] = $arr;
            }
        }
        return($datos);
    }

    public function consultaRegistroArrayParam($query, $params) {
        $sentencia = $this->conexion->prepare($query);
        echo $this->conexion->error;
        call_user_func_array(array($sentencia, 'bind_param'), $params);
        $sentencia->execute();
        $res = $sentencia->get_result();
        return $res->fetch_assoc();
    }

//	public function consultaArray($query) {
//		$datos = array();
//		$result = mysqli_query($this->conexion, $query);
//		if (mysqli_num_rows($result) > 0) {
//			while ($arr = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//				$datos[] = $arr;
//			}
//		}
//		return $datos;
//	}

    public function consultaArrayParam($query, $params) {
        $sentencia = $this->conexion->prepare($query);
        echo $this->conexion->error;
        call_user_func_array(array($sentencia, 'bind_param'), $params);
        $sentencia->execute();
        $res = $sentencia->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function realizarOperacion($query, $permitirCeroAfectados) {
        $result = mysqli_query($this->conexion, $query) or $this->onDieOperacion();
        if (!$permitirCeroAfectados) {
            if ($result && mysqli_affected_rows($this->conexion) > 0) {
                return mysqli_insert_id($this->conexion);
            }
        } else {
            if ($result) {
                return mysqli_insert_id($this->conexion);
            }
        }
        return -1;
    }

    public function realizarOperacionParam($query, $params, $permitirCeroAfectados) {
        $sentencia = $this->conexion->prepare($query);
        call_user_func_array(array($sentencia, 'bind_param'), $params) or die($sentencia->error);
        $result = $sentencia->execute() or $this->onDieOperacion();

        if (!$permitirCeroAfectados) {
            if ($result && $sentencia->affected_rows > 0) {
                return $sentencia->insert_id;
            }
        } else {
            if ($result) {
                return $sentencia->insert_id;
            }
        }
        return -1;
    }

//	public function realizarOperacionSinDieError($query, $permitirCeroAfectados) {
//		try {
//			$result = mysqli_query($this->conexion, $query);
//			if (!$permitirCeroAfectados) {
//				if ($result && mysqli_affected_rows($this->conexion) > 0) {
//					return mysqli_insert_id($this->conexion);
//				}
//			} else {
//				if ($result) {
//					return mysqli_insert_id($this->conexion);
//				}
//			}
//			return -1;
//		} catch (Exception $e) {
//			return -1;
//		}
//	}
//	public function comenzarTransaccion() {
//		$sql = "BEGIN;";
//		$resultado = mysqli_query($this->conexion, $sql);
//		return $resultado;
//	}
//
//	public function finalizarTransaccion($resultado) {
//		if ($resultado) {
//			$sql = "COMMIT;";
//			$resultado = mysqli_query($this->conexion, $sql);
//		} else {
//			$sql = "ROLLBACK;";
//			$resultado = mysqli_query($this->conexion, $sql);
//		}
//		return $resultado;
//	}
//
//	private function onDieOperacion() {
//		$error = "Error: ";
//		switch (mysqli_errno($this->conexion)) {
//			case 1062:
//				$error .= "C��digo o parametro duplicado. Verificar los datos ingresados.";
//				break;
//			case 1451:
//				$error .= "C��digo o parametro no habilitado para modificarlo y/o eliminarlo. Verificar los datos ingresados.";
//				break;
//			default:
//				$error .= "Error al realizar la operaci��n. Verificar los datos ingresados. Error Nro: " . mysqli_errno($this->conexion);
//				break;
//		}
//		die($error);
//	}
//
//	public function cerrarConexion() {
//		mysqli_close($this->conexion);
//	}
//
//	public function setConexion($conexion) {
//		$this->conexion = $conexion;
//		mysqli_query($this->conexion, "SET NAMES 'utf8'");
//		mysqli_query($this->conexion, "SET AUTOCOMMIT=0;");
//	}
//
//	public function getParametrosAPI() {
//		return $this->parametrosAPI();
//	}
}
