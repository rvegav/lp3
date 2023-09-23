<?php require 'clases/conexion.php';
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
$empleado = consultas::get_datos("select * from v_empleado where emp_cod =" . $_REQUEST['vemp_cod']);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title"><i class="fa fa-user-plus"></i> Agregar Usuario</h4>
</div>
<form action="usuarios_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
    <div class="modal-body">
        <input type="hidden" name="accion" value="4" />
        <input type="hidden" name="vemp_cod" value="<?php echo $_REQUEST['vemp_cod'] ?>" />
        <div class="form-group">
            <label class="control-label col-lg-2 col-sm-3 col-md-3 col-xs-2"> Nombres y Empleado:</label>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                <input type="text" class="form-control" value="<?php echo $empleado[0]['emp_nombre'] . " " . $empleado[0]['emp_apellido'] ?>" disabled="" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Cargo:</label>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                <input type="text" class="form-control" value="<?php echo $empleado[0]['car_descri'] ?>" disabled="" />
            </div>
        </div>
        <div class="form-group has-feedback">
            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Usuario:</label>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                <input type="text" class="form-control" name="vusu_nick" required="" minlength="4" placeholder="Ingrese Usuario" />
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
        </div>
        <div class="form-group has-feedback">
            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Contraseña:</label>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                <input type="password" class="form-control" name="vusu_clave" required="" minlength="4" placeholder="Ingrese su clave" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Grupo:</label>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
            <?php $grupos = consultas::get_datos("select * from grupos order by gru_cod"); ?>
            <select class="form-control select2" name="vgru_cod" required="">
                <?php if (!empty($grupos)) {
                    foreach ($grupos as $grupo) { ?>
                        <option value="<?php echo $grupo['gru_cod']; ?>"><?php echo $grupo['gru_nombre']; ?></option>
                    <?php }
                } else { ?>
                    <option value="">Debe insertar al menos una grupo</option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> Cerrar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Crear Usuario</button>
    </div>
</form>