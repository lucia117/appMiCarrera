<?php

header('Content-Type: text/html; charset=UTF-8');
require_once 'mysql.php';

class NotaDao extends mysql {

    public function obtenerNotas($codigo, $idNivel, $idCursado) {

        switch ($idNivel) {
            case I:
                $query = 'SELECT inicial.codigo, inicial.colegio, materias.nombre, inicial.a_1, inicial.a_2 
                           FROM inicial
                           LEFT JOIN materias ON materias.colegio = inicial.colegio and materias.decual = substring(inicial.materia,1,1)  and materias.codigo = substring(inicial.materia,2,3) 
                           WHERE inicial.codigo=? and c_lectivo= 2019';
                break;
            case P:
                $query = 'SELECT primario.codigo, primario.colegio, materias.nombre, primario.e_1, primario.e_2, primario.e_3 
                            FROM primario
                            LEFT JOIN materias ON materias.colegio = primario.colegio and materias.decual = substring(primario.materia,1,1)  and materias.codigo = substring(primario.materia,2,3) 
                            WHERE primario.codigo =? and c_lectivo= 2019';
                break;
            case M:
                if ($idCursado[forma_calificacion] === 'T') { //MEDIO TRIMESTRAL
                    $query = 'SELECT medio.codigo, medio.colegio, materias.nombre, medio.e1_1, medio.e1_2, medio.e1_3, medio.e1_4, medio.e1_5, medio.e1_6, medio.conduc_1 , medio.e2_1, medio.e2_2, medio.e2_3, medio.e2_4, medio.e2_5, medio.e2_6,medio.conduc_2, medio.e3_1, medio.e3_2, medio.e3_3, medio.e3_4, medio.e3_5, medio.e3_6, medio.conduc_3 
                          FROM medio 
                          LEFT JOIN materias on  materias.colegio=medio.colegio and materias.decual = substring(medio.materia,1,1)  and materias.codigo = substring(medio.materia,2,3)   
                          WHERE medio.codigo =?  and c_lectivo= 2019';
                }
                if ($idCursado[forma_calificacion] === 'E') { //MEDIO CUATRIMESTRAL
                    $query = 'SELECT medio.codigo, medio.colegio, materias.nombre, medio.e1_1, medio.e1_2, medio.e1_3, medio.e1_4, medio.e1_5, medio.e1_6, medio.conduc_1 , medio.e2_1, medio.e2_2, medio.e2_3, medio.e2_4, medio.e2_5, medio.e2_6,medio.conduc_2  
                          FROM medio 
                          LEFT JOIN materias on  materias.colegio=medio.colegio and materias.decual = substring(medio.materia,1,1)  and materias.codigo = substring(medio.materia,2,3)   
                          WHERE medio.codigo =? and c_lectivo= 2019';
                }
                if ($idCursado[forma_calificacion] === 'A') { //MEDIO ANUAL/NOCTURNO 
                    $query = 'SELECT medio.codigo, medio.colegio, materias.nombre, medio.l1_1 AS c1, medio.r1, medio.l1_2 AS c2, medio.r2, medio.l1_3 AS c3, medio.r3, medio.l2_1 AS c4, medio.r4, medio.l2_2 AS c5, medio.r5, medio.l2_3 AS c6, medio.r6, medio.l3_1 AS c7, medio.r7, medio.l3_2 AS c8, medio.r8,  medio.l3_3 as IA, medio.pf
	                  FROM medio 
                          LEFT JOIN materias on  materias.colegio=medio.colegio and materias.decual = substring(medio.materia,1,1)  and materias.codigo = substring(medio.materia,2,3)   
                          WHERE medio.codigo = ? and c_lectivo= 2019';
                }
                  if ($idCursado[forma_calificacion] === 'B') { //MEDIO BIMESTRAL
                    $query = 'SELECT medio.codigo, medio.colegio, materias.nombre, medio.e1_1, medio.e1_2, medio.e1_3, medio.e1_4, medio.e1_5, medio.definitiva  
                          FROM medio 
                          LEFT JOIN materias on  materias.colegio=medio.colegio and materias.decual = substring(medio.materia,1,1)  and materias.codigo = substring(medio.materia,2,3)   
                          WHERE medio.codigo =?  and c_lectivo= 2019';
                }
          
                break;

            default :
                return [];
        }
        $tipo = 'i';

        $respuesta = $this->consultaArrayParam($query, [&$tipo, &$codigo]);
        
        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }
    
    public function obtenerTipoCursado($codigo){
        $query ='select 
	            alum.clave, 
                    alum.colegio,
                    c.forma_calificacion
                FROM univac.alumnos as alum 
                left join univac.cursos as c on c.colegio = alum.colegio
                where alum.codigo=? and borra=0 and c.codigo = alum.clave';
                
                $tipo = 'i';

        $respuesta = $this->consultaRegistroArrayParam($query, [&$tipo, &$codigo]);
                            
        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }

}




/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


