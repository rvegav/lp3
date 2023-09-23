<?php
session_start();
require 'acceso_bloquear_compras.php';
            #require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
?>
<?php if ($_REQUEST['accion']==1): ?>

    <label>Orden de Compras</label>
    <?php $compras = consultas::get_datos("select com_cod, to_char(com_fecha, 'dd/mm/YYYY') as com_fecha from compras where prv_cod=".$_REQUEST['vprv_cod']." and com_estado_pago ='I'"); ?>
    <select class="form-control select2" name="vped_cod" id="compra_select" onchange="detalles()">
        <?php if (!empty($compras)) { ?>
            <option value="">Seleecione una orden de compra</option>
            <?php foreach ($compras as $compra) {
                ?>
                <option value="<?php echo $compra['com_cod']; ?>"><?php echo "N°: " . $compra['com_cod'] . " - FECHA: " . $compra['com_fecha']; ?></option>
            <?php }
        } else {
            ?>
            <option value="">El Proveedor no tiene cuentas pendientes de pago</option>
        </select> 

    <?php } ?>    
<?php endif ?>
<?php if ($_REQUEST['accion']==2): ?>
  <label>Articulos</label>
  <?php $articulos = consultas::get_datos("select  * from v_articulo"); ?>
  <select class="form-control select2" name="vped_cod" id="articulo_select" >
    <?php if (!empty($articulos)) { ?>
        <option value="">Seleecione un Articulo</option>
        <?php foreach ($articulos as $articulo) {?>
            <option value="<?php echo $articulo['art_cod']; ?>"><?php echo $articulo['art_descri'].'- Marca: '.  $articulo['mar_descri']?></option>
        <?php }
    } else {?>
        <option value="">Error</option>
    <?php } ?>
</select> 

<?php endif ?>

