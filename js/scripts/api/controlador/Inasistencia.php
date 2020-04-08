<?php

header('Content-Type: text/html; charset=UTF-8');
require 'dao/InasistenciaDao.php';

class Inasistencia {

    /**
     * Obtener Categorias Listado insasistencia para tabla
     * 
     * @url GET /inasistencia/obtenerListadoInasistencia
     */
    public function obtenerListadoInasistencia() {

        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $inasistenciaDao = new InasistenciaDao();
        $datosInasistencia = $inasistenciaDao->obtenerListadoInasistencia($idCodigo);
        return ['datos' => $datosInasistencia];

    }

    /**
     * Obtener Categorias Listado insasistencia para tabla
     * 
     * @url GET /inasistencia/totalJustificada
     */
    public function totalJustificada($codigo) {
        
        
    }

    /**
     * Obtener Categorias Listado insasistencia para tabla
     * 
     * @url GET /inasistencia/totalInjustificada
     */
    public function totalInjustificada($codigo) {
        
    }

    /**
     * Obtener Categorias Listado insasistencia para tabla
     * 
     * @url GET /inasistencia/total
     */
    public function total($codigo) {
        
    }

    /**
     * Obtener los tipos de faltas por colegio
     * 
     * @url GET /inasistencia/tipoFalta
     */
    public function tipoFalta() {
        $idColegio = filter_input(INPUT_GET, 'colegio', FILTER_SANITIZE_NUMBER_INT);
        $inasistenciaDao = new InasistenciaDao();
        $datosInasistencia = $inasistenciaDao->TiposFaltaSegunColegio($idColegio);
        return ['datos' => $datosInasistencia];
    }

    /**
     * Obtener todas las inasistencias de un alumno 
     * 
     * @url GET /inasistencia/faltasAlumno
     */
    public function faltasAlumno($colegio, $codigo) {
        $idColegio = filter_input(INPUT_GET, 'colegio', FILTER_SANITIZE_NUMBER_INT);
        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $inasistenciDao = new InasistenciaDao();
        $datosInasistencia = $inasistenciDao->faltasAlumno($idColegio, $idCodigo);
        return['datos' => $datosInasistencia];
    }

    /**
     * Obtener todas las inasistencias de un alumno 
     * 
     * @url GET /inasistencia/acumula
     */
    public function acumula() {
        $idColegio = filter_input(INPUT_GET, 'colegio', FILTER_SANITIZE_NUMBER_INT);
        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $inasistenciDao = new InasistenciaDao();
        $datosInasistencia = $inasistenciDao->acumula($idColegio, $idCodigo);
        return['INASISTENCIA' => $datosInasistencia];
    }

    /**
     * Obtener todas las inasistencias de un alumno 
     * 
     * @url GET /inasistencia/pruebaForeach
     */
    public function pruebaForeach() {
        $idColegio = filter_input(INPUT_GET, 'colegio', FILTER_SANITIZE_NUMBER_INT);
        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $inasistenciDao = new InasistenciaDao();
        $faltasAlumno = $inasistenciDao->CAR_TFALTA($idColegio, $idCodigo);

        return['faltas' => $faltasAlumno];
    }
    
   
    /**
     * Obtener todas las inasistencias de un alumno 
     * 
     * @url GET /inasistencia/inasistenciaTerciario
     */
    public function inasistenciaTerciario() {
        
        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $inasistenciDao = new InasistenciaDao();
        $faltasAlumno = $inasistenciDao->inasistenciaTerciario($idCodigo);

        return['faltas' => $faltasAlumno];
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

