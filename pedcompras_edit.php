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
                            <div class="box box-warning">
                                <div class="box-header">
                                    <i class="ion ion-edit"></i>
                                    <h3 class="box-title">Editar Pedidos</h3>
                                    <a href="pedcompras_index.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Volver"
                                    rel="tooltip" data-placement="top">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                                <form action="pedcompras_control.php" method="get" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <?php $pedido = consultas::get_datos("select * from v_pedido_cabcompra where ped_cod=".$_REQUEST['vped_cod']);?>
                                        <input type="hidden" name="accion" value="2"/>
                                        <input type="hidden" name="vped_cod" value="<?php echo $pedido[0]['ped_cod']?>"/> 
                                        <div class="row">
                                            <div class="col-xs-12 col-lg-4 col-md-6">
                                                <label>Fecha:</label>
                                                <input type="text" name ="vped_fecha" class="form-control"  value="<?php echo $pedido[0]['ped_fecha']?>" readonly=""/>
                                            </div>
                                            <div class="col-xs-12 col-lg-2 col-md-6">
                                                <label> Proveedor:</label>
                                                    <div class="input-group">
                                                        <?php $proveedores = consultas::get_datos("select * from proveedor order by prv_cod=".$pedido[0]['prv_cod']." desc");?>
                                                        <select class="form-control select2" name="vprv_cod" required="">
                                                            <?php if(!empty($proveedores)) {                                                    
                                                            foreach ($proveedores as $proveedor) { ?>
                                                            <option value="<?php echo $proveedor['prv_cod'];?>"><?php echo $proveedor['prv_razonsocial'];?>
                                                            </option>
                                                            <?php } 
                                                             }else{?>
                                                            <option value="">Debe insertar al menos un proveedor</option>
                                                            <?php } ?>
                                                        </select>  
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-primary btn-flat" type="button" 
                                                            data-toggle ="modal" data-target="#registrar">
                                                                <i class="fa fa-plus"></i>    
                                                            </button>
                                                        </span>
                                                    </div>                                                                                                                                                  
                                            </div>
                                        </div>                                             
                                        <div class="row">
                                         <div class="col-xs-12 col-lg-4 col-md-6">
                                            <label class="">Empleado:</label>
                                                <input type="text" class="form-control" value="<?php echo $pedido[0]['empleado']?>" disabled=""/>
                                         </div>
                                          <div class="col-xs-12 col-lg-4 col-md-6">
                                                <label class="">Sucursal:</label>                                           
                                                <input type="text" class="form-control" value="<?php echo $pedido[0]['suc_descri']?>" disabled=""/>
                                          </div>                                            
                                        </div>  
                                      
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-warning pull-right">
                                            <span class="glyphicon glyphicon-floppy-disk"></span> Actualizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>
                  <?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->  
        <!-- MODAL CARGO AGREGAR -->
        <div class="modal fade" id="registrar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Registrar Marcas</h4>
                    </div>
                    <form action="articulo_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="4"/>
                            <input type="hidden" name="vmar_cod" value="0"/>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Descripción</label>
                                <div class="col-sm-10">
                                    <input type="text" name="vart_descri" class="form-control" required="" autofocus=""/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> Cerrar</button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Registrar</button>
                        </div>
                    </form>
                </div>
            </div>            
        </div>        
        <!-- FIN MODAL CARGO AGREGAR -->                  
            </div>                  
        <?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
    </body>
</html>


