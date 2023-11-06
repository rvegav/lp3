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
                                <h3 class="box-title">Articulos</h3>
                                <a href="articulo_add.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-plus"></i></a>                                                                            
                                <a href="marca_print.php" class="btn btn-default btn-sm pull-right" role="button" target="print"><i class="fa fa-print"></i></a>   
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
                                    <?php
                                    $articulos = consultas::get_datos("select * from v_articulo where (art_descri||mar_descri) ilike '%".(isset($_REQUEST['buscar'])? $_REQUEST['buscar']:"")."%' order by art_cod");
                                    if (!empty($articulos)) {
                                        ?>
                                        <!-- crear tabla con datos -->
                                        <div class="table-responsive">
                                            <table class="table table-condensed table-striped table-hover table-bordered" id="tablaArticulos">
                                                <thead>
                                                    <tr>
                                                        <th>Descripción</th>
                                                        <th>Precio Costo</th>
                                                        <th>Precio Venta</th>
                                                        <th>Impuesto</th>
                                                        <th class="text-center">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($articulos as $articulo) { ?>
                                                        <tr>
                                                            <td><?php echo $articulo['art_descri']." ". strtoupper($articulo['mar_descri']); ?></td>
                                                            <td><?php echo number_format($articulo['art_precioc'], 0, ",", "."); ?></td>
                                                            <td><?php echo number_format($articulo['art_preciov'], 0, ",", "."); ?></td>
                                                            <td><?php echo $articulo['tipo_descri']; ?></td>
                                                            <td class="text-center">
                                                                <a href="articulo_edit.php?vart_cod=<?php echo $articulo['art_cod']; ?>" class="btn btn-warning btn-sm" role="button" 
                                                                 data-title="Editar" rel="tooltip" data-placement="top">
                                                                 <i class="fa fa-edit"></i>
                                                             </a>
                                                             <a onclick="borrar(<?php echo "'".$articulo['art_cod']."_".$articulo['art_descri']." ".strtoupper($articulo['mar_descri'])."'";?>)" data-toggle="modal" data-target="#borrar"
                                                                 class="btn btn-danger btn-sm" role="button" 
                                                                 data-title="Borrar" rel="tooltip" data-placement="top">
                                                                 <i class="fa fa-trash"></i>
                                                             </a>
                                                             <a  class="btn btn-warning btn-sm" role="button" data-title="Ver Composicion" data-toggle="modal" data-target="#composicion<?= $articulo['art_cod'] ?>" rel="tooltip" data-placement="top">
                                                                 <i class="fa fa-eye"></i><input type="hidden" class="vart_cod" value="<?php echo $articulo['art_cod'] ?>">
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
                                        <i class="fa fa-info-circle"></i> No se han registrado articulos...
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
            <div class="alert alert-danger" id="confirmacion"></div>
        </div>
        <div class="modal-footer">                            
            <a id="si" role="button" class="btn btn-danger"><i class="fa fa-check"></i> SI</a>
            <button data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
        </div>
    </div>
</div>            
</div>           
<!-- MODAL CARGO BORRAR -->
<?php foreach ($articulos as $articulo): ?>
    <div class="modal fade" id="composicion<?= $articulo['art_cod'] ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Composicion del Articulo</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>                    
              <div class="modal-body">
                <form action="" method="get" accept-charset="utf-8" class="form-horizontal" id="<?php echo $articulo['art_cod'] ?>">
                    <div class="box-body">
                        <input type="hidden" name="accion" value="1"/>
                        <input type="hidden" name="vart_cod" class="vart_cod" value="<?php echo $articulo['art_cod'] ?>">
                        <div class="form-group">
                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Materiales:</label>
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                <div class="input-group">
                                    <?php $sql = "select * from material_primario where mapr_id not in (select coar_mapr_id from composion_articulos where coar_art_id =". $articulo['art_cod'].")" ?>
                                    <?php $materiales = consultas::get_datos($sql);?>
                                    <select class="form-control select2" name="vmapr_id" required="">
                                        <?php if (!empty($materiales)): ?>
                                            <?php foreach ($materiales as $material): ?>
                                                <option value="<?php echo $material['mapr_id'];?>"><?php echo $material['mapr_descripcion'];?></option>
                                            <?php endforeach ?>
                                            
                                        <?php else: ?>
                                            <option value="">No existen materiales cargados</option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2 col-sm-3 col-md-2 col-xs-2">Cantidad:</label>
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="vcant_materia" name="vcant_materia">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="button"  class="btn btn-primary pull-right registrar_composicion">
                            <span class="glyphicon glyphicon-floppy-disk"></span> Registrar
                        </button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table composicion_articulo" id="composicion_articulo<?php echo $articulo['art_cod'] ?>">
                        <thead>
                            <th>Material</th>
                            <th>Cantidad</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    
                </div>
            </div>

        </div>
    </div>            
</div>  
<?php endforeach ?>
<!-- FIN MODAL CARGO BORRAR -->                
</div>                  
<?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
<script>
    $("#mensaje").delay(4000).slideUp(200,function() {
        $(this).alert('close');
    });
</script>
<script>
    function borrar(datos){
        var dat = datos.split('_');
        $("#si").attr('href','articulo_control.php?vart_cod='+dat[0]+'&vart_descri='+dat[1]+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea borrar el articulo <i><strong>'+dat[1]+'</strong></i>?');
    };
    let art_id;
    $('#tablaArticulos tbody').on('click','a',function(){
        let art_id_aux;    
        art_id = this.children[1].getAttribute('value');
        let cadena = '#composicion_articulo'+art_id;
        if (art_id != art_id_aux) {
            $(cadena).dataTable().fnDestroy();
            document.getElementById(art_id).reset();
        }
        let tabla = $(cadena).DataTable({
            'lengthMenu':[[10, 15, 20], [10, 15, 20]],
            'paging':true,
            'info':true,
            'filter':true,
            'stateSave':true,
            'processing':true,
            'searching':false,
            'ajax': {
                url: 'articulo_control.php',
                "type":"POST",
                "data":function(data){
                    data.vart_cod=art_id;
                    data.accion=2;
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
                {data:'material','sClass':'text-center'},
                {data:'cantidad'}]
            
        }); 

    });
    $('.registrar_composicion').click(()=>{
        let cadena = '#'+art_id;
        let tabla = '#composicion_articulo'+art_id;
        let data = $(cadena).serialize();
        $.ajax({
            url: 'articulo_control.php',
            type: 'POST',
            data: data,
        })
        .done(function(r) {
            let json = JSON.parse(r);
            console.log(data);
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
            $("#vencimiento").val("");
        })
        .always(function() {
            console.log("complete");
        });

    })   
    
</script>
</body>
</html>