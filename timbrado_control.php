<?php 
require 'clases/conexion.php';
session_start();

$accion = $_REQUEST['accion'];
if ($accion == 'obtenerTimbrado') {
	$timbradoCod = $_REQUEST['timbrado'];
	if (!empty($timbradoCod)) {		
		$sqlObtenerTimbrado = "select * from timbrado timb where timb.cod_timbrado = '$timbradoCod'";
		$resObtenerTim = consultas::get_datos($sqlObtenerTimbrado);
		if(!empty($resObtenerTim)){
			$datos['timbrado'] = $resObtenerTim;
			echo json_encode($datos);
			exit;
		}else{
			$datos['mensaje'] = 'No se obtuvo el timbrado.';
			http_response_code(401);
			echo json_encode($datos);
			exit;
		}
	}else{
		$datos['mensaje'] = 'No se obtuvo el timbrado.';
		http_response_code(401);
		echo json_encode($datos);
		exit;
	}
}
 ?>