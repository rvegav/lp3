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
        <title>Modulo de produccion</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <?php
        #session_start(); /* Reanudar sesion */
        require 'menu/css_lte.ctp';
        ?><!--ARCHIVOS CSS-->

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
            <?php require 'menu/header_lte.ctp'; ?><!--CABECERA PRINCIPAL-->
            <?php require 'menu/toolbar_lte.ctp'; ?><!--MENU PRINCIPAL-->
        <div class="content-wrapper">
                <div class="content">
                
                    <div class="content">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <i class="fa fa-plus"></i>
                                        <h3 class="box-title">Lista de produccion</h3>
                                        <a href="pedventas_add.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-plus"></i></a>                          
                                    </div>                                
                                    <div class="box-body">
                                <?php if (!empty($_SESSION['mensaje'])) { ?>
                                <div class="alert alert-danger" role="alert" id="mensaje">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                    <?php echo $_SESSION['mensaje'];
                                    $_SESSION['mensaje'] = '';?>
                                </div>        
                                <?php } ?>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <form method="post" accept-charset="utf-8" class="form-horizontal">
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                                                <div class="input-group custom-search-form">
                                                                    <input type="search" class="form-control" name="buscar" placeholder="Ingrese el valor a buscar..." autofocus="">    
                                                                    <span class="input-group-btn">
                                                                        <button type="submit" class="btn btn-primary btn-flat"
                                                                        data-title = "Buscar" rel="tooltip" data-placement="bottom">
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </span>
                                                                </div>                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>                                              
                                                <?php $listaProduccion = consultas::get_datos("select * from pedido_producto pepr
                                                join articulo arti on arti.art_cod = pepr.pepr_prod_cod");
                                                if (!empty($listaProduccion)) { ?>
                                                    <!-- crear tabla con datos -->
                                                    <div class="table-responsive">
                                                        <table class="table table-condensed table-striped table-hover table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Producto</th>
                                                                    <th>Cantidad</th>
                                                                    <th>Fecha de inicio</th>
                                                                    <th>Material</th>
                                                                    <th>Presupuesto</th>
                                                                    <th>Cortes</th>
                                                                    <th>Confeccion y ensamble</th>
                                                                    <th>Acabado</th>
                                                                    <th>Fecha fin</th>
                                                                    <th class="text-center">Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>                                 
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php } else { ?>
                                                    <!--mostrar mensaje de alerta tipo info -->
                                                    <div class="alert alert-info flat">
                                                        <i class="fa fa-info-circle"></i> No se han registrado pedidos...
                                                    </div>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
<?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->  
<?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
<?php// require 'menu/navbar_lte.ctp'; ?><!--ARCHIVOS JS-->
<script>
    $("#mensaje").delay(4000).slideUp(200,function() {
    $(this).alert('close');
    });
</script>
<script>
   
</script>

    </body>
</html>