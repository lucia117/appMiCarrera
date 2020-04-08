<?php

header('Content-Type: text/html; charset=UTF-8');
require 'dao/NotaDao.php';

class Nota {

      /**
     * Obtener Listado de materias y sus notas 
     * 
     * @url GET /nota/obtener-notas
     */
    public function obtenerNotas() {

        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $idNivel = filter_input(INPUT_GET, 'nivel');
        $notaDao = new NotaDao();
        $idCursado = $notaDao->obtenerTipoCursado($idCodigo);
        
        $datosNotas = $notaDao->obtenerNotas($idCodigo, $idNivel, $idCursado);
        return [
            'datos' => [
                'forma_cursado'=>$idCursado[forma_calificacion], 
                'datos_notas'=>$datosNotas
                ]
            ];
         

    }
    
    
      /**
     * Obtener tipo de cursado de nivel medio 
     * 
     * @url GET /nota/obtener-tipo-cursado
     */
    public function obtenerTipoCursado() {

        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $idNivel = filter_input(INPUT_GET, 'nivel');
        $notaDao = new NotaDao();
        $idCursado = $notaDao->obtenerTipoCursado($idCodigo);
        return [$idCursado];

    }

}