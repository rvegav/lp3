<?php
        session_start();
        require 'acceso_bloquear_compras.php';
        require 'acceso_bloquear_ventas.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="shortcut icon" type="image/x-icon" href="/lp3/img/icono-negro.png">
        <title>LP3</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <?php 
        #session_start();/*Reanudar sesion*/
        require 'menu/css_lte.ctp'; ?><!--ARCHIVOS CSS-->

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php require 'menu/header_lte.ctp'; ?><!--CABECERA PRINCIPAL-->
            <?php require 'menu/toolbar_lte.ctp';?><!--MENU PRINCIPAL-->
            <div class="content-wrapper">
                <div class="content">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-warning">
                                <div class="box-header">
                                    <i class="ion ion-edit"></i>
                                    <h3 class="box-title">Editar Clientes</h3>
                                    <a href="clientes_index.php" class="btn btn-warning pull-right btn-sm" role="buttom" data-title ="Volver"
                                    rel="tooltip" data-placement="top">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                                <form action="clientes_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <?php $clientes = consultas::get_datos("select * from clientes "
                                                . "where cli_cod =".$_REQUEST['vcli_cod']);
                                        ?>
                                        <input type="hidden" name="accion" value="2"/>
                                        <input type="hidden" name="vcli_cod" value="<?php echo $clientes[0]['cli_cod'];?>"/>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-2 col-md-2 col-xs-2">CI:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                <input type="number" name ="vcli_ci" class="form-control" required="" min="1" 
                                                       value="<?php echo $clientes[0]['cli_ci'];?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-2 col-md-2 col-xs-2">Nombres:</label>
                                            <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                                <input type="text" name ="vcli_nombre" class="form-control" required="" 
                                                       value="<?php echo $clientes[0]['cli_nombre'];?>"/>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-2 col-md-2 col-xs-2">Apellidos:</label>
                                            <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                                <input type="text" name ="vcli_apellido" class="form-control" required="" 
                                                       value="<?php echo $clientes[0]['cli_apellido'];?>"/>
                                            </div>
                                        </div>                                             
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-2 col-md-2 col-xs-2">Telefóno:</label>
                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-8">
                                                <input type="text" name ="vcli_telefono" class="form-control" 
                                                       value="<?php echo $clientes[0]['cli_telefono'];?>"/>
                                            </div>
                                        </div>                                         
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-2 col-md-2 col-xs-2">Dirección:</label>
                                            <div class="col-lg-8 col-sm-8 col-md-8 col-xs-8">
                                                <textarea type="text" name ="vcli_direcc" class="form-control" rows="4"><?php echo $clientes[0]['cli_direcc'];?></textarea>
                                            </div>
                                        </div>                                           
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-warning pull-right">
                                            <span class="glyphicon glyphicon-edit"></span> Actualizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>
                  <?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->  
            </div>                  
        <?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
    </body>
</html>
