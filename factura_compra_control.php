<?php 
session_start();
require 'acceso_bloquear_compras.php';

require 'clases/conexion.php';

$nro_factura = $_REQUEST['vnro_factura'];
$nro_timbrado = $_REQUEST['vnro_timbrado'];
$prv_cod = $_REQUEST['vprv_cod'];
$monto = $_REQUEST['vfact_monto'];
$fecha = $_REQUEST['vped_fecha'];
if ($_REQUEST['accion']==1) {
	$compras = $_REQUEST['cod_com'];
	if (isset($_REQUEST['articulos'])) {
		$articulos = $_REQUEST['articulos']["'cod'"];
		$cantidades = $_REQUEST['articulos']["'cant'"];
		$precios = $_REQUEST['articulos']["'precio'"];
	}
	$sql = "INSERT INTO facturas_compras(faco_nro_factura, faco_fecha, faco_prv_cod, faco_timbrado, faco_monto) VALUES (".$nro_factura.", TO_DATE('". $fecha."','YYYY-MM-DD'),".$prv_cod.",".$nro_timbrado.",". $monto.")";
		$factura_insert = consultas::ejecutar_sql($sql);
		$sql = "select max(faco_cod) as faco_cod from facturas_compras where faco_nro_factura ='".$nro_factura."' and faco_timbrado = '" .$nro_timbrado."'";
		$factura_id = consultas::get_datos($sql);
		$factura_id = $factura_id[0]['faco_cod'];
		if ($compras>0) {
		// code...
			for ($i=0; $i < count($compras) ; $i++) { 
				$sql = "select dc.art_cod, dc.com_precio, dc.com_cant, sum(dc.com_precio * dc.com_cant) as monto_total, a.art_descri  from compras c join detalle_compras dc on dc.com_cod = c.com_cod join articulo a on dc.art_cod = a.art_cod where c.com_cod = ".$compras[$i]." group by dc.art_cod, a.art_descri, dc.com_precio, dc.com_cant";
				$detalles = consultas::get_datos($sql);
				foreach ($detalles as $detalle) {
					$sql = "INSERT INTO detalle_factura_compra(defc_com_cod, defc_faco_cod, defc_art_cod, defc_precio_compra, defc_cant) VALUES(".$compras[$i].",".$factura_id.",".$detalle['art_cod'].",".$detalle['com_precio'].",".$detalle['com_cant'].")";
					$detalle_insert = consultas::ejecutar_sql($sql);
				}
				$sql = "update compras set com_estado_pago = 'P' where com_cod = ".$compras[$i];
				$compras_update = consultas::ejecutar_sql($sql);
			}
		}
		for ($i=0; $i <count($articulos) ; $i++) { 
			$sql ="INSERT INTO detalle_factura_compra(defc_com_cod, defc_faco_cod, defc_art_cod, defc_precio_compra, defc_cant) VALUES(".$compras[$i].",".$factura_id.",".$articulos[$i].",".$precios[$i].",".$cantidades[$i].")";
			$detalle_insert = consultas::ejecutar_sql($sql);

		}
		if ($factura_insert) {
			$mensaje = 'Se registró correctamente la factura de compra';
			$_SESSION['mensaje'] = $mensaje;
			header("location:factura_compra_index.php");
		}else{
			$_SESSION['mensaje'] = 'ERROR: Ocurrió un problema';
			header("location:factura_compra_index.php");    
		}
	}elseif ($_REQUEST['accion']==2) {
		$sql = "INSERT INTO facturas_compras(faco_nro_factura, faco_fecha, faco_prv_cod, faco_timbrado, faco_monto) VALUES (".$nro_factura.", TO_DATE('". $fecha."','YYYY-MM-DD'),".$prv_cod.",".$nro_timbrado.",". $monto.")";
			$factura_insert = consultas::ejecutar_sql($sql);

			if ($factura_insert) {
				$sql = 'UPDATE ctas_a_pagar set estado_cuota =\'A\' where ctpa_id ='. $_REQUEST['vctpa_id'];
				$resultado = consultas::ejecutar_sql($sql);
				if ($resultado) {
					$mensaje = 'Se registró correctamente el pago';
					$_SESSION['mensaje'] = $mensaje;
					header("location:cuentas_pagar_index.php");
				}else{
					$_SESSION['mensaje'] = 'ERROR: Ocurrió un problema al actualizar ctas a pagar';
					header("location:cuentas_pagar_index.php");    	
				}
			}else{
				$_SESSION['mensaje'] = 'ERROR: Ocurrió un problema';
				header("location:cuentas_pagar_index.php");    
			}

		}
	?>