<?php
session_start(); /* Reanudar sesion */
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
                                    <h3 class="box-title">Agregar Detalle Compra</h3>
                                    <a href="compras_index.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-arrow-left"></i></a>                                                                                                                
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
                                            <?php $compras = consultas::get_datos("select * from v_compras where id_sucursal =".$_SESSION['id_sucursal']." and com_cod=".$_REQUEST['vcom_cod']."");
                                            if (!empty($compras)) { ?>
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Fecha</th>
                                                                <th>Proveedor</th>
                                                                <th>Condición</th>
                                                                <th>Total</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($compras as $compra) { ?>
                                                                <tr>
                                                                   <td><?php echo $compra['com_cod']; ?></td>
                                                                    <td><?php echo $compra['com_fecha']; ?></td>
                                                                    <td><?php echo $compra['proveedor']; ?></td>
                                                                    <td><?php echo $compra['tipo_compra']; ?></td>
                                                                    <td><?php echo number_format($compra['com_total'], 0, ",", "."); ?></td>
                                                                    <td><?php echo $compra['com_estado']; ?></td>
                                                                </tr>
                                                        <?php } ?>                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <!--mostrar mensaje de alerta tipo info -->
                                                <div class="alert alert-info flat">
                                                    <i class="fa fa-info-circle"></i> No se han registrado compras...
                                                </div>
                                        <?php } ?>
                                        </div>
                                    </div> 
                                    <?php if($compras[0]['ped_cod']!="0"){ ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                             
                                            <?php $pedidosdet = consultas::get_datos("select * from v_detalle_pedcompra where ped_cod =".$compras[0]['ped_cod'] 
                                                    ." and art_cod not in (select art_cod from detalle_compras where com_cod=".$compras[0]['com_cod'].")");
                                            if (!empty($pedidosdet)) { ?>
                                                <div class="box-header">
                                                    <i class="fa fa-list"></i>
                                                    <h3 class="box-title">Detalle Items del Pedido</h3>
                                                </div>                                             
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Descripción</th>
                                                                <th>Cantidad</th>
                                                                <th>Precio</th>
                                                                <th>Impuesto</th>
                                                                <th>Subtotal</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($pedidosdet as $detp) { ?>
                                                                <tr>
                                                                    <td><?php echo $detp['art_cod']; ?></td>
                                                                    <td><?php echo $detp['art_descri']." ".$detp['mar_descri']; ?></td>
                                                                    <td><?php echo $detp['ped_cant']; ?></td>
                                                                    <td><?php echo number_format($detp['ped_precio'], 0, ",", "."); ?></td>
                                                                    <td><?php echo $detp['tipo_descri']; ?></td>
                                                                    <td><?php echo number_format($detp['subtotal'], 0, ",", "."); ?></td>
                                                                    <td class="text-center">
                                                                        <a onclick="add(<?php echo $compras[0]['com_cod']; ?>,<?php echo $detp['art_cod']; ?>,<?php echo $detp['dep_cod']; ?>,<?php echo $detp['ped_cod']; ?>)"
                                                                           class="btn btn-success btn-sm" role="button" data-title="Editar" rel="tooltip" data-placement="top"
                                                                           data-toggle="modal" data-target="#editar"><i class="fa fa-plus"></i>
                                                                        </a>                                                                                                                                                                                                                                                                                       
                                                                    </td>
                                                                </tr>
                                                        <?php } ?>                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } ?>                                             
                                        </div>
                                    </div>                                     
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                             
                                            <?php $comprasdet = consultas::get_datos("select * from v_detalle_compras where com_cod =".$compras[0]['com_cod']);
                                            if (!empty($comprasdet)) { ?>
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
                                                                <th>Precio</th>
                                                                <th>Impuesto</th>
                                                                <th>Subtotal</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($comprasdet as $det) { ?>
                                                                <tr>
                                                                    <td><?php echo $det['art_cod']; ?></td>
                                                                    <td><?php echo $det['art_descri']." ".$det['mar_descri']; ?></td>
                                                                    <td><?php echo $det['com_cant']; ?></td>
                                                                    <td><?php echo number_format($det['com_precio'], 0, ",", "."); ?></td>
                                                                    <td><?php echo $det['tipo_descri']; ?></td>
                                                                    <td><?php echo number_format($det['subtotal'], 0, ",", "."); ?></td>
                                                                    <td class="text-center">
                                                                        <a onclick="edit(<?php echo $det['com_cod']; ?>,<?php echo $det['art_cod']; ?>,<?php echo $det['dep_cod']; ?>)"
                                                                           class="btn btn-warning btn-sm" role="button" data-title="Editar" rel="tooltip" data-placement="top"
                                                                           data-toggle="modal" data-target="#editar"><i class="fa fa-edit"></i>
                                                                        </a>
                                                                        <a onclick="quitar(<?php echo "'".$det['com_cod']."_".$det['dep_cod']."_".$det['art_cod']
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
                                                    <i class="fa fa-info-circle"></i> La compra aún no tiene detalles cargados...
                                                </div>
                                        <?php } ?>                                              
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                <form action="compras_dcontrol.php" method="get" accept-charset="utf-8" class="form-horizontal">
                                                    <div class="box-body">
                                                        <input type="hidden" name="accion" value="1"/>     
                                                        <input type="hidden" name="vcom_cod" value="<?php echo $compras[0]['com_cod']?>"/>     
                                                        <div class="form-group">
                                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Deposito:</label>
                                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                                    <?php $depositos = consultas::get_datos("select * from deposito where id_sucursal=".$_SESSION['id_sucursal']);?>
                                                                    <select class="form-control select2" name="vdep_cod" required="">
                                                                        <?php if(!empty($depositos)) {                                                    
                                                                        foreach ($depositos as $deposito) { ?>
                                                                        <option value="<?php echo $deposito['dep_cod'];?>"><?php echo $deposito['dep_descri'];?></option>
                                                                        <?php } 
                                                                         }else{?>
                                                                        <option value="">Debe insertar al menos un deposito</option>
                                                                        <?php } ?>
                                                                    </select>                                                                                                                                               
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Articulo:</label>
                                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                                    <?php $articulos= consultas::get_datos("select * from articulo");?>
                                                                <select class="form-control select2" name="vart_cod" required="" id="articulo" onchange="precio()">
                                                                        <?php if(!empty($articulos)) {                                                    
                                                                        foreach ($articulos as $articulo) { ?>
                                                                        <option value="<?php echo $articulo['art_cod']."_".$articulo['art_preciov'];?>">
                                                                            <?php echo $articulo['art_descri'];?></option>
                                                                        <?php } 
                                                                         }else{?>
                                                                        <option value="">Debe insertar al menos un deposito</option>
                                                                        <?php } ?>
                                                                    </select>                                                                                                                                               
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cantidad:</label>
                                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                                <input type="number" name ="vcom_cant" class="form-control" min="1" value="1" required=""/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Precio:</label>
                                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                                <input type="number" name ="vcom_precio" class="form-control" min="1" required="" id="prec"/>
                                                            </div>
                                                        </div>                                              
                                                      </div>
                                                        <div class="box-footer">
                                                            <button type="submit" class="btn btn-primary pull-right">
                                                                <span class="glyphicon glyphicon-plus"></span> Agregar
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
    precio();
    function quitar(datos){
        var dat = datos.split('_');
        $("#si").attr('href','compras_dcontrol.php?vcom_cod='+dat[0]+'&vdep_cod='+dat[1]+'&vart_cod='+dat[2]+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea quitar el articulo<i><strong> '
                +dat[3]+'</strong> de la compra</i>?');
    };    
    function precio(){
        //alert($("#articulo").val());
        var dat = $("#articulo").val().split('_');
        $("#prec").val(dat[1]);
    };
    

</script>
<script>
      function edit(id,art,dep){        
        $.ajax({
            type    : "GET",
            url     : "/lp3/compras_dedit.php/?vcom_cod="+id+"&vart_cod="+art+"&vdep_cod="+dep,
            cache   : false,
            beforeSend:function(){
                $("#detalles").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>')
            },
            success:function(data){
                $("#detalles").html(data)
            }    
                
        });
    };
      function add(com,art,dep,ped){        
        $.ajax({
            type    : "GET",
            url     : "/lp3/compras_dadd.php/?vcom_cod="+com+"&vart_cod="+art+"&vdep_cod="+dep+"&vped_cod="+ped,
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