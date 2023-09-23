<?php
        session_start();/*Reanudar sesion*/
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
                                    <h3 class="box-title">Agregar Deposito</h3>
                                    <a href="deposito_index.php" class="btn btn-primary pull-right btn-sm"><i class="fa fa-arrow-left"></i></a>
                                </div>
                                <form action="deposito_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="accion" value="1"/>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-md-2"> Descripción</label>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="text" name="vdep_descri" class="form-control" required="" minlength="5" autofocus="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-md-2"> Cod. Sucursal</label>
                                                <div class="col-lg-8 col-md-8">
                                            <?php $sucursal = consultas::get_datos("select * from sucursal order by 1");?>
                                                <select name="vdep_sucursal" class="form-control">
                                                    <option>Seleccionar</option>
                                                <?php if (!empty($sucursal)) {?>
                                                    <?php for($i=0;$i< count($sucursal);$i++){?>
                                                    <option value="<?php echo $sucursal[$i]['id_sucursal'] ?>"><?php echo $sucursal[$i]['suc_descri'] ?></option>
                                                    <?php } ?>
                                                <?php }else{ ?>
                                                    <option>Sin registros</option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group">
                                            <label class="control-label col-lg-2 col-md-2"> Cod. Sucursal</label>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="text" name="vdep_sucursal" class="form-control" required="required" minlength="1" autofocus="">
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="box-footer">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-floppy-o"></i> REGISTRAR
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