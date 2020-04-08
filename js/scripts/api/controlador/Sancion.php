<?php
header('Content-Type: text/html; charset=UTF-8');
require 'dao/SancionDao.php';

class Sancion{
    
         
    /**
	 * Obtener listado de Sanciones 
	 * 
	 * @url GET /sancion/obtenerListadoSanciones
	 */
    public function obtenerListadoSanciones($codigo){
        
        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
       
        $sancionDao = new SancionDao(); 
        
        $datosSancion = $sancionDao->obtenerListadoSanciones($idCodigo);
        
        return['datos'=>$datosSancion];
        

        
    }
    
    /**
     * Obtener cantidad total de Sancione 
     * 
     * @url GET /sancion/obtenerTotalSanciones
     */
    
    public function obtenerTotalSanciones($codigo){

        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        
        $sancionDao = new SancionDao(); 
        
        $datosSancion= $sancionDao->obtenerTotalSanciones($idCodigo);
        
        return ['datos'=> $datosSancion];
    }
   
    
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

