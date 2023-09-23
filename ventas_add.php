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
                                    <h3 class="box-title">Agregar Venta</h3>
                                    <a href="ventas_index.php" class="btn btn-primary pull-right btn-sm"><i class="fa fa-arrow-left"></i></a>
                                </div>
                                <form action="ventas_control.php" method="get" accept-charset="utf-8" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="accion" value="1"/>
                                        <input type="hidden" name="vven_cod" value="0"/>
                                        <div class="form-group">
                                            <?php $fecha = consultas::get_datos("select to_char(current_date,'dd/mm/yyyy') as fecha");?>
                                            <label class="control-label col-lg-2 col-md-2 col-xs-2"> Fecha:</label>
                                                <div class="col-lg-4 col-md-4 col-xs-4">
                                                    <input type="text" name="vven_fecha" class="form-control" value="<?php echo $fecha[0]['fecha']?>" disabled="">
                                            </div>
                                            <label class="control-label col-lg-2 col-md-2 col-xs-2"> Condición:</label>
                                                <div class="col-lg-4 col-md-4 col-xs-4">
                                                    <select class="form-control select2" name="vtipo_venta" required="" id="tipo_venta" onchange="tipoventa()">                                                        
                                                        <option value="CONTADO">CONTADO</option>                                                        
                                                        <option value="CREDITO">CREDITO</option>                                                        
                                                    </select>                                                    
                                                </div>                                            
                                        </div>  
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-sm-2 col-md-2 col-xs-2"> Cliente:</label>
                                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-10">
                                                <div class="input-group">
                                                    <?php $clientes= consultas::get_datos("select * from clientes order by cli_cod");?>
                                                    <select class="form-control select2" name="vcli_cod" required="" onchange="pedidos()" id="cliente">
                                                        <?php if(!empty($clientes)) {   ?>
                                                        <option value="">Seleccione un cliente</option>
                                                        <?php foreach ($clientes as $cliente) { ?>
                                                        <option value="<?php echo $cliente['cli_cod'];?>"><?php echo "(".$cliente['cli_ci'].") ".$cliente['cli_nombre']." ".$cliente['cli_apellido'];?></option>
                                                        <?php } 
                                                         }else{?>
                                                        <option value="">Debe insertar al menos un cliente</option>
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
                                            <div id="detalles_pedidos"></div>
                                        </div>
                                        <div class="form-group tipo" style="display: none">
                                            <label class="control-label col-lg-2 col-md-2 col-xs-2"> Cant Cuotas:</label>
                                            <div class="col-lg-4 col-md-4 col-xs-12">
                                                <input type="number" name="vcan_cuota" class="form-control" required="" min="1" max="36" value="1" readonly="" id="cuotas">
                                            </div>                                            
                                            <label class="control-label col-lg-2 col-md-2 col-xs-2"> Intervalo:</label>
                                            <div class="col-lg-4 col-md-4 col-xs-12">
                                                <input type="number" name="vven_plazo" class="form-control" min="0" max="90" value="0" readonly="" id="intervalo">
                                            </div>                                                                                        
                                        </div>                                        
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 col-md-2 col-xs-2"> Empleado:</label>
                                            <div class="col-lg-4 col-md-4 col-xs-12">
                                                    <input type="text" name="vempleado" class="form-control" value="<?php echo $_SESSION['nombres']?>" disabled="">
                                            </div>                                            
                                            <label class="control-label col-lg-2 col-md-2 col-xs-2"> Sucursal:</label>
                                            <div class="col-lg-4 col-md-4 col-xs-12">
                                                    <input type="text" name="vsucursal" class="form-control" value="<?php echo $_SESSION['sucursal']?>" disabled="">
                                            </div>                                                                                        
                                        </div>
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
        <script>
            //pedidos();
            function pedidos(){
                //alert($('#cliente').val())
                $.ajax({
                   type: "GET",
                   url : "/lp3/ventas_pedidos.php?vcli_cod="+$('#cliente').val(),
                   cache: false,
                   beforeSend:function(){
                       $("#detalles_pedidos").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>');
                   },
                   success : function(data){
                       $("#detalles_pedidos").html(data);
                   }
                });
            };
            function tipoventa(){
                //alert('tipo venta');
                if ($('#tipo_venta').val()==="CONTADO" ) {
                    $('.tipo').hide();
                    $('#cuotas').prop("readonly",true);
                    $('#cuotas').val(1);
                    $('#intervalo').prop("readonly",true);
                    $('#intervalo').val(0);
                }else{
                    $('.tipo').show();
                    $('#cuotas').prop("readonly",false);
                    $('#intervalo').prop("readonly",false);                    
                }
            };
        </script>
    </body>
</html>