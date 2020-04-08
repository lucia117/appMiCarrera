<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header('Access-Control-Allow-Headers: origin, content-type, accept, authorization, usuario, codigo, rol, contrasenia, colegio');
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
	header('Access-Control-Max-Age: 1209600');

	exit();
}

date_default_timezone_set('America/Argentina/Cordoba');

require 'RestServer.php';
require 'controlador/Alumno.php';
require 'controlador/Colegio.php';
require 'controlador/ControladorOperaciones.php';
require 'controlador/Inasistencia.php';
require 'controlador/Nota.php';
require 'controlador/Sancion.php';
require 'controlador/Mensaje.php';




$server = new RestServer('debug');
$server->addClass('Alumno'); 
$server->addClass('Colegio');
$server->addClass('ControladorOperaciones');
$server->addClass('Inasistencia'); 
$server->addClass('Nota'); 
$server->addClass('Sancion'); 
$server->addClass('Mensaje'); 
$server->handle();
