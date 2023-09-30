<?php
        session_start();
        #require 'acceso_bloquear_compras.php';
        // require 'acceso_bloquear_ventas.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="shortcut icon" type="image/x-icon" href="/lp3/img/icono-negro.png">
        <title>LP3</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <?php 
        #session_start();
        require 'menu/css_lte.ctp'; ?>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php require 'menu/header_lte.ctp'; ?>
            <?php require 'menu/toolbar_lte.ctp';?>
            <div class="content-wrapper">
                <div class="content">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <i class="ion ion-plus"></i>
                                    <h3 class="box-title">Agregar Orden de Produccion</h3>
                                    <a href="pedcompras_index.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Volver"
                                    rel="tooltip" data-placement="top">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                                <form action="orden_produccion_control.php" method="get" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="accion" value="1"/>
                                        <input type="hidden" name="vped_cod" value="0"/> 
                                        <div class="row">
                                            <div class="col-xs-12 col-lg-4 col-md-6">
                                                <?php $fecha = consultas::get_datos("select current_date as fecha");?>
                                                <label>Fecha:</label>
                                                <input type="date" name ="vorden_fecha" class="form-control" required="" value="<?php echo $fecha[0]['fecha']?>" readonly=""/>
                                            </div>
                                            <div class="col-xs-12 col-lg-4 col-md-6">
                                                <label> Nro orden:</label>                         
                                                <?php $orden = consultas::get_datos("select COALESCE(max(orpr_id)+1, 1) as orpr_id from orden_produccion");?>
                                                <input type="text" name="orpr_id" class="form-control" value="<?php echo $orden[0]['orpr_id']?>" readonly>  

                                            </div>
                                        </div>                                             
                                        
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-right">
                                            <span class="glyphicon glyphicon-floppy-disk"></span> Registrar
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


