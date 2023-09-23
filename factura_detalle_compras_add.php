<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
if ($_REQUEST['accion']==1) {
	// code...
$sql = "select dc.art_cod, dc.com_precio, dc.com_cant, sum(dc.com_precio * dc.com_cant) as monto_total, a.art_descri  from compras c join detalle_compras dc on dc.com_cod = c.com_cod join articulo a on dc.art_cod = a.art_cod where c.com_cod = ".$_REQUEST['vcom_cod']." group by dc.art_cod, a.art_descri, dc.com_precio, dc.com_cant";
$detalles = consultas::get_datos($sql);

if ($detalles) {
	$contador =  $_REQUEST['vitem'];
	?>
	<?php foreach ($detalles as $detalle): ?>
		<tr>
			<!-- <td><?php echo $contador ?></td> -->
			<td><?php echo $detalle['art_descri'] ?></td>
			<td><?php echo $detalle['com_cant'] ?></td>
			<td><?php echo number_format($detalle['com_precio'], 0, '', '.' )?></td>
			<td><?php echo number_format($detalle['monto_total'], 0, '', '.' )?></td>
		</tr>
		$contador++;
	<?php endforeach ?>
<?php 
}else{
	echo "Sin datos";
}
}elseif ($_REQUEST['accion']==2) {
	$sql = "select * from v_articulo where art_cod = ".$_REQUEST['vart_cod'];
	$articulo = consultas::get_datos($sql);
	echo $articulo[0]['art_descri'];	
}
?>