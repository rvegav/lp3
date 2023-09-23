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
                                    <i class="ion ion-edit"></i>
                                    <h3 class="box-title">Editar Articulos</h3>
                                    <a href="stock_index.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Volver"
                                    rel="tooltip" data-placement="top">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                                <form action="stock_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <?php $articulo = consultas::get_datos("select * from v_stock where art_cod=".$_REQUEST['vart_cod']." and dep_cod = ".$_REQUEST['vdep_cod'])?>
                                        <input type="hidden" name="accion" value="2"/>
                                        <input type="hidden" name="vart_cod" value="<?php echo $_REQUEST['vart_cod'] ?>"/>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cod. Barra:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-8">
                                                <input type="text" name ="vart_codbarra" class="form-control" autofocus="" value="<?php echo $articulo[0]['art_codbarra']?>"/>
                                            </div>
                                        </div>           
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Marca:</label>
                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                    <?php $marcas = consultas::get_datos("select * from marca order by mar_cod=".$articulo[0]['mar_cod']." desc");?>
                                                    <select class="form-control select2" name="vmar_cod" required="">
                                                        <?php if(!empty($marcas)) {                                                    
                                                        foreach ($marcas as $marca) { ?>
                                                        <option value="<?php echo $marca['mar_cod'];?>"><?php echo $marca['mar_descri'];?></option>
                                                        <?php } 
                                                         }else{?>
                                                        <option value="">Debe insertar al menos una marca</option>
                                                        <?php } ?>
                                                    </select>                                                                                                                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Descripción:</label>
                                            <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                                <input type="text" name ="vart_descri" class="form-control" required="" value="<?php echo $articulo[0]['art_descri']?>"/>
                                            </div>
                                        </div>  
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Precio Costo:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                <input type="number" name ="vart_precioc" class="form-control" min="0" value="<?php echo $articulo[0]['art_precioc']?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Precio Venta:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                <input type="number" name ="vart_preciov" class="form-control" min="0" required="" value="<?php echo $articulo[0]['art_preciov']?>"/>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cantidad:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                <input type="number" name ="vart_cantidad" class="form-control" min="0" required="" value="<?php echo $articulo[0]['stoc_cant']?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Deposito:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                                                <input type="text" name ="vdep_codv" class="form-control" min="0" required="" value="<?php echo $articulo[0]['dep_descri']?>"/>
                                                <input type="hidden" name ="vdep_cod" class="form-control" min="0" required="" value="<?php echo $articulo[0]['dep_cod']?>"/>
                                            </div>
                                        </div>     
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2"> Impuesto:</label>
                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                                    <?php $tipos = consultas::get_datos("select * from tipo_impuesto order by tipo_cod=".$articulo[0]['tipo_cod']." desc");?>
                                                    <select class="form-control select2" name="vtipo_cod" required="">
                                                        <?php if(!empty($tipos)) {                                                    
                                                        foreach ($tipos as $tipo) { ?>
                                                        <option value="<?php echo $tipo['tipo_cod'];?>"><?php echo $tipo['tipo_descri'];?></option>
                                                        <?php } 
                                                         }else{?>
                                                        <option value="">Debe insertar al menos un tipo de impuesto</option>
                                                        <?php } ?>
                                                    </select>                                                                                                                                                 
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-warning pull-right">
                                            <span class="glyphicon glyphicon-edit"></span> Actualizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>
                  <?php require 'menu/footer_lte.ctp'; ?>
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
            </div>                  
        <?php require 'menu/js_lte.ctp'; ?>
    </body>
</html>