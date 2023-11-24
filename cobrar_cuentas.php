<?php
session_start();
        // require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/consultasAjax.php';
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
<body class="hold-transition skin-blue sidebar-mini" onload="metodo()">
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
                                <h3 class="box-title">Cuentas a cobrar</h3>
                                <a href="cuentas_cobrar_index.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Volver"
                                rel="tooltip" data-placement="top">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                        </div>
                        <form action="pagos_control.php" method="get" accept-charset="utf-8" class="form-horizontal" id="frm_fact_compra">
                            <div class="box-body">
                                <input type="hidden" name="accion" value="1"/>
                                <input type="hidden" name="vctco_id" value="<?php echo $_REQUEST['vctco_id']?>">
                                <div class="row">
                                    <div class="col-xs-12 col-lg-4 col-md-4">
                                        <?php $fecha = consultas::get_datos("select current_date as fecha");?>
                                        <label>Fecha:</label>
                                        <input type="date" name ="vpago_fecha" class="form-control" required value="<?php echo $fecha[0]['fecha']?>"/>
                                    </div>
                                    <div class="col-xs-12 col-lg-4 col-md-4">
                                        <label> Metodo de Pago:</label>
                                        <div >
                                            <select class="form-control select2" name="vmetodo" required="" id="metodo_pago" onchange="metodo()">
                                                <option value="E">Efectivo</option>
                                                <option value="T">Tarjeta</option>
                                            </select>  

                                        </div>                                                                                                                                                  
                                    </div>
                                    <div class="col-xs-12 col-lg-4 col-md-4 tipo " style="display: none" >
                                            <label class="control-label"> Nro Tarjeta:</label>
                                            <input type="number" name="nro_tarjeta" class="form-control" >
                                                                                                                                                                              
                                    </div>
                                </div>                   

                                <br> 
                                <div class="row">
                                    <div class="col-xs-12 col-lg-12 col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-condensed table-striped table-hover table-bordered" id="cuenta_cobrar">
                                                <thead>
                                                    <!-- <th>#</th> -->
                                                    <th>Nro Cuota</th>
                                                    <th>Monto Cuota</th>
                                                    <th>Fecha de Vencimiento</th>
                                                    <th>Total</th>
                                                </thead>
                                                <?php $cuota = consultas::get_datos("select * from ctas_a_cobrar where ctco_id =".$_REQUEST['vctco_id']); ?>
                                                <tbody>
                                                    <?php foreach ($cuota as $detalle): ?>
                                                        <tr>
                                                            <td><?php echo $detalle['nro_cuota'] ?></td>
                                                            <td class="monto_cuota"><?php echo $detalle['monto_cuota'] ?></td>
                                                            <td><?php echo $detalle['fecha_venc'] ?></td>
                                                            <td class="monto_total"><?php echo $detalle['monto_cuota'] ?></td>
                                                            
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
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
    </div>                  
    <?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
    <script type="text/javascript">
            // console.log($('#vcom_cod').val());
        const metodo =()=>{
            if ($('#metodo_pago').val()==="E" ) {
                $('.tipo').hide();
  
            }else{
                $('.tipo').show();
                
            }
            let monto_formato = formatNumber.new($('.monto_total').text(), "")
            $('.monto_cuota').text(monto_formato);
            $('.monto_total').text(monto_formato);
        };

    const formatNumber = {
               separador: ".", // separador para los miles
               sepDecimal: ',', // separador para los decimales
               formatear:function (num){
                 num +='';
                 var splitStr = num.split('.');
                 var splitLeft = splitStr[0];
                 var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
                 var regx = /(\d+)(\d{3})/;
                 while (regx.test(splitLeft)) {
                     splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
                 }
                 return this.simbol + splitLeft +splitRight;
             },
             new:function(num, simbol){
                 this.simbol = simbol ||'';
                 return this.formatear(num);
             }
         }
     </script>
 </body>
 </html>


