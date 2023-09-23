<?php
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php'; ?>
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
                                    <i class="ion ion-clipboard"></i>
                                    <h3 class="box-title">Cargos</h3>
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
                                            $cargos = consultas::get_datos("select * from cargo where (car_cod||car_descri)  ilike '%".(isset($_REQUEST['buscar'])? $_REQUEST['buscar']:"")."%' order by car_cod");
                                            if (!empty($cargos)) {
                                                ?>
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Descripción</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($cargos as $cargo) { ?>
                                                                <tr>
                                                                    <td><?php echo $cargo['car_descri']; ?></td>
                                                                    <td class="text-center">
                                                                        <a onclick="editar(<?php echo "'".$cargo['car_cod']."','".$cargo['car_descri']."'";?>)" class="btn btn-warning btn-sm" role="button" 
                                                                           data-title="Editar" rel="tooltip" data-placement="top">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                        <a onclick="borrar(<?php echo "'".$cargo['car_cod']."','".$cargo['car_descri']."'";?>)" class="btn btn-danger btn-sm" role="button" 
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
                        <h4 class="modal-title">Registrar Cargos</h4>
                    </div>
                    <form action="cargo_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="1"/>
                            <input type="hidden" name="vcar_cod" value="0"/>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Descripcion</label>
                                <div class="col-sm-10">
                                    <input type="text" name="vcar_descri" class="form-control" required=""/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> Cerrar</button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Registrar</button>
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
                        <h4 class="modal-title"><i class="fa fa-edit"></i>Editar Cargos</h4>
                    </div>
                    <form action="cargo_control.php" method="post" accept-charset="utf-8" class="form-horizontal">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="2"/>
                            <input type="hidden" name="vcar_cod" id="cod"/>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Descripcion</label>
                                <div class="col-sm-10">
                                    <input type="text" name="vcar_descri" id="descri" class="form-control" required=""/>
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
    function editar(id,descr){
        // var dat = datos.split('_');
        // $("#cod").val(dat[0]);
        // $("#descri").val(dat[1]);
        $("#cod").val(id);
        $("#descri").val(descr);
        $("#editar").modal('show');
        //$("#descri").val().select();
    };
function borrar(id,descr){
        // var dat = datos.split('_');
        $('#borrar').modal('show');
        $("#si").attr('href','cargo_control.php?vcar_cod='+id+'&vcar_descri='+descr+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea borrar el cargo <i><strong>'+id+'</strong></i>?');
    };
    
</script>
<script>
    $(".modal").on('shown.bs.modal',function(){
        $(this).find('input:text:visible:first').focus();
    })
</script>
    </body>
</html>


