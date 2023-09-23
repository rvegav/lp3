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
                                    <h3 class="box-title">Agregar Detalle Pedido</h3>
                                    <a href="pedventas_index.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-arrow-left"></i></a>                                                                                                                
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
                                            <?php $pedidos = consultas::get_datos("select * from v_pedido_cabventa where id_sucursal =".$_SESSION['id_sucursal']." and ped_cod=".$_REQUEST['vped_cod']);
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
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($pedidos as $pedido) { ?>
                                                                <tr>
                                                                    <td><?php echo $pedido['ped_cod']; ?></td>
                                                                    <td><?php echo $pedido['ped_fecha']; ?></td>
                                                                    <td><?php echo $pedido['clientes']; ?></td>
                                                                    <td><?php echo number_format($pedido['ped_total'], 0, ",", "."); ?></td>
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
                                            <?php $pedidosdet = consultas::get_datos("select * from v_detalle_pedventa where ped_cod =".$pedidos[0]['ped_cod']);
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
                                                                <th>Precio</th>
                                                                <th>Impuesto</th>
                                                                <th>Subtotal</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($pedidosdet as $det) { ?>
                                                                <tr>
                                                                    <td><?php echo $det['art_cod']; ?></td>
                                                                    <td><?php echo $det['art_descri']." ".$det['mar_descri']; ?></td>
                                                                    <td><?php echo $det['ped_cant']; ?></td>
                                                                    <td><?php echo number_format($det['ped_precio'], 0, ",", "."); ?></td>
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
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                <form action="pedventas_dcontrol.php" method="get" accept-charset="utf-8" class="form-horizontal">
                                                    <div class="box-body">
                                                        <input type="hidden" name="accion" value="1"/>     
                                                        <input type="hidden" name="vped_cod" value="<?php echo $pedidos[0]['ped_cod']?>"/>     
                                                        <div class="form-group">
                                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Deposito:</label>
                                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                                    <?php $depositos = consultas::get_datos("select * from deposito where id_sucursal=".$_SESSION['id_sucursal']);?>
                                                                    <select class="form-control select2" name="vdep_cod" required="" id="selDeposito">
                                                                        <option value="">Seleccionar</option>
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
                                                                        <option value="">Debe insertar al menos un deposito</option>
                                                                    </select>                                                                                                                                               
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cantidad:</label>
                                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                                <input type="number" name ="vped_cant" class="form-control" min="1" value="1" required=""/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Precio:</label>
                                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                                <input type="number" name ="vped_precio" class="form-control" min="1" required="" id="prec"/>
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
        $("#si").attr('href','pedventas_dcontrol.php?vped_cod='+dat[0]+'&vdep_cod='+dat[1]+'&vart_cod='+dat[2]+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea quitar el articulo<i><strong> '
                +dat[3]+'</strong> del pedido</i>?');
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
            url     : "/lp3/pedventas_dedit.php/?vped_cod="+id+"&vart_cod="+art+"&vdep_cod="+dep,
            cache   : false,
            beforeSend:function(){
                $("#detalles").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>')
            },
            success:function(data){
                $("#detalles").html(data)
            }    
                
        });
    }  

$('#selDeposito').change(function() {
        var opcion = $('#selDeposito').val();
        var metodo = 1;
       $.ajax({
        url : 'pedventas_control.php',
        data : {opcion,metodo},
        type : 'POST',
        success : function(json) {
           var js = JSON.parse(json);
           if(js.articulos!=null){
            var html1 = "";
            html1 += "<option>Debe insertar al menos un deposito</option>"; 

            for (var i = 0; i < js.articulos.length; i++) {
                html1 += "<option value='"+js.articulos[i].art_cod+"_"+js.articulos[i].art_preciov+"'>"+js.articulos[i].art_descri+" - "+js.articulos[i].mar_descri+"</option>"; 
            }
           }else{
                html1 += "<option selected='selected'>Debe insertar al menos un deposito</option>"; 

           }
            $('#articulo').html(html1);
           //alert(js.);
        },
        error : function(xhr, status) {
            var json = JSON.parse(xhr.responseText);
            alert(json.mensajeValid);
            // console.log(xhr);
        },
        complete : function(xhr, status) {
            
        }
    });
    });
</script>
    </body>
</html>