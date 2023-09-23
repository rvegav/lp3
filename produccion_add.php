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
        <title>Proceso de produccion</title>
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
                                                 <div class="row">
                                                 	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                 		<div class="card">
                                                 			<div class="card-header">
                                                 				<h5 class="card-title">Lista de productos</h5>
                                                 			</div>
                                                 			<div class="card-body">
                                                 				
                                                 			</div>
                                                 		</div>
                                                 	</div>
                                                 </div>                                             
                                                <?php $curriculum = consultas::get_datos("select * from v_pedido_cabventa where id_sucursal =".$_SESSION['id_sucursal']." and (ped_cod||''||clientes) ilike '%".(isset($_REQUEST['buscar'])? $_REQUEST['buscar']:"")."%' order by ped_cod");
                                                if (!empty($curriculum)) { ?>
                                                    <!-- crear tabla con datos -->
                                                    <div class="table-responsive">
                                                        <table class="table table-condensed table-striped table-hover table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Fecha</th>
                                                                    <th>Cliente</th>
                                                                    <th>Total</th>
                                                                    <th>Estado</th>
                                                                    <th class="text-center">Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach ($curriculum as $curriculum) { ?>
                                                                    <tr>
                                                                        <td><?php echo $curriculum['ped_cod']; ?></td>
                                                                        <td><?php echo $curriculum['ped_fecha']; ?></td>
                                                                        <a href="clientes_control.php"></a>
                                                                        <td><?php echo $curriculum['clientes']; ?></td>
                                                                        <td><?php echo number_format($curriculum['ped_total'], 0, ",", "."); ?></td>
                                                                        <td><?php if ($curriculum['estado']==='ANULADO') { echo "<spam style='color:red;'>".$curriculum['estado']."</spam>";}else{ echo $curriculum['estado'];}; ?></td>
                                                                        <td class="text-center">
                                                                            <?php if ($curriculum['estado']==='PENDIENTE') { ?>
                                                                            <a href="pedventas_det.php?vped_cod=<?php echo $curriculum['ped_cod']; ?>" 
                                                                               class="btn btn-success btn-sm" role="button" data-title="Detalles" rel="tooltip" data-placement="top">
                                                                                <i class="fa fa-list"></i>
                                                                            </a>
                                                                            <a href="pedventas_edit.php?vped_cod=<?php echo $curriculum['ped_cod']; ?>" 
                                                                               class="btn btn-warning btn-sm" role="button" data-title="Editar" rel="tooltip" data-placement="top">
                                                                                <i class="fa fa-edit"></i>
                                                                            </a>
                                                                            <a onclick="borrar(<?php echo "'".$curriculum['ped_cod']."_".$curriculum['clientes']."_".$curriculum['ped_fecha']."'";?>)" 
                                                                               data-toggle="modal" data-target="#borrar" class="btn btn-danger btn-sm" role="button" 
                                                                               data-title="Anular" rel="tooltip" data-placement="top"> <i class="fa fa-close"></i></a>                                                                          
                                                                            <?php }?>                                                                        
                                                                            <a href="/lp3/pedventas_print.php?vped_cod=<?php echo $curriculum['ped_cod'];?>" 
                                                                               class="btn btn-primary btn-sm" role="button" target="print"
                                                                               data-title="Imprimir" rel="tooltip" data-placement="top">
                                                                                <i class="fa fa-print"></i></a>                                                                         
                                                                                                                                              
                                                                        </td>
                                                                    </tr>
                                                            <?php } ?>                                                            
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