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
                                    <i class="fa fa-plus"></i>
                                    <h3 class="box-title">Pedido de Venta</h3>
                                    <a href="pedventas_add.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-plus"></i></a>                                                                                                                
                                </div>                                
                                <div class="box-body">
                           <?php if (!empty($_SESSION['mensaje'])) { ?>
                            <div class="alert alert-success" role="alert" id="mensaje">
                                <span class="glyphicon glyphicon-info-sign"></span>
                                <?php echo $_SESSION['mensaje'];
                                $_SESSION['mensaje'] = '';?>
                            </div>        
                            <?php }else if(!empty($_SESSION['mensajeValid'])){ ?>
                                <div class="alert alert-danger" role="alert" id="mensaje">
                                <span class="glyphicon glyphicon-info-sign"></span>
                                <?php echo $_SESSION['mensajeValid'];
                                $_SESSION['mensajeValid'] = '';?>
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
                                            <?php $pedidos = consultas::get_datos("select * from v_pedido_cabventa where id_sucursal =".$_SESSION['id_sucursal']." and (ped_cod||''||clientes) ilike '%".(isset($_REQUEST['buscar'])? $_REQUEST['buscar']:"")."%' order by ped_cod");
                                            if (!empty($pedidos)) { ?>
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
                                                        <?php foreach ($pedidos as $pedido) { ?>
                                                                <tr>
                                                                    <td><?php echo $pedido['ped_cod']; ?></td>
                                                                    <td><?php echo $pedido['ped_fecha']; ?></td>
                                                        <a href="clientes_control.php"></a>
                                                                    <td><?php echo $pedido['clientes']; ?></td>
                                                                    <td><?php echo number_format($pedido['ped_total'], 0, ",", "."); ?></td>
                                                                    <td><?php if ($pedido['estado']==='ANULADO') { echo "<spam style='color:red;'>".$pedido['estado']."</spam>";}else{ echo $pedido['estado'];}; ?></td>
                                                                    <td class="text-center">
                                                                        <?php if ($pedido['estado']==='PENDIENTE') { ?>
                                                                        <a href="pedventas_det.php?vped_cod=<?php echo $pedido['ped_cod']; ?>" 
                                                                           class="btn btn-success btn-sm" role="button" data-title="Detalles" rel="tooltip" data-placement="top">
                                                                            <i class="fa fa-list"></i>
                                                                        </a>
                                                                        <a href="pedventas_edit.php?vped_cod=<?php echo $pedido['ped_cod']; ?>" 
                                                                           class="btn btn-warning btn-sm" role="button" data-title="Editar" rel="tooltip" data-placement="top">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                        <a onclick="borrar(<?php echo "'".$pedido['ped_cod']."_".$pedido['clientes']."_".$pedido['ped_fecha']."'";?>)" 
                                                                           data-toggle="modal" data-target="#borrar" class="btn btn-danger btn-sm" role="button" 
                                                                           data-title="Anular" rel="tooltip" data-placement="top"> <i class="fa fa-close"></i></a> 

                                                                             <!-- <a onclick="confirmar(<?php echo "'".$pedido['ped_cod']."','".$pedido['clientes']."','".$pedido['ped_fecha']."'";?>)" 
                                                                            class="btn btn-info btn-sm" role="button" 
                                                                           data-title="Confirmar" rel="tooltip" data-placement="top"> <i class="fa fa-check"></i></a>  -->                                                                      
                                                                        <?php }?>                                                                        
                                                                        <a href="/lp3/pedventas_print.php?vped_cod=<?php echo $pedido['ped_cod'];?>" 
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
        <?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->  
        <!-- MODAL CARGO BORRAR -->
        <div class="modal fade" id="borrar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                        <h4 class="modal-title"><i class="fa fa-trash"></i>Atenci&oacute;n</h4>
                    </div>                    
                        <div class="modal-body">
                            <div class="alert alert-danger" id="confirmacion"></div>
                        </div>
                        <div class="modal-footer">                            
                            <a id="si" role="button" class="btn btn-danger"><i class="fa fa-check"></i> SI</a>
                            <button data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
                        </div>
                </div>
            </div>            
        </div>   
        <!-- FIN MODAL CARGO BORRAR -->                
         <!--   <div class="modal fade" id="confirmar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                        <h4 class="modal-title"><i class="fa fa-trash"></i>Atenci&oacute;n</h4>
                    </div>                    
                        <div class="modal-body">
                            <div class="alert alert-success" id="confirmacionPed"></div>
                        </div>
                        <div class="modal-footer">                            
                            <a id="siConfirmo" role="button" class="btn btn-success"><i class="fa fa-check"></i> SI</a>
                            <button data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
                        </div>
                </div>
            </div>            
        </div> -->              
        </div>                  
<?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
<script>
    $("#mensaje").delay(4000).slideUp(200,function() {
    $(this).alert('close');
    });
</script>
<script>
    function borrar(datos){
        var dat = datos.split('_');
        $("#si").attr('href','pedventas_control.php?vped_cod='+dat[0]+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea anular el pedido N°<i><strong>'
                +dat[0]+'</strong> de fecha <i><strong>'+dat[2]+'</strong> y cliente <i><strong>'+dat[1]+'</strong></i>?');
    };    
    
    //   function confirmar(id,cliente,fecha){
        
    //     //alert(cliente);
    //     $('#confirmar').modal('show');
    //     $("#siConfirmo").attr('href','pedventas_control.php?vped_cod='+id+'&accion=5');
    //     $("#confirmacionPed").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea confirmar el pedido N°<i><strong>'
    //              +id+'</strong> de fecha <i><strong>'+fecha+'</strong> y cliente <i><strong>'+cliente+'</strong></i>?');
    // };    
    
</script>
    </body>
</html>