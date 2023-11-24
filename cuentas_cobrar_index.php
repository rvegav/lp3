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
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <?php
        #session_start();
    require 'menu/css_lte.ctp';
    ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php require 'menu/header_lte.ctp'; ?>
        <?php require 'menu/toolbar_lte.ctp'; ?>
        <div class="content-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="ion ion-clipboard"></i>
                                <h3 class="box-title">Cuentas a Cobrar</h3>
                                
                            </div>                                
                            <div class="box-body">
                                <?php if (!empty($_SESSION['mensaje'])) { ?>
                                    <div class="alert alert-danger" role="alert" id="mensaje">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        <?php echo $_SESSION['mensaje'];
                                        $_SESSION['mensaje'] = '';?>
                                    </div>        
                                <?php } ?>       
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                            
                                            <?php
                                            $cuentas_pagar = consultas::get_datos("select * from ctas_a_cobrar order by ven_cod asc ");
                                            if (!empty($cuentas_pagar)) {
                                                ?>
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Nro Cuota</th>
                                                                <th>Nro Venta</th>
                                                                <th>Monto Cuota</th>
                                                                <th>Saldo Cuota</th>
                                                                <th>Fecha Vencimiento</th>
                                                                <th>Estado</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($cuentas_pagar as $cuenta) { ?>
                                                                <tr>
                                                                    <td><?php echo $cuenta['nro_cuota']; ?></td>
                                                                    <td><?php echo $cuenta['ven_cod']; ?></td>
                                                                    <td><?php echo $cuenta['monto_cuota']; ?></td>
                                                                    <td><?php echo $cuenta['saldo_cuota']; ?></td>
                                                                    <td><?php echo $cuenta['fecha_venc']; ?></td>
                                                                    <?php if ($cuenta['estado_cuota']=='P'): ?>
                                                                        <?php $estado = 'PENDIENTE' ?>
                                                                    <?php else: ?>
                                                                        <?php $estado = 'ABONADO' ?>
                                                                    <?php endif ?>
                                                                    <td><?php echo $estado; ?></td>
                                                                    <td class="text-center">
                                                                        <?php if ($cuenta['estado_cuota']=='P'): ?>

                                                                            <a onclick="confirmar(<?php echo $cuenta['ctco_id'] ?>, <?php echo $cuenta['nro_cuota'] ?>)" class="btn
                                                                                btn-default btn-sm" role="button" 
                                                                                data-title="Cobrar" rel="tooltip" data-toggle="modal"  data-placement="top" data-target="#confirmar">
                                                                                <i class="fa fa-check"></i>
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <a class="btn
                                                                            btn-success btn-sm" role="button" 
                                                                            data-title="Ya abonado" rel="tooltip" data-toggle="modal"  data-placement="top">
                                                                            <i class="fa fa-check"></i>
                                                                        </a>
                                                                    <?php endif ?>
                                                                </a>                                                                    
                                                            </td>
                                                        </tr>
                                                    <?php } ?>                                                            
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else { ?>
                                        <!--mostrar mensaje de alerta tipo info -->
                                        <div class="alert alert-info flat">
                                            <i class="fa fa-info-circle"></i> No se han registrado cuentas a pagar...
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
    <?php require 'menu/footer_lte.ctp'; ?>  
    <!-- MODAL CARGO BORRAR -->
    <div class="modal fade" id="borrar" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title"><i class="fa fa-trash"></i>Atenci&oacute;n</h4>
              </div>                    
              <div class="modal-body">
                <div class="alert alert-danger" id="confirmacion_borrar"></div>
            </div>
            <div class="modal-footer">                            
                <a id="si_borrar" role="button" class="btn btn-danger"><i class="fa fa-check"></i> SI</a>
                <button data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
            </div>
        </div>
    </div>            
</div>           
<div class="modal fade" id="confirmar" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title"><i class="fa fa-trash"></i>Atenci&oacute;n</h4>
          </div>                    
          <div class="modal-body">
            <div class="alert alert-success" id="confirmacion_orden"></div>
        </div>
        <div class="modal-footer">                            
            <a id="si_confirmar" role="button" class="btn btn-success"><i class="fa fa-check"></i> SI</a>
            <button data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
        </div>
    </div>
</div>            
</div> 
</div>                  
<?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
<script>
    $("#mensaje").delay(4000).slideUp(200,function() {
        $(this).alert('close');
    });
</script>
<script>
    function borrar(id, nro){    
        $("#si_borrar").attr('href','orden_produccion_control.php?vctco_id='+ id + '&accion=3');
        $("#confirmacion_borrar").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea borrar la orden de Produccion <i><strong>'+id+'</strong></i>?');
    };    
    function confirmar(id, nro){    
        $("#si_confirmar").attr('href','cobrar_cuentas.php?vctco_id='+ id + '&accion=7');
        $("#confirmacion_orden").html('<span class="glyphicon glyphicon-check"></span> Desea cobrar la cuota N° <i><strong>'+nro+'</strong></i>?');
    };
</script>
</body>
</html>