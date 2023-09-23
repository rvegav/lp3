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
                                    <i class="ion ion-plus"></i>
                                    <h3 class="box-title">Agregar Articulos</h3>
                                    <a href="articulo_index.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Volver"
                                    rel="tooltip" data-placement="top">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                                <form action="articulo_control.php" method="get" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="accion" value="1"/>
                                        <input type="hidden" name="vart_cod" value="0"/>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cod. Barra:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-8">
                                                <input type="text" name ="vart_codbarra" class="form-control" autofocus=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">MARCA:</label>
                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                <div class="input-group">
                                                    <?php $marcas = consultas::get_datos("select * from marca order by mar_cod");?>
                                                <select class="form-control select2" name="vmar_cod" required="">
                                                    <?php if(!empty($marcas)) {
                                                    foreach ($marcas as $marca) { ?>
                                                    <option value="<?php echo $marca['mar_cod'];?>"><?php echo $marca['mar_descri'];?></option>
                                                     <?php } 
                                                    }else{?>
                                                    <option value="">Debe insertar al menos una marca</option>
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
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Descripcion:</label>
                                            <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                                <input type="text" name ="vart_descri" class="form-control" required=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Precio Costo:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                <input type="number" name ="vart_precioc" class="form-control" min="0" value="0"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Precio Venta:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                <input type="number" name ="vart_preciov" class="form-control" min="0" required=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Impuesto:</label>
                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                <div class="input-group">
                                                    <?php $tipos = consultas::get_datos("select * from tipo_impuesto order by tipo_cod");?>
                                                <select class="form-control select2" name="vtipo_cod" required="">
                                                    <?php if(!empty($tipos)) {
                                                    foreach ($tipos as $tipo) { ?>
                                                    <option value="<?php echo $tipo['tipo_cod'];?>"><?php echo $tipo['tipo_descri'];?></option>
                                                     <?php } 
                                                    }else{?>
                                                    <option value="">Debe insertar al menos un tipo de impuesto</option>
                                                    <?php } ?>
                                                </select>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary btn-flat" type="button">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
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
                                <label class="control-label col-sm-2">Descripcion</label>
                                <div class="col-sm-10">
                                    <input type="text" name="vart_descri" class="form-control" required=""/>
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
            </div>                  
        <?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
    </body>
</html>

