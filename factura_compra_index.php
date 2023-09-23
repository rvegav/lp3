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
        session_start(); /* Reanudar sesion */
        require 'menu/css_lte.ctp';
        ?><!--ARCHIVOS CSS-->

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php require 'menu/header_lte.ctp'; ?><!--CABECERA PRINCIPAL-->
            <?php require 'menu/toolbar_lte.ctp'; ?><!--MENU PRINCIPAL-->
            <div class="content-wrapper">
                <div class="content">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <i class="ion ion-clipboard"></i>
                                    <h3 class="box-title">Facturas de Compras</h3>
                                    <a href="factura_compra_add.php" class="btn btn-primary pull-right btn-sm"><i class="fa fa-plus"></i></a>
                                </div>                                
                                <div class="box-body">
                            <?php if (!empty($_SESSION['mensaje'])) { ?> <!--success, warning, danger-->
                            <div class="alert alert-success" role="alert" id="mensaje">
                                <span class="glyphicon glyphicon-info-sign"></span>
                                <?php echo $_SESSION['mensaje'];
                                $_SESSION['mensaje'] = '';?>
                            </div>        
                            <?php } ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?php
                                            $facturas_compras = consultas::get_datos("select * from facturas_compras order by 1");
                                            if (!empty($facturas_compras)) {
                                                ?>
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Nro de Factura</th>
                                                                <th>Fecha</th>
                                                                <th>Proveedor</th>
                                                                <th>Monto Total</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($facturas_compras as $factura) { ?>
                                                                <tr>
                                                                    <td><?php echo $factura['faco_nro_factura']; ?></td>
                                                                    <td><?php echo $factura['faco_fecha'] ?></td>
                                                                    <td><?php echo $factura['faco_prv_cod'] ?></td>
                                                                    <td><?php echo $factura['faco_monto'] ?></td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-warning btn-sm button_detalle" 
                                                                           data-title="Ver detalle" data-toggle="modal" data-target="#verDetalle" data-placement="top" onclick="verDetalle(<?php echo $factura['faco_cod'] ?>)" value="<?php echo $factura['faco_cod'] ?>">
                                                                            <i class="fa fa-list" ></i>
                                                                        </button>                                                                    
                                                                    </td>
                                                                </tr>
                                                        <?php } ?>                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <!--mostrar mensaje de alerta tipo info -->
                                                <div class="alert alert-info flat">
                                                    <i class="fa fa-info-circle"></i> No se han registrado facturas...
                                                </div>
                                        <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->  
        </div>
        <div class="modal fade" id="verDetalle" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"> Detalles de la compra</h4>
                    </div>
                    <div class="modal-body" id="detalle_compras_facturas">
                        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="detalle_compras_facturas">
                            
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="reset" data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> Cerrar</button>
                    </div>
                </div>
            </div>            
        </div>

<?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
<script>
    $("#mensaje").delay(4000).slideUp(200,function() {
    $(this).alert('close');
    });
    const verDetalle = (valor)=>{
        // alert(valor);
        $.ajax({
           type: "GET",
           url :"/lp3/factura_detalle_compras.php?vfact_cod="+valor+"&vaccion=2",
           cache: false,
           success : function(data){
                // alert(data);
               $("#detalle_compras_facturas").html(data);
           }
        });
    };
</script>
    </body>
</html>


