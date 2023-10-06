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
                                    <h3 class="box-title">Agregar Materia Prima</h3>
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
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cod. Materia Prima:</label>
                                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-8">
                                                <input type="text" name ="vmate_prima_cod" class="form-control" autofocus=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Descripcion:</label>
                                            <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                                <input type="text" name ="vmate_prima_descri" class="form-control" required=""/>
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
            </div>                  
        <?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
    </body>
</html>

