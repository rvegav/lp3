<?php 
include_once 'clases/conexion.php';
// include autoloader
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$vpedCod = $_GET['vped_cod'];
$timbrad = consultas::get_datos("select nro_timbrado, vencimiento from timbrado order by cod_timbrado desc limit 1");
$pedidos = consultas::get_datos("select * from detalle_pedcompra dp 
join stock s on s.dep_cod = dp.dep_cod and s.art_cod = dp.art_cod 
join articulo a on a.art_cod = dp.art_cod
where ped_cod = $vpedCod");
$proveedor = consultas::get_datos("select * from pedido_cabcompra pc 
join proveedor p on p.prv_cod = pc.prv_cod 
where pc.ped_cod = $vpedCod");
$tabla="";
$orden = 1;
$subtotal = 0;
$total = 0;
foreach ($pedidos as $d) {
    $subtotal = $subtotal + $d['ped_cant'] * $d['ped_precio'];
    $tabla .= "<tr>";
    $tabla .= "<td>" . $orden . "</td>";
    $tabla .= "<td>" . $d['art_descri'] . "</td>";
    $tabla .= "<td align=\"right\">" . number_format($d['ped_cant'], 0, ',', '.') . "</td>";
    $tabla .= "<td align=\"right\">" . number_format($d['ped_precio'], 0, ',', '.') . "</td>";
    $tabla .= "<td align=\"right\">" . number_format($d['ped_cant'] * $d['ped_precio'], 0, ',', '.') . "</td>";
    $tabla .= "</tr>";
    $orden++;
}
$total = $subtotal;
$dompdf = new Dompdf();

$reporte = file_get_contents('r_orden_compra.php');
$reporte = str_replace('${FECHA_EMISION}',  date('d/m/Y'), $reporte);
$reporte = str_replace('${NRO_ORDEN}', $proveedor[0]['ped_cod'], $reporte);
$reporte = str_replace('${PROVEEDOR}', $proveedor[0]['prv_razonsocial'], $reporte);
$reporte = str_replace('${RUC_PROVEEDOR}', $proveedor[0]['prv_ruc'], $reporte);
$reporte = str_replace('${DIRE_PROVEEDOR}', $proveedor[0]['prv_direccion'], $reporte);
$reporte = str_replace('${TELE_PROVEEDOR}', $proveedor[0]['prv_telefono'], $reporte);
$reporte = str_replace('${PRODUCTOS}', $tabla, $reporte);
$reporte = str_replace('${SUBTOTAL}', number_format($subtotal, 0, ',', '.'), $reporte);
$reporte = str_replace('${TOTAL}', number_format($total, 0, ',', '.'), $reporte);
$dompdf->loadHtml($reporte);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream("factura.pdf", array("Attachment" => 0));