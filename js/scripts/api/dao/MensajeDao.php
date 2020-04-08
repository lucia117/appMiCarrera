<?php

header('Content-Type: text/html; charset=UTF-8');
require_once 'mysql.php';

class MensajeDao extends mysql {
    
    public function obtenerMensajes($codigo, $colegio) {

     
        
        $query = "select fecha_hora, escrito_por,titulo, texto,  n_adjunto from univac.notificaciones where colegio=? and 
                    (para_quien= 1 or 
                    (para_quien= 2 and texto_quien LIKE '% 2 ANEYADM 1%')or 
                    (para_quien= 3 and substr(texto_quien,1,8)=?))order by  sql_rowid desc ";

        
        $tipo = 'ii';
        
       $respuesta = $this->consultaArrayParam($query, [&$tipo, &$colegio, &$codigo]);
       
       
        if (empty($respuesta)) {
            return [];
        } else {
            return $respuesta;
        }
    }
    
}
