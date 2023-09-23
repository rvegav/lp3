<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';

$usuarioId = $_SESSION['usu_cod'];
$sucursal = $_SESSION['id_sucursal'];
if (!empty($_POST['txtAccion'])) {
	$txtAccion = $_POST['txtAccion'];
	$txtCajah = $_POST['txtCajah'];
	$txtCedula = $_POST['txtCedula'];
	$txtNombre = $_POST['txtNombre'];
	$txtMontoApertura = $_POST['txtMontoApertura'];
	$txtHora = $_POST['txtHora'];
	$txtFecha = $_POST['txtFecha'].' '.$txtHora;
	$slTimbrado = $_POST['slTimbrado'];
	$fechaActual = date('d-m-Y H:i:s');
	if (is_numeric($txtAccion)) {
			$consCaja = "select * from caja c where c.caj_cod = '$txtCajah' and c.id_sucursal = '$sucursal'";
			$resCaja = consultas::get_datos($consCaja);
			if(!empty($resCaja)){
				if ($txtAccion==1) {
					if (!empty($slTimbrado)) {
						$consCajaAperCierre = "select * from apertura_cierre ac where ac.caj_cod = '$txtCajah' and ac.id_sucursal = '$sucursal' and ac.estado = 'I' and ac.aper_fecha is not null and ac.aper_cierre is null and usu_cod = '$usuarioId'";
						$resCajaAperCierre = consultas::get_datos($consCajaAperCierre);
						if (empty($resCajaAperCierre)) {

							$consCajaActivaUsuario = "select * from apertura_cierre ac where ac.estado = 'I' and ac.aper_fecha is not null and ac.aper_cierre is null and usu_cod = '$usuarioId'";
							$resCajaAperCierre = consultas::get_datos($consCajaActivaUsuario);
							if (empty($resCajaAperCierre)) {

								if ($txtMontoApertura>=0) {
									$consMaxNroAper = "select max(nro_aper) from apertura_cierre";
									$resMaxNroAper = consultas::get_datos($consMaxNroAper);
									if (!empty($consMaxNroAper)) {
										$nuevoNumeroApertura = $resMaxNroAper[0]['max']+1;
										$insApertura = "insert into apertura_cierre(nro_aper,caj_cod,id_sucursal,aper_fecha,usu_cod,aper_cierre,monto_aper,total_efectivo,total_cheque,total_credito,estado,cod_timbrado)values('$nuevoNumeroApertura','$txtCajah','$sucursal','$txtFecha','$usuarioId',null,'$txtMontoApertura','0',null,null,'I','$slTimbrado');";
										$resApertura = consultas::get_datos($insApertura);
											echo json_encode('excelente');
											exit;
									}else{
										$datos['mensaje'] = 'Ocurrio un error inesperado.';
										http_response_code(401);
										echo json_encode($datos);
										exit;
									}

								}else{
									$datos['mensaje'] = 'El monto inicial de la caja no debe ser negativo.';
									http_response_code(401);
									echo json_encode($datos);
									exit;
								}
							}else{
								$datos['mensaje'] = 'El usuario ya tiene una caja activas.';
								http_response_code(401);
								echo json_encode($datos);
								exit;
							}
						}else{
							$datos['mensaje'] = 'La caja esta activa.';
							http_response_code(401);
							echo json_encode($datos);
							exit;
						}
					}else{
						$datos['mensaje'] = 'Campo de timbrado obligatorio.';
						http_response_code(401);
						echo json_encode($datos);
						exit;
					}
				}else if ($txtAccion==2) {
					$consCajaAperCierre = "select * from apertura_cierre ac where ac.caj_cod = '$txtCajah' and ac.id_sucursal = '$sucursal' and ac.estado = 'I' and ac.aper_fecha is not null and ac.aper_cierre is null and usu_cod = '$usuarioId'";
					$resCajaAperCierre = consultas::get_datos($consCajaAperCierre);
					if (!empty($resCajaAperCierre)) {
						$nroAper = $resCajaAperCierre[0]['nro_aper'];
					 	$codCaja = $resCajaAperCierre[0]['caj_cod'];
						$sqlConsVenta = "select * from ventas where nro_aper = '$nroAper' and caj_cod = '$codCaja'";
						$resConsVenta = consultas::get_datos($sqlConsVenta);
						$totalVentaContado = 0;
						$totalVentaCredito = 0;
						if (!empty($resConsVenta)) {
							for ($i=0; $i < count($resConsVenta); $i++) { 
								if ($resConsVenta[$i]['tipo_venta']=="CREDITO") {
									$totalVentaCredito += $resConsVenta[$i]['ven_total'];
								}else if ($resConsVenta[$i]['tipo_venta']=="CONTADO") {
									$totalVentaContado += $resConsVenta[$i]['ven_total'];
								}
							}
						}
						$sqlUpdateAperCierr = "update apertura_cierre set total_efectivo = '$totalVentaContado', total_credito = '$totalVentaCredito', aper_cierre = '$fechaActual' where nro_aper = '$nroAper' and caj_cod = '$codCaja' and usu_cod = '$usuarioId'";
						$resUpdateAperCierr = consultas::get_datos($sqlUpdateAperCierr);
						$datos['mensaje'] = "excelente";
						echo json_encode($datos);
						exit;
					}else{
						$datos['mensaje'] = 'La caja no esta activa.';
						http_response_code(401);
						echo json_encode($datos);
						exit;
					}
				}else{

				}
			}else{
				$datos['mensaje'] = 'No existe la caja seleccionada en la sucursal';
				http_response_code(401);
				echo json_encode($datos);
				exit;
			}

	}
}else{
	$nroCaja = $_POST['nroCaja'];
	$consCajaAperCierre = "select * from apertura_cierre ac inner join timbrado t on t.cod_timbrado = ac.cod_timbrado where ac.caj_cod = '$nroCaja' and ac.id_sucursal = '$sucursal' and ac.estado = 'I' and ac.aper_fecha is not null and ac.aper_cierre is null and usu_cod = '$usuarioId'";
	$resConsCajaAperCierre = consultas::get_datos($consCajaAperCierre);
	if ($resConsCajaAperCierre==true) {
		$nroAper = $resConsCajaAperCierre[0]['nro_aper'];
		$montoAper = $resConsCajaAperCierre[0]['monto_aper'];
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
			
			$datos['nro_timbrado'] = $resConsCajaAperCierre[0]['nro_timbrado'];
			$datos['vencimiento'] = $resConsCajaAperCierre[0]['vencimiento'];
			$datos['totalVentaContado'] = $totalVentaContado;
			$datos['totalVentaCredito'] = $totalVentaCredito;
			$datos['totalCierre'] = $totalVentaContado+$totalVentaCredito+$montoAper;
		}

		$datos['resConsCajaAperCierre'] = $resConsCajaAperCierre;
		echo json_encode($datos);
		exit();
	}else{
		$datos['mensaje'] = 'no se encontro la caja abierta';
		http_response_code(401);
		echo json_encode($datos);
		exit;
	}

}



//$resultado = consultas::get_datos($sql);