<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'mysql.php';

class InasistenciaDao extends mysql {

    public function obtenerListadoInasistencia($codigo) {
        $query1 = "SELECT ausentes.fecha, ausentes.cantidad, ausentes.justifica, t_faltas.nombre AS tipoFalta, ausentes.motivo
                FROM  ausentes
                INNER JOIN alumnos ON alumnos.codigo = ausentes.codigo 
                INNER JOIN t_faltas ON t_faltas.sql_rowid = ausentes.tipofalta 
                WHERE alumnos.codigo = ?
                ORDER BY ausentes.fecha DESC";
        
	$query="SELECT ausentes.fecha, ausentes.cantidad, ausentes.justifica, t_faltas.nombre AS tipoFalta, ausentes.motivo 
			FROM ausentes 
			LEFT JOIN t_faltas ON ausentes.colegio=t_faltas.colegio and ausentes.codi_falta=t_faltas.codigo 
			where ausentes.codigo= ?
			ORDER BY ausentes.fecha DESC ";
	





        
        $tipo = 'i';

        $respuesta = $this->consultaArrayParam($query, [&$tipo, &$codigo]);

        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }

    public function ausentes($codigo, $colegio) {

        $query = "SELECT * FROM ausentes WHERE colegio=? and codigo=? ORDER BY fecha";

        $tipo = 'ii';

        $respuesta = $this->consultaArrayParam($query, [&$tipo, &$colegio, &$codigo]);

        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }

    public function TiposFaltaSegunColegio($colegio) {
        //$query = 'SELECT * FROM t_faltas WHERE colegio=? ORDER BY codigo';
        $query = 'SELECT codigo,acumula, hastacu, incide, tipofalta,tardjusti,tardacumu, nombre FROM t_faltas WHERE colegio=? ORDER BY codigo';
        $tipo = 'i';
        $respuesta = $this->consultaArrayParam($query, [&$tipo, &$colegio]);
        return $respuesta;
    }

    public function faltasAlumno($colegio, $codigo) {
        $query = 'SELECT ausentes.codigo, ausentes.colegio, ausentes.fecha, t_faltas.nombre, ausentes.cantidad, ausentes.justifica, ausentes.motivo, ausentes.codi_falta, ausentes.tipofalta 
                    FROM ausentes 
                    LEFT JOIN t_faltas ON t_faltas.colegio = ausentes.colegio 
                    WHERE ausentes.colegio= ? and ausentes.codigo= ? ORDER BY fecha';

        $tipo = 'ii';
        $respuesta = $this->consultaArrayParam($query, [&$tipo, &$colegio, &$codigo]);

        return $respuesta;
    }

    public function acumulaTipoFalta($colegio, $codigo) {
        $query = 'SELECT colegio, fecha, cantidad, justifica, motivo, codi_falta, tipofalta FROM ausentes WHERE colegio=? and codigo=? ORDER BY fecha;';
        $tipo = 'ii';
        $respuesta = $this->consultaArrayParam($query, [&$tipo, &$colegio, &$codigo]);

        return $respuesta;
    }

    public function CAR_TFALTA($colegio) {

        $inasistenciDao = new InasistenciaDao();
        $faltas = $inasistenciDao->TiposFaltaSegunColegio($colegio);

        $tFalta = array();
        foreach ($faltas as $obj) {
            $descripcion = array(
                "codigo" => $obj['codigo'],
                "a1" => 0,
                "a2" => 0,
                "a3" => 0,
                "acumula" => $obj['acumula'],
                "hastacu" => $obj['hastacu'],
                "incide" => $obj['incide'],
                "tipofalta" => $obj['tipofalta'],
                "tardjusti" => $obj['tardjusti'],
                "tardacumu" => $obj['tardacumu'],
                "nombre" => $obj['nombre'],
            );

            array_push($tFalta, $descripcion);
        }

        return $tFalta;
    }

    public function acumula($colegio, $codigo) {

        $inasistenciDao = new InasistenciaDao();
        $faltasAlumno = $inasistenciDao->faltasAlumno($colegio, $codigo);
        $tFalta = $inasistenciDao->CAR_TFALTA($colegio);
        $AnCU= 0; 
        $AaCU =0;
        //return $tFalta;
        //return $faltasAlumno;

        foreach ($faltasAlumno as $obj) {
            
            foreach ($tFalta as $falta) {
                
                while ($obj['codi_falta'] === $falta['codigo']) {

                    if ($falta['acumula'] === 1) {
                        $falta['acumula']+= 1;

                        if ($falta['incide'] === 1) {
                            
                            if ($falta['a1'] > $falta['hastacu']) {
                                /* Falta bloque de codigo */
                            }
                        } else {
                            if ($falta['a1'] == $falta['hastacu']) {
                                $falta['a1'] = 0;
                                if ($falta['tardjusti'] === 1) {
                                    $falta['a2'] += (int) $falta['tardacum'];
                                } else {
                                    $falta['a3'] += (int) $falta['tardacum'];
                                }
                            } else {
                                if ($falta['tipofalta' < 5]) {
                                    foreach ($falta as $obj1) {
                                        if ($obj1['tipofalta'] < 5 && $obj1['acumula'] === 1 && $obj1['incide'] === 0 && $obj1['a1'] <> 0) {
                                            $AnCU += $obj1['hastacu'];
                                            $AaCU += $obj1['a1'];
                                        }
                                    }
                                    if ($AnCU > 0 && $AaCU >= $AnCU) {
                                        foreach ($falta as $obj2) {
                                            if ($obj2['tipofalta'] < 5 && $obj2['acumula'] === 1 && $obj2['incide'] === 0 && $obj2['a1'] <> 0) {
                                                $falta['a1'] = 0;
                                            }
                                        }
                                        $falta['a1'] = $AaCU - ($AaCU / $AnCU) * $AnCU;
                                        //ver codigo con roberto ; 
                                    }
                                }
                            }
                        }
                    } else {
                        // ver codigo con roberto 
                    }
                }
            }
        }


        return $codigo;
    }

    public function totalInasistencias($colegio, $codigo) {
        $inasistenciDao = new InasistenciaDao();
        $faltasAlumno = $inasistenciDao->faltasAlumno($colegio, $codigo);
        $tFalta = $inasistenciDao->TiposFaltaSegunColegio($colegio);
        $s = 0;

        $tFaltaA = array();
        foreach ($faltasAlumno as $obj) {
            $descripcion = array(
                "codigo" => $obj['codigo'],
                "acumula1" => 0,
                "acumula2" => 0,
                "acumula3" => 0,
                "acumula" => $obj['acumula'],
                "hastacu" => $obj['hastacu'],
                "incide" => $obj['incide'],
                "tipofalta" => $obj['tipofalta'],
                "tardjusti" => $obj['tardjusti'],
                "tardacum" => $obj['tardacum'],
                "nombre" => $obj['nombre'],
            );

            array_push($tFaltaA, $descripcion);
        }

        return $tFalta;


        $AnCU = 0;
        $AaCU = 0;


        foreach ($faltasAlumno as $obj) {

            if ($tFalta['acumula'] === 1) {
                $acumula1 += 1;
                if ($tFalta['incide'] === 1) {
                    if ($acumula1 > $tFalta['hastacu']) {
                        /* Falta bloque de codigo */
                    }
                } else {
                    if ($acumula1 == $tFalta['hastacu']) {
                        $acumula1 = 0;
                        if ($tFalta['tipojusti'] === 1) {
                            $acumula2 += (int) $tFalta['tardacum'];
                        } else {
                            $acumula3 += (int) $tFalta['tardacum'];
                        }
                    } else {
                        if ($tFalta['tipofalta' < 5]) {
                            foreach ($tFalta as $obj1) {
                                if ($obj1['tipofalta'] < 5 && $obj1['acumula'] === 1 && $obj1['incide'] === 0 && $acumula1 <> 0) {
                                    $AnCU += $obj1['hastacu'];
                                    $AaCU += $acumula1;
                                }
                            }
                            if ($AnCU > 0 && $AaCU > $AnCU) {
                                foreach ($tFalta as $obj2) {
                                    if ($obj2['tipofalta'] < 5 && $obj2['acumula'] === 1 && $obj2['incide'] === 0 && $acumula1 <> 0) {
                                        $acumula1 = 0;
                                    }
                                }
                                $acumula1 = $AaCU - ($AaCU / $AnCU) * $AnCU;
                                //ver codigo con roberto ; 
                            }
                        }
                    }
                }
            } else {
                // ver codigo con roberto 
            }
        }
    }

    
    
  public function inasistenciaTerciario($codigo) {
        $query = "SELECT cantidad,SUBSTRING(motivo,2,3) as materia, motivo FROM ausentes WHERE codigo=?";
        
        $tipo = 'i';

        $respuesta = $this->consultaArrayParam($query, [&$tipo, &$codigo]);

        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }  
    
    
    
    }
