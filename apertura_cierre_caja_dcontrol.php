<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
#session_start();

$usuarioId = $_SESSION['usu_cod'];
$sucursal = $_SESSION['id_sucursal'];
$nroAper = $_POST['nro_aper'];

$consDetalleCierre = "select * from apertura_cierre where nro_aper = '$nroAper'";
$resConsDetalleCierre = consultas::get_datos($consDetalleCierre);
if (!empty($resConsDetalleCierre)) {
	$nroCaja = $resConsDetalleCierre[0]['caj_cod'];
	$montoAper = $resConsDetalleCierre[0]['monto_aper'];
	$sqlConsVenta = "select * from ventas where nro_aper = '$nroAper' and caj_cod = '$nroCaja'";
	$resConsVenta = consultas::get_datos($sqlConsVenta);
	if (!empty($resConsVenta)) {
		$totalVentaContado = 0;
		$totalVentaCredito = 0;
		for ($i=0; $i < count($resConsVenta); $i++) { 
			if ($resConsVenta[$i]['tipo_venta']=="CREDITO") {
				$totalVentaCredito += $resConsVenta[$i]['ven_total'];
			}else if ($resConsVenta[$i]['tipo_venta']=="CONTADO") {
				$totalVentaContado += $resConsVenta[$i]['ven_total'];
			}
		}
		$datos['totalVentaContado'] = $totalVentaContado;
		$datos['totalVentaCredito'] = $totalVentaCredito;
		$datos['totalCierre'] = $totalVentaContado+$totalVentaCredito+$montoAper;
	}
	$datos['detalleVentas'] = $resConsVenta;
	$datos['detalleCierre'] = $resConsDetalleCierre;
	echo json_encode($datos);
	exit();
}else{
	$datos['mensaje'] = 'no se encontraron los datos';
	http_response_code(401);
	echo json_encode($datos);
	exit();
}




?>