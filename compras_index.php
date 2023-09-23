<?php
session_start();
//require 'acceso_bloquear_compras.php';
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
        #session_start(); /* Reanudar sesion */
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
                                    <i class="fa fa-money"></i>
                                    <h3 class="box-title">Orden de Compras</h3>
                                    <a href="compras_add.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-plus"></i></a>                                                                                                                
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
                                            <form method="post" accept-charset="utf-8" class="form-horizontal">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                                            <div class="input-group custom-search-form">
                                                                <input type="search" class="form-control" name="buscar" placeholder="Ingrese el valor a buscar..." autofocus="">    
                                                                <span class="input-group-btn">
                                                                    <button type="submit" class="btn btn-primary btn-flat"
                                                                    data-title = "Buscar" rel="tooltip" data-placement="bottom">
                                                                        <i class="fa fa-search"></i>
                                                                    </button>
                                                                </span>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>                                              
                                            <?php $compras = consultas::get_datos("select * from v_compras where id_sucursal =".$_SESSION['id_sucursal']." and (com_cod||''||proveedor) ilike '%".(isset($_REQUEST['buscar'])? $_REQUEST['buscar']:"")."%' order by com_cod desc");
                                            if (!empty($compras)) { ?>
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered" id="tablaCompras">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Fecha</th>
                                                                <th>Proveedor</th>
                                                                <th>Condición</th>
                                                                <th>Total</th>
                                                                <th>Estado</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($compras as $compra) { ?>
                                                                <tr>
                                                                    <td><?php echo $compra['com_cod']; ?></td>
                                                                    <td><?php echo $compra['com_fecha']; ?></td>
                                                                    <td><?php echo $compra['proveedor']; ?></td>
                                                                    <td><?php echo $compra['tipo_compra']; ?></td>
                                                                    <td><?php echo number_format($compra['com_total_precio'], 0, ",", "."); ?></td>
                                                                    <td><?php echo $compra['com_estado']; ?></td>
                                                                    <td class="text-center">
                                                                        <?php if ($compra['com_estado']==='PENDIENTE') { ?>
                                                                        <a onclick="confirmar(<?php echo "'".$compra['com_cod']."_".$compra['proveedor']."_".$compra['com_fecha']."'";?>)" 
                                                                           data-toggle="modal" data-target="#confirmar" class="btn btn-info btn-sm" role="button" 
                                                                           data-title="Confirmar" rel="tooltip" data-placement="top"> <i class="fa fa-check"></i></a>  
                                                                        <a href="compras_det.php?vcom_cod=<?php echo $compra['com_cod']; ?>" 
                                                                           class="btn btn-success btn-sm" role="button" data-title="Detalles" rel="tooltip" data-placement="top">
                                                                            <i class="fa fa-list"></i>
                                                                        </a>
                                                                        <a onclick="anular(<?php echo "'".$compra['com_cod']."_".$compra['proveedor']."_".$compra['com_fecha']."'";?>)" 
                                                                           data-toggle="modal" data-target="#borrar" class="btn btn-danger btn-sm" role="button" 
                                                                           data-title="Anular" rel="tooltip" data-placement="top"> <i class="fa fa-close"></i></a>                                                                          
                                                                        <?php }?>                                                                        
                                                                        <a href="/lp3/pedcompras_print.php?vped_cod=<?php echo $compra['com_cod'];?>" 
                                                                           class="btn btn-primary btn-sm" role="button" target="print"
                                                                           data-title="Imprimir" rel="tooltip" data-placement="top">
                                                                            <i class="fa fa-print"></i></a>                                                                         
                                                                                                                                          
                                                                    </td>
                                                                </tr>
                                                        <?php } ?>                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <!--mostrar mensaje de alerta tipo info -->
                                                <div class="alert alert-info flat">
                                                    <i class="fa fa-info-circle"></i> No se han registrado compras...
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
                            <div class="alert alert-danger" id="confirmacion"></div>
                        </div>
                        <div class="modal-footer">                            
                            <a id="si" role="button" class="btn btn-danger"><i class="fa fa-check"></i> SI</a>
                            <button data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
                        </div>
                </div>
            </div>            
        </div>                
        <!-- FIN MODAL CARGO BORRAR -->  
        <!-- MODAL CONFIRMAR COMPRA -->
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
                            <div class="alert alert-success" id="confirmacionc"></div>
                        </div>
                        <div class="modal-footer">                            
                            <a id="sic" role="button" class="btn btn-danger"><i class="fa fa-check"></i> SI</a>
                            <button data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
                        </div>
                </div>
            </div>            
        </div>                
        <!-- FIN MODAL CONFIRMAR COMPRA -->           
        </div>                  
<?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
<script>
    $("#mensaje").delay(4000).slideUp(200,function() {
    $(this).alert('close');
    });
</script>
<script>
    function anular(datos){
        var dat = datos.split('_');
        $("#si").attr('href','compras_control.php?vcom_cod='+dat[0]+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea anular la compra N°<i><strong>'
                +dat[0]+'</strong> de fecha <i><strong>'+dat[2]+'</strong> y proveedor <i><strong>'+dat[1]+'</strong></i>?');
    };    
    function confirmar(datos){
        var dat = datos.split('_');
        $("#sic").attr('href','compras_control.php?vcom_cod='+dat[0]+'&accion=2');
        $("#confirmacionc").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea confirmar la compra N°<i><strong>'
                +dat[0]+'</strong> de fecha <i><strong>'+dat[2]+'</strong> y proveedor <i><strong>'+dat[1]+'</strong></i>?');
    }; 
</script>
<script type="text/javascript">
    var tabla = $("#tablaCompras").DataTable({
        'lengthMenu':[[10, 15, 20], [10, 15, 20]],
        'paging':true,
        'info':true,
        'filter':true,
        'stateSave':true,
        'processing':true,
        ////'scrollX':true,
        'searching':true,
        
        'language':{
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        
    });
</script>
    </body>
</html>
