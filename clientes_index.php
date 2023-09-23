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
                            <div class="box box-primary">
                                <div class="box-header">
                                    <i class="ion ion-clipboard"></i>
                                    <h3 class="box-title">Clientes</h3>
                                    <a href="clientes_add.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Agregar"                                       
                                    rel="tooltip" data-placement="top">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <a href="clientes_print.php" class="btn btn-default btn-sm pull-right" role="button" target="print"><i class="fa fa-print"></i></a>                                    
                                </div>
                                <div class="box-body">
                                 <?php if (!empty($_SESSION['mensaje'])) { ?>
                                    <div class="alert alert-danger" id="mensaje">
                                        <span class="glyphicon glyphicon-info-sign"></span> 
                                            <?php echo $_SESSION['mensaje'];
                                            $_SESSION['mensaje'] ='';?>
                                    </div>                                            
                                 <?php }?>
                                 
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?php                                            
                                            $clientes = consultas::get_datos("select * from clientes order by cli_cod");
                                            if (!empty($clientes)) { ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>CI</th>
                                                            <th>Nombres y Apellidos</th>
                                                            <th>Teléfono</th>
                                                            <th>Dirección</th>
                                                            <th class="text-center">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        foreach ($clientes as $cliente) { ?>
                                                        <tr>
                                                            <td data-title="CI">
                                                                <?php echo $cliente['cli_ci'];?>
                                                            </td>
                                                            <td data-title="Nombres y Apellidos">
                                                                <?php echo $cliente['cli_nombre']." ".$cliente['cli_apellido'];?>
                                                            </td>                                                           
                                                            <td data-title="Telefono">
                                                                <?php echo $cliente['cli_telefono'];?>
                                                            </td>  
                                                            <td data-title="Dirección">
                                                                <?php echo $cliente['cli_direcc'];?>
                                                            </td>                                                              
                                                            <td class="text-center">
                                                                <a href="clientes_edit.php?vcli_cod=<?php echo $cliente['cli_cod'];?>" class="btn btn-warning btn-sm" role="buttom" data-title ="Editar"
                                                                   rel="tooltip" data-placement="top">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <a href="clientes_del.php?vcli_cod=<?php echo $cliente['cli_cod'];?>" class="btn btn-danger btn-sm" role="buttom" data-title ="Borrar"
                                                                   rel="tooltip" data-placement="top">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>                                                            
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                           <?php }else{ ?>
                                            <div class="alert alert-info flat">
                                                <span class="glyphicon glyphicon-info-sign"></span> 
                                                No se han registrado clientes...
                                            </div>
                                           <?php  }
                                            ?>
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
