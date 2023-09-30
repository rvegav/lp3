<?php
session_start();
#require 'acceso_bloquear_compras.php';
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
                                <h3 class="box-title">Agregar Detalle Pedido</h3>
                                <a href="pedcompras_index.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-arrow-left"></i></a>                                                                                                                
                            </div>                                
                            <div class="box-body">
                                <?php if (!empty($_SESSION['correcto'])) { ?>
                                    <div class="alert alert-success" role="alert" id="mensaje">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        <?php echo $_SESSION['correcto'];
                                        $_SESSION['correcto'] = '';?>
                                    </div>        
                                <?php } ?>
                                <?php if (!empty($_SESSION['error'])) { ?>
                                    <div class="alert alert-danger" role="alert" id="mensaje">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        <?php echo $_SESSION['error'];
                                        $_SESSION['error'] = '';?>
                                    </div>        
                                <?php } ?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                             
                                        <?php $ordenes = consultas::get_datos("select * from orden_produccion where orpr_id=".$_REQUEST['vorpr_id']);
                                        if (!empty($ordenes)) { ?>
                                            <!-- crear tabla con datos -->
                                            <div class="table-responsive">
                                                <table class="table table-condensed table-striped table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Fecha</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($ordenes as $orden) { ?>
                                                            <tr>
                                                                <td><?php echo $orden['orpr_id']; ?></td>
                                                                <td><?php echo $orden['orpr_fecha_pedido']; ?></td>
                                                                <?php 
                                                                if ($orden['orpr_estado']=='P'){
                                                                    $estado ='PENDIENTE';
                                                                }else{
                                                                    $estado = 'APROBADO';
                                                                }
                                                                ?>
                                                                <td><?php echo $estado; ?></td>
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
                                        <?php $orden_prod_dets = consultas::get_datos("select * from detalle_orden_prod join articulo on deor_art_id = art_cod where deor_orpr_id =".$ordenes[0]['orpr_id']);
                                        if (!empty($orden_prod_dets)) { ?>
                                            <div class="box-header">
                                                <i class="fa fa-list"></i>
                                                <h3 class="box-title">Detalle Items</h3>
                                            </div>                                             
                                            <!-- crear tabla con datos -->
                                            <div class="table-responsive">
                                                <table class="table table-condensed table-striped table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Articulo</th>
                                                            <th>Cantidad</th>
                                                            <th class="text-center">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($orden_prod_dets as $det) { ?>
                                                            <tr>
                                                                <td><?php echo $det['art_descri']; ?></td>
                                                                <td><?php echo $det['deor_cantidad']; ?></td>
                                                                <td class="text-center">
                                                                    <a onclick="edit(<?php echo $det['deor_id']; ?>, <?php echo $det['deor_orpr_id'] ?>)"
                                                                     class="btn btn-warning btn-sm" role="button" data-title="Editar" rel="tooltip" data-placement="top"
                                                                     data-toggle="modal" data-target="#editar"><i class="fa fa-edit"></i>
                                                                 </a>
                                                                 <a onclick="quitar(<?php echo $det['deor_id']; ?>)" data-toggle="modal" data-target="#borrar" 
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
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                    <form action="orden_produccion_control.php" method="get" accept-charset="utf-8" class="form-horizontal">
                                        <div class="box-body">
                                            <input type="hidden" name="accion" value="4"/>     
                                            <input type="hidden" name="vorpr_id" value="<?php echo $ordenes[0]['orpr_id']?>"/>     
                                            <div class="form-group">
                                                <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Articulo:</label>
                                                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                    <?php $articulos= consultas::get_datos("select * from articulo");?>
                                                    <select class="form-control select2" name="vart_cod"  id="articulo">
                                                        <option value="">Seleccionar</option>
                                                        <?php foreach ($articulos as $articulo): ?>
                                                            <option value="<?php echo $articulo['art_cod'] ?>"><?php echo $articulo['art_descri'] ?></option>
                                                        <?php endforeach ?>
                                                    </select>                                                                                                                                               
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cantidad:</label>
                                                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                    <input type="number" name ="vart_cant" class="form-control" min="1" value="1" required=""/>
                                                </div>
                                            </div>                                             
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary pull-right">
                                                <span class="glyphicon glyphicon-plus"></span> Agregdar
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
    function quitar(id){
        $("#si").attr('href','pedcompras_dcontrol.php?vped_cod='+dat[0]+'&vdep_cod='+dat[1]+'&vart_cod='+dat[2]+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea quitar el articulo<i><strong> '
            +dat[3]+'</strong> del pedido</i>?');
    };    
    

</script>
<script>
  function edit(id, orpr){        
    $.ajax({
        type    : "GET",
        url     : "orden_produccion_detalle_edit.php/?vdeor_id="+id+"&vorpr_id="+orpr,
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