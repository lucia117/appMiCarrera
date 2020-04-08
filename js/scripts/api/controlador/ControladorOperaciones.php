<?php
require 'dao/OperacionesDao.php';

class ControladorOperaciones {
    
    /**
     *  Realizar una transaccion
     * 
     * @url POST /tigo/transaccion
     */
    public function transaccion() {
        
        $inputJSON = file_get_contents('php://input');
        $body = json_decode($inputJSON, TRUE); //convert JSON into array
        
//        $resultado = $this->validarCamposTransaccion($body);
//        if(!empty($resultado)){
//            return array("error"=>true,"mensaje"=>$resultado);
//        }
        
        $operacionesDao = new OperacionesDao();
        
        return "Hola mundo";
        
    }
   
    
    private function validarCamposTransaccion($body){
        $campos = array();
        array_push($campos, array("clave" => "nro_documento_cliente","requerido"=>TRUE,"valor"=>""));
        array_push($campos, array("clave" => "orden_id", "requerido" => TRUE, "valor" => ""));
        array_push($campos, array("clave" => "mensaje", "requerido" => FALSE, "valor" => ""));
        array_push($campos, array("clave" => "monto", "requerido" => TRUE, "valor" => 0.0));
        array_push($campos, array("clave" => "linea_cliente", "requerido" => TRUE, "valor" => 123456));
        array_push($campos, array("clave" => "nombre_cliente", "requerido" => FALSE, "valor" => ""));
        array_push($campos, array("clave" => "url_exito", "requerido" => FALSE, "valor" => ""));
        array_push($campos, array("clave" => "url_error", "requerido" => FALSE, "valor" => ""));
        array_push($campos, array("clave" => "nombre_comercio_notificacion", "requerido" => TRUE, "valor" => "Cine"));
        array_push($campos, array("clave" => "codigo_notificacion", "requerido" => TRUE, "valor" => ""));
        array_push($campos, array("clave" => "cantidad", "requerido" => TRUE, "valor" => 1));
        array_push($campos, array("clave" => "concepto", "requerido" => TRUE, "valor" => ""));
        array_push($campos, array("clave" => "precio_unitario", "requerido" => TRUE, "valor" => 1));
        array_push($campos, array("clave" => "razon_social", "requerido" => TRUE, "valor" => ""));
        array_push($campos, array("clave" => "nit", "requerido" => TRUE, "valor" => ""));
        
        foreach ($campos as $campo) {
            $campoEncontrado = false;
            $valorCampo;

            foreach ($body as $key => $value) {
                if($key == $campo['valor']){
                    $campoEncontrado = true;
                    $valorCampo = $value;
                    continue;
                }
            }
            
            if(!$campoEncontrado){
                return "Campo " . $campo['valor'] . " requerido";
            }
        }
        return "";
    }
    
     /**
     *  Test
     * 
     * @url GET /test
     */
    public function test(){
        return "Funca";
    }
}