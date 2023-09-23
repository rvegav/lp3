<?php
session_start();
#require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
// require 'clases/conexion.php';

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
            <?php 
                $presupuesto = consultas::get_datos('Select * from presupuestos where pres_ped_cod = '. $_REQUEST['vped_cod']);
                if (!$presupuesto) {
                    $sql = "insert into presupuestos (pres_cod, pres_ped_cod, pres_fecha_creacion) "
                         . "values((select coalesce(max(pres_cod),0)+1 from presupuestos),".$_REQUEST['vped_cod'].", TO_DATE('".date('Y-m-d')."','YYYY-MM-DD'))";
                    consultas::ejecutar_sql($sql);
                    $presupuesto =consultas::get_datos('Select * from presupuestos where pres_ped_cod = '. $_REQUEST['vped_cod']);
                }

             ?>
            <div class="content-wrapper">
                <div class="content">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <i class="fa fa-plus"></i>
                                    <h3 class="box-title">Agregar Presupuesto al Pedido</h3>
                                    <a href="aprobacion_presupuesto_index.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-arrow-left"></i></a>                                                                                                                
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
                                            <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Fecha Aprobacion</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($presupuesto as $presu) { ?>

                                                                <tr>
                                                                    <td><?php echo $presu['pres_cod']; ?></td>
                                                                    <td><?php echo $presu['pres_fecha_aprobacion']; ?></td>
                                                                    <?php if ($presu['pres_estado']=='P'): ?>
                                                                        <?php $estado = 'PENDIENTE' ?>
                                                                    <?php elseif($presu['pres_estado']=='A'): ?>
                                                                        <?php $estado = 'APROBADO' ?>
                                                                    <?php else: ?>
                                                                        <?php $estado = 'DENEGADO' ?>
                                                                    <?php endif ?>
                                                                    <td><?php echo $estado; ?></td>
                                                                </tr>
                                                        <?php } ?>                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php $pedidos = consultas::get_datos("select * from v_pedido_cabcompra where id_sucursal =".$_SESSION['id_sucursal']." and ped_cod=".$_REQUEST['vped_cod']);
                                            if (!empty($pedidos)) { ?>
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Fecha</th>
                                                                <th>Proveedor</th>
                                                                <th>Total</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($pedidos as $pedido) { ?>

                                                                <tr>
                                                                    <td><?php echo $pedido['ped_cod']; ?></td>
                                                                    <td><?php echo $pedido['ped_fecha']; ?></td>
                                                                    <td><?php echo $pedido['proveedor']; ?></td>
                                                                    <td><?php echo number_format($pedido['ped_total_presup'], 0, ",", "."); ?></td>
                                                                    <td><?php echo $pedido['estado']; ?></td>
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
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                             
                                            <?php $pedidosdet = consultas::get_datos("select ped_cod,dep_cod, art_cod,art_descri,mar_descri,ped_cant, tipo_descri,ped_precio, ped_precio_presup, (sum(ped_precio_presup)*ped_cant) as subtotal from v_detalle_pedcompra where ped_cod =".$pedidos[0]['ped_cod']." group by ped_cod,dep_cod,art_cod,art_descri,mar_descri,ped_cant,tipo_descri, ped_precio, ped_precio_presup");
                                            if (!empty($pedidosdet)) { ?>
                                                <div class="box-header">
                                                    <i class="fa fa-list"></i>
                                                    <h3 class="box-title">Detalle Items</h3>
                                                </div>                                             
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Descripción</th>
                                                                <th>Cantidad</th>
                                                                <th>Precio Referencial</th>
                                                                <th>Precio Presupuestado</th>
                                                                <th>Impuesto</th>
                                                                <th>Subtotal</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($pedidosdet as $det) { ?>
                                                                <tr>
                                                                    <?php $detalle_presupuesto = consultas::get_datos('Select * from detalle_presupuesto where depr_pres_cod = '.$presupuesto[0]['pres_cod'].' and depr_art_cod = '. $det['art_cod']) ?>
                                                                    <?php if (!$detalle_presupuesto){ ?>
                                                                        <?php 
                                                                            $sql = "insert into detalle_presupuesto (depr_cod, depr_pres_cod, depr_art_cod, depr_precio, depr_cant) "
                                                                                 . "values((select coalesce(max(depr_cod),0)+1 from detalle_presupuesto),".$presupuesto[0]['pres_cod'].", ".$det['art_cod'].",".$det['ped_precio_presup'].",".$det['ped_cant'].")";
                                                                            consultas::ejecutar_sql($sql);
                                                                         ?>
                                                                    <?php }else{ ?>
                                                                        <?php 
                                                                            $sql = "update detalle_presupuesto set depr_precio = ".$det['ped_precio_presup'].", depr_cant = ".$det['ped_cant']." where depr_art_cod = ".$det['art_cod'] ." and depr_pres_cod = ".$presupuesto[0]['pres_cod'];
                                                                            consultas::ejecutar_sql($sql);
                                                                         ?>
                                                                    <?php } ?>
                                                                    <td><?php echo $det['art_cod']; ?></td>
                                                                    <td><?php echo $det['art_descri']." ".$det['mar_descri']; ?></td>
                                                                    <td><?php echo $det['ped_cant']; ?></td>
                                                                    <td><?php echo number_format($det['ped_precio'], 0, ",", "."); ?></td>
                                                                    <td><?php echo number_format($det['ped_precio_presup'], 0, ",", "."); ?></td>
                                                                    <td><?php echo $det['tipo_descri']; ?></td>
                                                                    <td><?php echo number_format($det['subtotal'], 0, ",", "."); ?></td>
                                                                    <td class="text-center">
                                                                        <a onclick="edit(<?php echo $det['ped_cod']; ?>,<?php echo $det['art_cod']; ?>,<?php echo $det['dep_cod']; ?>)"
                                                                           class="btn btn-warning btn-sm" role="button" data-title="Editar" rel="tooltip" data-placement="top"
                                                                           data-toggle="modal" data-target="#editar"><i class="fa fa-edit"></i>
                                                                        </a>
                                                                        <a onclick="quitar(<?php echo "'".$det['ped_cod']."_".$det['dep_cod']."_".$det['art_cod']
                                                                           ."_".$det['art_descri']."'";?>)" data-toggle="modal" data-target="#borrar" 
                                                                           class="btn btn-danger btn-sm" role="button" data-title="Quitar" rel="tooltip" data-placement="top"> 
                                                                            <i class="fa fa-trash"></i></a>                                                                                                                                                                                                                                                                                          
                                                                    </td>
                                                                </tr>
                                                        <?php } ?>                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <!--mostrar mensaje de alerta tipo info -->
                                                <div class="alert alert-info flat">
                                                    <i class="fa fa-info-circle"></i> El pedido aún no tiene detalles cargados...
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
        <!-- MODAL EDITAR DETALLE -->
        <div class="modal fade" id="editar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content" id="detalles">
                </div>
            </div>            
        </div>                
        <!-- FIN MODAL EDITAR DETALLE -->          
        </div>                  
<?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
<script>
    $("#mensaje").delay(4000).slideUp(200,function() {
    $(this).alert('close');
    });
</script>

<script>
      function edit(id,art,dep){        
        $.ajax({
            type    : "GET",
            url     : "/lp3/prescompra_dedit.php/?vped_cod="+id+"&vart_cod="+art+"&vdep_cod="+dep,
            cache   : false,
            beforeSend:function(){
                $("#detalles").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>')
            },
            success:function(data){
                $("#detalles").html(data)
            }    
                
        });
    }

</script>
    </body>
</html>