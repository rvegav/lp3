<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
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
        #session_start(); /* Reanudar sesion */
        require 'menu/css_lte.ctp';
        ?><!--ARCHIVOS CSS-->

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php require 'menu/header_lte.ctp'; ?><!--CABECERA PRINCIPAL-->
            <?php require 'menu/toolbar_lte.ctp'; ?><!--MENU PRINCIPAL-->
            <div class="content-wrapper">
                <div class="content">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <i class="ion ion-clipboard"></i>
                                    <h3 class="box-title">Informe de Pedido de Ventas</h3>
                                </div>                                
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?php $opcion = "1";
                                                    if (isset($_REQUEST['opcion'])) {
                                                       $opcion = $_REQUEST['opcion'];
                                                   }
                                            ?>
                                            <form action="pedventas_print.php" method="GET" accept-charset="utf-8" class="form-horizontal">
                                                <input type="hidden" name="opcion" value="<?php echo $opcion;?>"/>
                                                <div class="box-body">
                                                    <div class="col-md-4">
                                                        <div class="panel panel-primary">
                                                            <div class="panel-heading text-center">
                                                                <strong>Opciones</strong>
                                                            </div>  
                                                            <div class="panel-body">
                                                                <div class="list-group">
                                                                    <a href="pedventas_rpt.php?opcion=1" class="list-group-item"> Por Fecha</a>
                                                                    <a href="pedventas_rpt.php?opcion=2" class="list-group-item"> Por Cliente</a>
                                                                    <a href="pedventas_rpt.php?opcion=3" class="list-group-item"> Por Articulo</a>
                                                                    <a href="pedventas_rpt.php?opcion=4" class="list-group-item"> Por Sucursal</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>    
                                                    <div class="col-md-8">
                                                        <div class="panel panel-primary">
                                                            <div class="panel-heading text-center">
                                                                <strong>Filtros</strong>
                                                            </div>
                                                            <div class="panel-body">
                                                                <?php
                                                                 switch ($opcion) {
                                                                    case 1: ?>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-2"> Desde</label>
                                                                    <div class="col-md-6">
                                                                        <input type="date" class="form-control" name="vdesde"/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-2"> Hasta</label>
                                                                    <div class="col-md-6">
                                                                        <input type="date" class="form-control" name="vhasta"/>
                                                                    </div>
                                                                </div>                                                                
                                                            <?php 
                                                             break;
                                                                    case 2: 
                                                                        $clientes = consultas::get_datos("select * from clientes where cli_cod in(select cli_cod from pedido_cabventa)");
                                                                        ?>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-2">Cliente:</label>
                                                                    <div class="col-md-6">
                                                                        <select class="form-control select2" name="vcliente">
                                                                            <?php foreach ($clientes as $cli) { ?>
                                                                            <option value="<?php echo $cli['cli_cod'];?>"><?php echo $cli['cli_nombre']." ".$cli['cli_apellido'];?></option>
                                                                                <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <?php 
                                                             break;
                                                                    case 3: 
                                                                        $articulos = consultas::get_datos("select * from v_articulo where art_cod in(select art_cod from detalle_pedventa)");
                                                                        ?>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-2">Articulos:</label>
                                                                    <div class="col-md-6">
                                                                        <select class="form-control select2" name="varticulo">
                                                                            <?php foreach ($articulos as $art) { ?>
                                                                            <option value="<?php echo $art['art_cod'];?>"><?php echo $art['art_descri']." ".$art['mar_descri'];?></option>
                                                                                <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <?php 
                                                             break;
                                                                    case 4: 
                                                                        $sucursales = consultas::get_datos("select * from sucursal where id_sucursal in(select id_sucursal from pedido_cabventa)");
                                                                        ?>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-2">Sucursal:</label>
                                                                    <div class="col-md-6">
                                                                        <select class="form-control select2" name="vsucursal">
                                                                            <?php foreach ($sucursales as $suc) { ?>
                                                                            <option value="<?php echo $suc['id_sucursal'];?>"><?php echo $suc['suc_descri'];?></option>
                                                                                <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                 <?php break;?>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-success pull-right">
                                                        <i class="fa fa-print"></i> Imprimir
                                                    </button>
                                                </div>
                                            </form> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->          
        </div>                  
<?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
<script>
    $("#mensaje").delay(4000).slideUp(200,function() {
    $(this).alert('close');
    });
</script>
    </body>
</html>


