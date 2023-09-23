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
                                    <h3 class="box-title">Tipo de Impuesto</h3>
                                    <a  class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#registrar"><i class="fa fa-plus"></i></a>
                                    <a href="cargo_print.php" class="btn btn-default btn-sm pull-right" role="button" target="print"><i class="fa fa-print"></i></a>
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
                                            <form method="post" accept-charset="utf-8" class="form-horizontal">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="input-group custom-search-form">
                                                                <input type="search" class="form-control" name="buscar" placeholder="Ingrese el valor a buscar..." autofocus="">
                                                                <span class="input-group-btn">
                                                                    <button type="submit" class="btn btn-primary btn-flat" data-title ="Buscar" rel="tooltip" data-placement="bottom">
                                                                        <i class="fa fa-search">
                                                                            
                                                                        </i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php
                                            $valor = '';
                                            if (isset($_REQUEST['buscar'])){
                                                $valor=$_REQUEST['buscar'];
                                            }
                                            $tipos = consultas::get_datos("select * from tipo_impuesto where tipo_descri ilike '%".$valor."%'order by tipo_cod");
                                            if (!empty($tipos)) {
                                                ?>
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Descripción</th>
                                                                <th>Porcentaje %</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($tipos as $tipo) { ?>
                                                                <tr>
                                                                    <td><?php echo $tipo['tipo_descri']; ?></td>
                                                                    <td><?php echo $tipo['tipo_porcen']; ?></td>
                                                                    <td class="text-center">
                                                            
                                                                        <a onclick="editar(<?php echo "'".$tipo['tipo_cod']."','".$tipo['tipo_descri']."','".$tipo['tipo_porcen']."'";?>)" class="btn btn-warning btn-sm" role="button" 
                                                                           data-title="Editar" rel="tooltip" data-placement="top">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>


                                                                        <a onclick="borrar(<?php echo "'".$tipo['tipo_cod']."','".$tipo['tipo_descri']."','".$tipo['tipo_porcen']."'";?>)" class="btn btn-danger btn-sm" role="button" 
                                                                           data-title="Borrar" rel="tooltip" data-placement="top">
                                                                            <i class="fa fa-trash"></i>
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
                                                    <i class="fa fa-info-circle"></i> No se han registrado marcas...
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
        <div class="modal fade" id="registrar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Registrar Tipo de Impuesto</h4>
                    </div>
                    <form action="tipoi_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="1"/>
                            <input type="hidden" name="vtipo_cod" value="0"/>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Descripcion</label>
                                <div class="col-sm-10">
                                    <input type="text" name="vtipo_descri" class="form-control" required=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Porcentaje</label>
                                <div class="col-sm-4">
                                    <input type="number" name="vtipo_porcen" class="form-control" required="" min="0"/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" data-dismiss="modal" class="btn btn-default pull-left"><i class="fa fa-close"></i> Cerrar</button>
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
        <!--modal cargo editar-->
        <div class="modal fade" id="editar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"><i class="fa fa-edit"></i>Editar Tipo de Impuesto</h4>
                    </div>
                    <form action="tipoi_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="2"/>
                            <input type="hidden" name="vtipo_cod" id="cod"/>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Descripcion</label>
                                <div class="col-sm-10">
                                    <input type="text" name="vtipo_descri" id="descri" class="form-control" required=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Porcentaje</label>
                                <div class="col-sm-4">
                                    <input type="number" name="vtipo_porcen" id="porcen" class="form-control" required="" min="0"/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> Cerrar</button>
                            <button type="submit" class="btn btn-warning"><i class="fa fa-edit"></i> Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            <!--modal cargo borrar-->
                <div class="modal fade" id="borrarEl" role="dialog">
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
                                    <button  data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> NO</button>
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
    function editar(id,descripcion,tipoPorcentaje){
        $("#cod").val(id);
        $("#descri").val(descripcion);
        $("#porcen").val(tipoPorcentaje);
        $('#editar').modal('show');
    };
function borrar(id,descripcion,tipoPorcentaje){
        // var dat = datos.split('_');
        $('#borrarEl').modal('show');
        $("#si").attr('href','tipoi_control.php?vtipo_cod='+id+'&vtipo_descri='+descripcion+'&vtipo_porcen='+tipoPorcentaje+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea el Tipo de impuesto <i><strong>'+id+'</strong></i>?');
    };
    
</script>
<script>
    $(".modal").on('shown.bs.modal',function(){
        $(this).find('input:text:visible:first').focus();
    })
</script>
    </body>
</html>


