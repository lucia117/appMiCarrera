<?php
header('Content-Type: text/html; charset=UTF-8');
require 'dao/MensajeDao.php';

class Mensaje{
    
     /**
     * Obtener Listado de mensaje 
     * 
     * @url GET /mensaje/obtener-mensaje
     */
    public function obtenerMensaje() {

        $idCodigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
        $idColegio = filter_input(INPUT_GET, 'colegio', FILTER_SANITIZE_NUMBER_INT);

        $mensajeDao = new MensajeDao();
        $datosMensajes = $mensajeDao->obtenerMensajes($idCodigo, $idColegio);
       
        return ['datos' => $datosMensajes];
    }
    
    
}

?>