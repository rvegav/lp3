<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
include_once 'clases/conexion.php';
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;
$vpedCod = $_GET['vped_cod'];

$timbrado = consultas::get_datos("select nro_timbrado, vencimiento from timbrado order by cod_timbrado desc limit 1");
$sqlPedidoVenta = "select * from pedido_venta where ven_cod = '$vpedCod'";
$resPedidoVenta = consultas::get_datos($sqlPedidoVenta);
$factura_insert = false;
if ($resPedidoVenta) {

	$pedidoVenta = $resPedidoVenta[0]['ped_cod'];
	$factura = consultas::get_datos("select fact_cod, fact_nro as ped_cod, fact_fecha_emision ped_fecha, concat(cli_nombre, cli_apellido) as clientes, fact_cliente_ruc, cli_ci from facturas f join clientes c on c.cli_cod = f.fact_clie_cod where fact_nro= '$pedidoVenta'");
	if ($factura) {
		$cabecera = $factura;
		$detalles = consultas::get_datos("select a.art_descri, det_cantidad as ped_cant, det_precio_uni as ped_precio from detalle_facturas df join articulo a on a.art_cod = df.det_art_cod where df.det_fact_cod =".$cabecera[0]['fact_cod']);

	}else{
		$cabecera = consultas::get_datos("select * from v_pedido_cabventa where ped_cod = '$pedidoVenta'");
		$fecha_cabecera = consultas::get_datos("select to_char(to_date(ped_fecha, 'DD/MM/YYYY'),'YYYY-MM-DD') as fecha_emision from v_pedido_cabventa where ped_cod = '$pedidoVenta'");
		$detalles = consultas::get_datos("select * from v_detalle_pedventa where ped_cod =".$cabecera[0]['ped_cod']);
		
		$sql = "INSERT INTO facturas(fact_nro, fact_fecha_emision, fact_clie_cod) VALUES ('".$cabecera[0]['ped_cod'] ."',TO_DATE('".
				$fecha_cabecera[0]['fecha_emision'] . "','YYYY-MM-DD'),'". $cabecera[0]['cli_cod']."')";
		$factura_insert = consultas::ejecutar_sql($sql);
		$factura = consultas::get_datos("select fact_cod, fact_nro, fact_fecha_emision, concat(cli_nombre, cli_apellido) as clientes , fact_cliente_ruc from facturas f join clientes c on c.cli_cod = f.fact_clie_cod where fact_nro= '$pedidoVenta'");
	}
	$tabla = "";
	$orden = 1;
	$subtotal = 0;
	$total = 0;
	foreach ($detalles as $d) {
		if ($factura_insert) {
			
			$sql = "INSERT INTO detalle_facturas(det_fact_cod, det_art_cod, det_cantidad, det_precio_uni) VALUES (".$factura[0]['fact_cod'] .",".$d['art_cod'] . ",". $d['ped_cant'].",".$d['ped_precio'].")";
			consultas::ejecutar_sql($sql);
		}
		$subtotal = $subtotal + $d['ped_cant']*$d['ped_precio'];
		$tabla .="<tr>";
		$tabla .="<td>".$orden."</td>";
		$tabla .="<td>".$d['art_descri']."</td>";
		$tabla .="<td align=\"right\">".number_format($d['ped_cant'],0,',','.')."</td>";
		$tabla .="<td align=\"right\">".number_format($d['ped_precio'],0,',','.')."</td>";
		$tabla .="<td align=\"right\">".number_format($d['ped_cant']*$d['ped_precio'],0,',','.')."</td>";
		$tabla .="</tr>";
		$orden++;
	}
	$total = $subtotal;
	$dompdf = new Dompdf();
	$path = 'logo.png';
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
	$reporte = file_get_contents('r_factura.php');
	$reporte = str_replace('${LOGO}', "<img src='$base64' alt=''>", $reporte);
	$reporte = str_replace('${TIMBRADO}', $timbrado[0]['nro_timbrado'], $reporte);
	$reporte = str_replace('${FECHA_VENCIMIENTO}', date('d/m/Y', strtotime($timbrado[0]['vencimiento'])), $reporte);
	$reporte = str_replace('${NRO_FACTURA}', $cabecera[0]['ped_cod'], $reporte);
	$reporte = str_replace('${FECHA_EMISION}', $cabecera[0]['ped_fecha'], $reporte);
	$reporte = str_replace('${CLIENTE}', $cabecera[0]['clientes'], $reporte);
	$reporte = str_replace('${CLIENTE_RUC}', $cabecera[0]['cli_ci'], $reporte);
	$reporte = str_replace('${PRODUCTOS}', $tabla, $reporte);
	$reporte = str_replace('${SUBTOTAL}', number_format($subtotal,0 ,',','.'), $reporte);
	$reporte = str_replace('${TOTAL}', number_format($total,0 ,',','.'), $reporte);
	$dompdf->loadHtml($reporte);

	// var_dump($reporte);
	// die();
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();

	$dompdf->stream("factura.pdf", array("Attachment" => 0));
}else{
	$_SESSION['mensajeValid'] = 'NO SE HA ENCONTRADO UN PEDIDO DE VENTA, PROBABLE VENTA ANULADA';
	header("Location: ventas_index.php");
	die();
}