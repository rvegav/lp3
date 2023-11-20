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
                                <h3 class="box-title">Modulo Producción</h3>
                                <a href="orden_produccion_add.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-plus"></i></a>
                            </div>                                
                            <div class="box-body">
                                <?php if (!empty($_SESSION['correcto'])) { ?>
                                    <div class="alert alert-success" role="alert" id="mensaje">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        <?php echo $_SESSION['correcto'];
                                        $_SESSION['mensaje'] = '';?>
                                    </div>        
                                <?php } ?>
                                <?php if (!empty($_SESSION['error'])) { ?>
                                    <div class="alert alert-danger" role="alert" id="mensaje">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        <?php echo $_SESSION['error'];
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
                                    <?php
                                    $producciones = consultas::get_datos("select prod_id, prod_nro, prod_fecha, prod_lote, prod_orpr_id, prod_aprobado, (SELECT distinct     etpr_descripcion from control_produccion c left join etapas_produccion e  on c.copr_etpr_id = e.etpr_id where c.copr_prod_id = prod_id and c.copr_item = (select max(cp.copr_item) from control_produccion cp where cp.copr_prod_id=c.copr_prod_id)) etapa, (select count(*) from control_calidad where coca_prod_id = prod_id) flg_cal, (SELECT distinct copr_observacion  from control_produccion c left join etapas_produccion e  on c.copr_etpr_id = e.etpr_id where c.copr_prod_id = prod_id and c.copr_item = (select max(cp.copr_item) from control_produccion cp where cp.copr_prod_id=c.copr_prod_id)) observacion from produccion");
                                    if (!empty($producciones)) {
                                        ?>
                                        <!-- crear tabla con datos -->
                                        <div class="table-responsive">
                                            <table class="table table-condensed table-striped table-hover table-bordered" id="tablaProduccion">
                                                <thead>
                                                    <tr>
                                                        <th>Nro</th>
                                                        <th>Fecha Produccion</th>
                                                        <th>Nro Lote</th>
                                                        <th>Orden Asoc.</th>
                                                        <th>Etapa Actual</th>
                                                        <th>Observación</th>
                                                        <th>Estado</th>
                                                        <th class="text-center">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($producciones as $produccion) { ?>
                                                        <tr>
                                                            <td><?php echo $produccion['prod_nro']; ?></td>
                                                            <td><?php echo $produccion['prod_fecha']; ?></td>
                                                            <td><?php echo $produccion['prod_lote']; ?></td>
                                                            <td><?php echo $produccion['prod_orpr_id']; ?></td>
                                                            <td><?php echo $produccion['etapa']; ?></td>
                                                            <?php if ($produccion['observacion']==''): ?>
                                                                <td>No hay observación</td>
                                                            <?php else: ?>
                                                                <td><?php echo $produccion['observacion']; ?></td>                                                                
                                                            <?php endif ?>
                                                            <?php if ($produccion['prod_aprobado']!='f'): ?>
                                                                <?php $estado = 'CULMINADO' ?>
                                                            <?php else: ?>
                                                                <?php $estado = 'EN PROCESO'?>
                                                            <?php endif ?>
                                                            <td><?php echo $estado; ?></td>
                                                            <td class="text-center">
                                                                <a  data-toggle = "modal" data-target ="#detalles<?php echo $produccion['prod_id']; ?>"class="btn btn-success btn-sm" role="button" data-title="Detalles" rel="tooltip" data-placement="top" >
                                                                    <i class="fa fa-list"></i>
                                                                </a>
                                                                <?php if ($produccion['flg_cal']==0): ?>

                                                                    <a onclick="culminar_produccion(<?php echo $produccion['prod_nro']?>, <?php echo $produccion['prod_id'] ?>)" data-toggle="modal" data-target="#culminar<?php echo $produccion['prod_id']; ?>" class="btn btn-warning btn-sm" role="button" 
                                                                       data-title="Culminar" rel="tooltip" data-placement="top">
                                                                       <i class="fa fa-check"></i>
                                                                   </a>
                                                               <?php endif ?>
                                                               <a  data-toggle="modal" data-target="#operaciones<?php echo $produccion['prod_id']; ?>"
                                                                   class="btn btn-success btn-sm" role="button" 
                                                                   rel="tooltip" data-placement="top">
                                                                   <i class="fa fa-plus"></i><input type="hidden" value="<?php echo $produccion['prod_id']; ?>">
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
                                        <i class="fa fa-info-circle"></i> No se han registrado producciones de produccion...
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
<!-- MODAL CARGO culminar -->
<div class="modal fade" id="culminar" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title"><i class="fa fa-trash"></i>Atenci&oacute;n</h4>
          </div>                    
          <div class="modal-body">
            <div class="alert alert-danger" id="confirmacion-"></div>
        </div>
        <div class="modal-footer">                            
            <a id="si_" role="button" class="btn btn-danger"><i class="fa fa-check"></i> SI</a>
            <button data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
        </div>
    </div>
</div>            
</div>
<?php foreach ($producciones as $produccion): ?>

    <div class="modal fade" id="operaciones<?php echo $produccion['prod_id'];  ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Menu Opereaciones</h4>
                </div>                    
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 offset-3">
                            <button  type="button" data-toggle="modal" data-target="#control<?php echo $produccion['prod_id']; ?>" class="btn btn-block btn-primary btn-lg rounded-pill" id="id_btnedit_detdeveng">Control de Calidad</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 offset-3">
                            <button type="button" data-toggle="modal" data-target="#etapa<?php echo $produccion['prod_id']; ?>" class="btn btn-block btn-primary btn-lg rounded-pill" id="id_mov_detalle">Gestionar Etapa</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 offset-3">
                            <button type="button" data-toggle="modal" data-target="#costo<?php echo $produccion['prod_id']; ?>" class="btn btn-block btn-primary btn-lg rounded-pill" id="id_cambio_plani">Gestionar Costos</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div> 
    <div class="modal fade" id="culminar<?php echo $produccion['prod_id'];  ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Culminacion de Produccion</h4>
                </div>                    
                <div class="modal-body">
                    <div class="alert alert-success" id="confirmacion"></div>
                </div>
                <div class="modal-footer">                            
                    <a id="si" role="button" class="btn btn-danger"><i class="fa fa-check"></i> SI</a>
                    <button data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detalles<?php echo $produccion['prod_id'];  ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Articulos a Producir</h4>
                </div>                    
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive">
                            <?php $sql = "SELECT art_descri||'-'||mar_descri art_descri, d.depro_cantidad FROM detalle_produccion d JOIN articulo a on a.art_cod = d.depro_art_id  join marca m on m.mar_cod = a.mar_cod WHERE depro_prod_id = ".$produccion['prod_id']; ?>
                            <?php $detalles = consultas::get_datos($sql)?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Articulo</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($detalles)): ?>
                                        <?php foreach ($detalles as $detalle): ?>
                                            <tr>
                                                <td><?php echo $detalle['art_descri'] ?></td>
                                                <td><?php echo $detalle['depro_cantidad'] ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div> 
    <div class="modal fade" id="costo<?php echo $produccion['prod_id'];  ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>                    
                <div class="modal-body">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Gastos de la produccion</h3>
                        </div>
                        <div class="box-body">
                            <?php $costoProduccion = consultas::get_datos("select * from costo_produccion where cospr_prod_id =". $produccion['prod_id']); ?>

                            <?php if (empty($costoProduccion)): ?>

                                <form accept-charset="utf-8" class="form-horizontal" id="frmcosto<?php echo $produccion['prod_id'] ?>">
                                    <input type="hidden" name="vprod_id" value="<?php echo $produccion['prod_id'] ?>">
                                    <input type="hidden" name="accion" value="7">
                                    <?php $costoMaterial = consultas::get_datos("select sum(mapr_precio) total_precio from composion_articulos c join material_primario m on m.mapr_id = c.coar_mapr_id where coar_art_id = (SELECT depro_art_id FROM detalle_produccion where depro_prod_id =". $produccion['prod_id'].")"); ?>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Costo por Composicion:</label>
                                        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                            <input type="text" name ="vcostoComposion" class="form-control" required="" value="<?php echo $costoMaterial[0]['total_precio'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Costo Mano de Obra:</label>
                                        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                            <input type="text" name ="vcostoMano" class="form-control" required="" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Fecha:</label>
                                        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                            <input type="date" name ="vfecha" class="form-control" required="" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary pull-right registrar_costo">
                                            <span class="glyphicon glyphicon-floppy-disk"></span> Registrar
                                        </button>
                                    </div>
                                </form>  
                            <?php endif ?>

                        </div>
                        <div class="box-footer">
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <div class="table-responsive">

                                    <table class="table" id="tablaCosto<?php echo $produccion['prod_id'] ?>">
                                        <thead>
                                            <td>Costo Mano de Obra</td>
                                            <td>Costo Materiales</td>
                                            <td>Fecha</td>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div> 
    <div class="modal fade" id="control<?php echo $produccion['prod_id'];  ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>                    
                <div class="modal-body">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Control de Calidad</h3>
                        </div>
                        <div class="box-body">
                            <?php $costoProduccion = consultas::get_datos("select * from control_calidad where coca_prod_id =". $produccion['prod_id']); ?>

                            <?php if (empty($costoProduccion)): ?>

                                <form accept-charset="utf-8" class="form-horizontal" id="frmcontrol<?php echo $produccion['prod_id'] ?>">
                                    <input type="hidden" name="vprod_id" value="<?php echo $produccion['prod_id'] ?>">
                                    <input type="hidden" name="accion" value="9">
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Calificacion:</label>
                                        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                            <select class="form-group select2" name="calificacion" required="">
                                                <option value="0">No Aprobado</option>
                                                <option value="1">Aprobado</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary pull-right registrar_control">
                                            <span class="glyphicon glyphicon-floppy-disk"></span> Registrar
                                        </button>
                                    </div>
                                </form>  
                            <?php endif ?>

                        </div>
                        <div class="box-footer">
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div> 
    <div class="modal fade" id="etapa<?php echo $produccion['prod_id'];  ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>                    
                <div class="modal-body">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Gestion de Etapas</h3>
                        </div>
                        <div class="box-body">
                            <form accept-charset="utf-8" class="form-horizontal" id="<?php echo $produccion['prod_id'] ?>">
                                <div class="form-group">
                                    <input type="hidden" name="vprod_id" value="<?php echo $produccion['prod_id'] ?>">
                                    <input type="hidden" name="accion" value="5">
                                    <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Etapa:</label>
                                    <div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
                                        <div class="input-group">
                                            <?php $etapas_produccion = consultas::get_datos("select * from etapas_produccion where  etpr_id not in (select copr_etpr_id from control_produccion where  copr_prod_id = ".$produccion['prod_id']." )");?>
                                            <select class="form-group select2" name="vetpr_id" required="">
                                                <?php if(!empty($etapas_produccion)) {
                                                    foreach ($etapas_produccion as $etapa) { ?>
                                                        <option value="<?php echo $etapa['etpr_id'];?>"><?php echo $etapa['etpr_descripcion'];?></option>
                                                    <?php } 
                                                }else{?>
                                                    <option value="">Debe insertar al menos una etapa</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <?php $detalle_produccion = consultas::get_datos("select d.depro_cantidad cantidad from produccion o join detalle_produccion d on d.depro_prod_id = o.prod_id where prod_id = ".$produccion['prod_id']); ?>
                                <div class="form-group">
                                    <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cantidad Producida:</label>
                                    <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                        <input type="text" name ="vcantidad" class="form-control" required=""/ value="<?php echo $detalle_produccion[0]['cantidad'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Observacion:</label>
                                    <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                        <input type="text" name ="vobservacion" class="form-control" required="" value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Realizado por:</label>
                                    <div class="col-lg-8 col-sm-8 col-md-8 col-xs-10">
                                        <input type="text" name ="vempl" class="form-control" value="<?php echo $_SESSION['nombres'] ?>" disabled />
                                        <input type="hidden" name ="vemplcod" class="form-control" value="<?php echo $_SESSION['emp_cod'] ?>" required=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary pull-right registrar_etapa">
                                        <span class="glyphicon glyphicon-floppy-disk"></span> Registrar
                                    </button>
                                </div>
                            </form>  

                        </div>
                        <div class="box-footer">
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <div class="table-responsive">

                                    <table class="table" id="historial_etapa<?php echo $produccion['prod_id'] ?>">
                                        <thead>
                                            <td>Etapa</td>
                                            <td>Fecha</td>
                                            <td>Cantidad</td>
                                            <td>Observacion</td>
                                            <td>Estado</td>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">                            

                </div>
            </div>
        </div>            
    </div>       
<?php endforeach ?>      

</div>                  
<?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
<script>
    $("#mensaje").delay(4000).slideUp(200,function() {
        $(this).alert('close');
    });
</script>
<script>
    function culminar_produccion(nro, id){    
        $("#si").attr('href','produccion_control.php?vprod_id='+ id + '&accion=8');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea dar por culminada la Produccion N°<i><strong>'+nro+'</strong></i>?');
    }; 
    let prod_id;
    $('#tablaProduccion tbody').on('click','a',function(){
        let prod_id_aux;    
        console.log(this);
        prod_id = this.children[1].getAttribute('value');
        let cadena_etapa = '#historial_etapa'+prod_id;
        let cadena_costo = '#tablaCosto'*prod_id;
        if (prod_id != prod_id_aux) {
            $(cadena_etapa).dataTable().fnDestroy();
            $(cadena_costo).dataTable().fnDestroy();
            document.getElementById(prod_id).reset();
        }
        let tabla = $(cadena_etapa).DataTable({
            'lengthMenu':[[10, 15, 20], [10, 15, 20]],
            'paging':true,
            "bLengthChange" : false,
            'filter':true,
            'stateSave':true,
            'processing':true,
            'searching':false,
            'ajax': {
                url: 'produccion_control.php',
                "type":"POST",
                "data":function(data){
                    data.vprod_id=prod_id;
                    data.accion=6;
                    data.ruta='etapa';
                }
            },
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
            'columns':[
                {data:'etapa','sClass':'text-center'},
                {data:'fecha','sClass':'text-center'},
                {data:'cantidad','sClass':'text-center'},
                {data:'observacion','sClass':'text-center'},
                {data:'estado','sClass':'text-center'}
                ]
        }); 
        let tablaCosto = $(cadena_costo).DataTable({
            'lengthMenu':[[10, 15, 20], [10, 15, 20]],
            'paging':true,
            "bLengthChange" : false,

            'filter':true,
            'stateSave':true,
            'processing':true,
            'searching':false,
            'ajax': {
                url: 'produccion_control.php',
                "type":"POST",
                "data":function(data){
                    data.vprod_id=prod_id;
                    data.accion=6;
                    data.ruta='costo';
                }
            },
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
            'columns':[
                {data:'mano','sClass':'text-center'},
                {data:'material','sClass':'text-center'},
                {data:'fecha','sClass':'text-center'},
                ]
        });
    });
$('.registrar_etapa').click(()=>{
    let cadena = '#'+prod_id;
    let tabla = '#historial_etapa'+prod_id;
    let data = $(cadena).serialize();

    $.ajax({
        url: 'produccion_control.php',
        type: 'POST',
        data: data,
    })
    .done(function(r) {
        let json = JSON.parse(r);
        if (json == 'correcto') {
            $(tabla).DataTable().ajax.reload();
        }
    })
    .fail(function(xrs) {
        var json = JSON.parse(xrs.responseText);
        if (json.mensaje) {
            alert(json.mensaje);
        }else{
            alert('Ocurrio un error inesperado');
        }
    })
    .always(function() {
        console.log("complete");
    });
})     
$('.registrar_costo').click(()=>{
    let cadena = 'frmcosto'+prod_id;
    let tablaCosto = '#tablaCosto'+prod_id;
    let data = $("#"+cadena).serialize();
    $.ajax({
        url: 'produccion_control.php',
        type: 'POST',
        data: data,
    })
    .done(function(r) {
        let json = JSON.parse(r);
        if (json == 'correcto') {
            $(tablaCosto).DataTable().ajax.reload();
        }else{
            alert('Ya se registro el costo para esta produccion ')
        }

    })
    .fail(function(xrs) {
        var json = JSON.parse(xrs.responseText);
        if (json.mensaje) {
            alert(json.mensaje);
        }else{
            alert('Ocurrio un error inesperado');
        }

    })
    .always(function() {
        console.log("complete");
    });

}) 
$('.registrar_control').click(()=>{
    let cadena = 'frmcontrol'+prod_id;
    let data = $("#"+cadena).serialize();
    $.ajax({
        url: 'produccion_control.php',
        type: 'POST',
        data: data,
    })
    .done(function(r) {
        let json = JSON.parse(r);
        if (json == 'correcto') {
            alert('Se registro correctamente');
        }else{
            alert('Ya se registro la calificacion para esta produccion ');
        }

    })
    .fail(function(xrs) {
        var json = JSON.parse(xrs.responseText);
        if (json.mensaje) {
            alert(json.mensaje);
        }else{
            alert('Ocurrio un error inesperado');
        }

    })
    .always(function() {
        console.log("complete");
    });

}) 
</script>
</body>
</html>