<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
#session_start();
$detalle = consultas::get_datos("select * from v_detalle_pedventa where ped_cod =" . $_REQUEST['vped_cod'] . " and dep_cod=" . $_REQUEST['vdep_cod'] . " and art_cod =" . $_REQUEST['vart_cod'])
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Detalle de Ventas</h4>
</div>
<form action="ventas_dcontrol.php" method="post" accept-charset="utf-8" class="form-horizontal">
    <div class="modal-body">
        <input type="hidden" name="accion" value="1" />
        <input type="hidden" name="vven_cod" value="<?php echo $_REQUEST['vven_cod'] ?>" />
        <div class="form-group">
            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Deposito:</label>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                <input type="hidden" name="vdep_cod" class="form-control" value="<?php echo $detalle[0]['dep_cod'] ?>" />
                <input type="text" class="form-control" value="<?php echo $detalle[0]['dep_descri'] ?>" disabled="" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Articulo:</label>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                <input type="hidden" name="vart_cod" class="form-control" value="<?php echo $detalle[0]['art_cod'] . "_" . $detalle[0]['ped_precio'] ?>" />
                <input type="text" class="form-control" value="<?php echo $detalle[0]['art_descri'] ?>" disabled="" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cantidad:</label>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                <input type="number" name="vven_cant" class="form-control" min="1" value="<?php echo $detalle[0]['ped_cant'] ?>" required="" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Precio:</label>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                <input type="number" name="vven_precio" class="form-control" min="1" required="" value="<?php echo $detalle[0]['ped_precio'] ?>" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> Cerrar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar</button>
    </div>
</form>