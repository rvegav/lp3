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
        session_start();/*Reanudar sesion*/
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
                                    <i class="ion ion-trash-a"></i>
                                    <h3 class="box-title">Borrar Sucursal</h3>
                                    <a href="sucursal_index.php" class="btn btn-primary pull-right btn-sm"><i class="fa fa-arrow-left"></i></a>
                                </div>
                                <form action="sucursal_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <?php 
                                        $sucursal = consultas::get_datos("select * from sucursal where id_sucursal =".$_GET['vid_sucursal']);
                                        //var_dump($sucursal);
                                        ?>
                                        <input type="hidden" name="accion" value="3"/>
                                        <input type="hidden" name="vid_sucursal" value="<?php echo $sucursal[0]['id_sucursal'];?>"/>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-md-2"> Descripción</label>
                                                <div class="col-lg-8 col-md-8">
                                                    <input type="text" name="vsuc_descri" class="form-control" required="" minlength="5" disabled=""
                                                           value="<?php echo $sucursal[0]['suc_descri'];?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button class="btn btn-danger pull-right" type="submit">
                                            <i class="fa fa-trash"></i> ELIMINAR
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
             
            </div>
                  <?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->  
            </div>                  
        <?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
    </body>
</html>