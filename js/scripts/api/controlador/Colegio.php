<?php

header('Content-Type: text/html; charset=UTF-8');
require 'dao/ColegioDao.php';

class Colegio {

    /**
     *  Obtener Colegios para select
     * 
     * @url GET /colegio/obtenerColegiosParaSelect
     */
    public function obtenerColegiosParaSelect() {
        $idNivel = filter_input(INPUT_GET, 'nivel');
        $colegioDao = new ColegioDao();
        $colegios = $colegioDao->obtenerColegiosParaSelect($idNivel);
        return ['datos' => $colegios];
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

