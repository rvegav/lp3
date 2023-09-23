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
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <?php 
        #session_start();
        require 'menu/css_lte.ctp'; ?>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php require 'menu/header_lte.ctp'; ?>
            <?php require 'menu/toolbar_lte.ctp';?>
            <div class="content-wrapper">
                <div class="content">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <i class="ion ion-plus"></i>
                                    <h3 class="box-title">Agregar Pedidos</h3>
                                    <a href="pedcompras_index.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Volver"
                                    rel="tooltip" data-placement="top">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                                <form action="pedcompras_control.php" method="get" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="accion" value="1"/>
                                        <input type="hidden" name="vped_cod" value="0"/> 
                                        <div class="row">
                                            <div class="col-xs-12 col-lg-4 col-md-6">
                                                <?php $fecha = consultas::get_datos("select current_date as fecha");?>
                                                <label>Fecha:</label>
                                                <input type="date" name ="vped_fecha" class="form-control" required="" value="<?php echo $fecha[0]['fecha']?>" readonly=""/>
                                            </div>
                                            <div class="col-xs-12 col-lg-2 col-md-6">
                                                <label> Proveedor:</label>
                                                    <div class="input-group">
                                                        <?php $proveedores = consultas::get_datos("select * from proveedor order by prv_razonsocial asc");?>
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
                                                <input type="text" class="form-control" value="<?php echo $_SESSION['nombres']?>" disabled=""/>
                                         </div>
                                          <div class="col-xs-12 col-lg-4 col-md-6">
                                                <label class="">Sucursal:</label>                                           
                                                <input type="text" class="form-control" value="<?php echo $_SESSION['sucursal']?>" disabled=""/>
                                          </div>                                            
                                        </div>                                        
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-right">
                                            <span class="glyphicon glyphicon-floppy-disk"></span> Registrar
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


