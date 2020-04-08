<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'mysql.php';

class SancionDao extends mysql{
    
    public function obtenerListadoSanciones($codigo){
        $query = "SELECT sancion.fecha, sancion.motivo 
                FROM sancion
                LEFT JOIN alumnos
                ON sancion.codigo = alumnos.codigo
                WHERE alumnos.codigo = ? and borra=0
                ORDER BY sancion.fecha DESC" 
                ;
        $tipo = 'i';
        
        //$respuesta = $this->consultaArray($query, [&$tipo, &$codigo]);
        
        $respuesta = $this->consultaArrayParam($query, [&$tipo, &$codigo]);

		if (empty($respuesta)) {
			return [];
		} else {
			return $respuesta;
		}
    }
    
    public function obtenerTotalSanciones($codigo){
        
        $query = "SELECT codigo, SUM(cantidad) as Cantidad FROM `sancion` WHERE codigo = ?";
        
        $tipo = 'i';
        
        $respuesta = $this->consultaRegistroArrayParam($query, [&$tipo,&$codigo]);
        
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

