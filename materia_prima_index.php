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
                                    <h3 class="box-title">Materias Primas</h3>
                                    <a href="materia_prima_add.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-plus"></i></a>                                                                            
                                    <!-- <a href="marca_print.php" class="btn btn-default btn-sm pull-right" role="button" target="print"><i class="fa fa-print"></i></a>    -->
                                </div>                                
                                <div class="box-body">
                            <?php if (!empty($_SESSION['correcto'])) { ?>
                            <div class="alert alert-success" role="alert" id="mensaje">
                                <span class="glyphicon glyphicon-info-sign"></span>
                                <?php echo $_SESSION['correcto'];
                                $_SESSION['error'] = '';
                                $_SESSION['correcto'] = '';
                                ?>
                            </div>        
                            <?php } ?>
                            <?php if ($_SESSION['error']!='') { ?>
                            <div class="alert alert-danger" role="alert" id="mensaje">
                                <span class="glyphicon glyphicon-info-sign"></span>
                                <?php echo $_SESSION['error'];
                                $_SESSION['error'] = '';
                                $_SESSION['correcto'] = '';
                                
                                ?>
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
                                            $materias = consultas::get_datos("select * from material_primario");
                                            if (!empty($materias)) {
                                                ?>
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Descripción</th>
                                                                <th>Unidad de Medida</th>
                                                                <th>Precio Estimado</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($materias as $materia_prima) { ?>
                                                                <tr>
                                                                    <td><?php echo $materia_prima['mapr_descripcion']; ?></td>
                                                                    <td><?php echo $materia_prima['mapr_unidad_medida']; ?></td>
                                                                    <td><?php echo $materia_prima['mapr_precio']; ?></td>
                                                                    <td class="text-center">
                                                                        <a href="materia_prima_edit.php?vmate_cod=<?php echo $materia_prima['mapr_id']; ?>" class="btn btn-warning btn-sm" role="button" 
                                                                           data-title="Editar" rel="tooltip" data-placement="top">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                        <a onclick="borrar(<?php echo $materia_prima['mapr_id']."_".$materia_prima['mapr_descripcion']." ".strtoupper($materia_prima['mapr_descripcion'])."'";?>)" data-toggle="modal" data-target="#borrar"
                                                                           class="btn btn-danger btn-sm" role="button" 
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
                                                    <i class="fa fa-info-circle"></i> No se han registrado materia prima...
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
//        alert(dat[1])
        $("#si").attr('href','materia_prima_control.php?vmate_prima_cod='+dat[0]+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea borrar el articulo <i><strong>'+dat[1]+'</strong></i>?');
    };    
    
</script>
    </body>
</html>