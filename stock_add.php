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
                            <div class="box box-primary">
                                <div class="box-header">
                                    <i class="ion ion-plus"></i>
                                    <h3 class="box-title">Agregar Articulos</h3>
                                    <a href="articulo_index.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Volver"
                                    rel="tooltip" data-placement="top">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                                <form action="stock_control.php" method="POST" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="accion" value="1"/>
                                        <input type="hidden" name="vart_cod" value="0"/>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">DEPOSITO:</label>
                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                <div class="input-group">
                                                    <?php $deposito = consultas::get_datos("select * from deposito order by dep_cod");?>
                                                    <select class="form-control select2" name="vdep_cod" required="">
                                                        <?php if(!empty($deposito)) {
                                                        foreach ($deposito as $deposito) { ?>
                                                        <option value="<?php echo $deposito['dep_cod'];?>"><?php echo $deposito['dep_descri'];?></option>
                                                         <?php } 
                                                        }else{?>
                                                        <option value="">Debe insertar al menos una deposito</option>
                                                        <?php } ?>
                                                    </select>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                          <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">ARTICULO:</label>
                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                <div class="input-group">
                                                    <?php $articulo = consultas::get_datos("select * from articulo order by art_cod");?>
                                                    <select class="form-control select2" name="vart_cod" required="">
                                                        <?php if(!empty($articulo)) {
                                                        foreach ($articulo as $articulo) { ?>
                                                        <option value="<?php echo $articulo['art_cod'];?>"><?php echo $articulo['art_descri'];?></option>
                                                         <?php } 
                                                        }else{?>
                                                        <option value="">Debe insertar al menos una articulo</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cantidad:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                <input type="number" name ="vart_cantidad" class="form-control" min="0" required=""/>
                                            </div>
                                        </div>>
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

