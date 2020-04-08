<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'mysql.php';

class ColegioDao extends mysql {

    public function obtenerColegiosParaSelect() {

        $idNivel = filter_input(INPUT_GET, 'nivel');

        switch ($idNivel) {
            case I:
                
                $query1 = 'SELECT codigo, colegio FROM parametros where  INI=1';
                $query = 'SELECT colegio AS codigo, nomcolegio AS colegio FROM niveles WHERE si_internet = 1 AND codigo = "I"  order by colegio asc'; 

                $respuesta = $this->consultaArray($query);
                break;
            case P:
               $query1 = 'SELECT codigo, colegio FROM parametros where  PRI=1  order by colegio asc';
               $query = 'SELECT colegio AS codigo, nomcolegio AS colegio FROM niveles WHERE si_internet = 1 AND codigo = "P"  order by colegio asc'; 
               
                $respuesta = $this->consultaArray($query);
                break;
            case M:
                $query1 = 'SELECT codigo, colegio FROM parametros where MED=1';
                $query = 'SELECT colegio AS codigo, nomcolegio AS colegio FROM niveles WHERE si_internet = 1 AND codigo = "M"  order by colegio asc'; 
                $respuesta = $this->consultaArray($query);
                break;
            case T:
               $query1 = 'SELECT codigo, colegio FROM parametros where TER=1  order by colegio asc';
               $query = 'SELECT colegio AS codigo, nomcolegio AS colegio FROM niveles WHERE si_internet = 1 AND codigo = "T"  order by colegio asc'; 
                $respuesta = $this->consultaArray($query);
                break;
            default :
                return [];
                //break;
        }

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

