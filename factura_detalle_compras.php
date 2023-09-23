<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
// var_dump($_REQUEST);
// die();
if ($_REQUEST['vaccion']!="2") {
    $detalles = consultas::get_datos("select * from detalle_compras dc join articulo a on a.art_cod = dc.art_cod where dc.com_cod =".$_REQUEST['vcom_cod']." order by 1");
    ?>
    <div class="table-responsive">
        <table class="table table-condensed table-striped table-hover table-bordered">
            <thead>
                <th>Articulo</th>
                <th>Cantidad</th>
                <th>Precio Compra</th>
            </thead>
            <tbody>
                <?php if(!empty($detalles)) {   ?>
                    <?php foreach ($detalles as $detalle) { ?>
                        <tr>
                            <td><?php echo $detalle['art_descri'] ?></td>
                            <td><?php echo $detalle['com_cant'] ?></td>
                            <td><?php echo $detalle['com_precio'] ?></td>
                        </tr>
                    <?php } ?>                                                            
                <?php }else{ ?>
                    <td class="colspam">No hay detalles</td>
                <?php } ?>                                                            



            </tbody>
        </table>
    </div>
    
<?php }else{ 
    $detalles = consultas::get_datos("select c.com_cod, sum(d.com_precio) as monto_total, to_char(com_fecha, 'DD/MM/YYYY') as com_fecha from detalle_factura_compra dc join compras c on c.com_cod = dc.defc_com_cod join detalle_compras d on d.com_cod = c.com_cod  where dc.defc_faco_cod =".$_REQUEST['vfact_cod']." group by c.com_cod" );
?>
    
    <div class="table-responsive">
        <table class="table table-condensed table-striped table-hover table-bordered">
            <thead>
                <th>Nro Compra</th>
                <th>Monto</th>
                <th>Fecha</th>
            </thead>
            <tbody>
                <?php if(!empty($detalles)) {   ?>
                    <?php foreach ($detalles as $detalle) { ?>
                        <tr>
                            <td><?php echo $detalle['com_cod'] ?></td>
                            <td><?php echo $detalle['monto_total'] ?></td>
                            <td><?php echo $detalle['com_fecha'] ?></td>
                        </tr>
                    <?php } ?>                                                            
                <?php }else{ ?>
                    <td class="colspam">No hay detalles</td>
                <?php } ?>                                                            



            </tbody>
        </table>
    </div>

<?php } ?>



