<?php
session_start();
#require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
#session_start();
$detalle = consultas::get_datos("select deor_cantidad as cantidad from detalle_orden_prod where deor_id = ".$_REQUEST['vdeor_id'])
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title"><i class="fa fa-edit"></i> Editar</h4>
</div>
<form action="orden_produccion_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
    <div class="modal-body">
        <input type="hidden" name="accion" value="5" /> 
        <input type="hidden" name="vdeor_id" value="<?php echo $_REQUEST['vdeor_id'] ?>" />
        <input type="hidden" name="vorpr_id" value="<?php echo $_REQUEST['vorpr_id'] ?>" />
        <div class="form-group">
            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Cantidad:</label>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                <input type="text" class="form-control" name ="vart_cantidad" value="<?php echo $detalle[0]['cantidad'] ?>"  />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> Cerrar</button>
        <button type="submit" class="btn btn-warning"><i class="fa fa-edit"></i> Actualizar</button>
    </div>
</form>