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
                                    <i class="ion ion-clipboard"></i>
                                    <h3 class="box-title">Empleado</h3>
                                    <a href="empleado_add.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Agregar"                                       
                                    rel="tooltip" data-placement="top">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <a href="empleado_print.php" class="btn btn-default btn-sm pull-right" role="button" target="print"><i class="fa fa-print"></i></a>                                    
                                </div>
                                <div class="box-body">
                                 <?php if (!empty($_SESSION['mensaje'])) { ?>
                                    <div class="alert alert-danger" id="mensaje">
                                        <span class="glyphicon glyphicon-info-sign"></span> 
                                            <?php echo $_SESSION['mensaje'];
                                            $_SESSION['mensaje'] ='';?>
                                    </div>                                            
                                 <?php }?>
                                 
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?php                                            
                                            $empleados = consultas::get_datos("select * from v_empleado order by emp_cod");
                                            if (!empty($empleados)) { ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Nombres y Apellidos</th>
                                                            <th>Teléfono</th>
                                                            <th>Dirección</th>
                                                            <th>Cargo</th>
                                                            <th class="text-center">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        foreach ($empleados as $empleado) { ?>
                                                        <tr>
                                                            <td data-title="#">
                                                                <?php echo $empleado['emp_cod'];?>
                                                            </td>
                                                            <td data-title="Nombres y Apellidos">
                                                                <?php echo $empleado['emp_nombre']." ".$empleado['emp_apellido'];?>
                                                            </td>                                                           
                                                            <td data-title="Telefono">
                                                                <?php echo $empleado['emp_tel'];?>
                                                            </td>  
                                                            <td data-title="Dirección">
                                                                <?php echo $empleado['emp_direcc'];?>
                                                            </td>
                                                            <td data-title="Cargo">
                                                                <?php echo $empleado['car_descri'];?>
                                                            </td> 
                                                            <td class="text-center">
                                                                <?php if ($empleado['usu_cod']==null) {?>
                                                                 <a onclik="add_user(<?php echo $empleado['emp_cod'];?>)" class="btn btn-primary btn-sm" role="buttom" data-title ="Usuario"
                                                                   rel="tooltip" data-placement="top" data-toggle="modal" data-target="#adduser">
                                                                    <i class="fa fa-user-plus"></i>
                                                                </a>
                                                                <?php } ?>
                                                                <a href="empleado_edit.php?vemp_cod=<?php echo $empleado['emp_cod'];?>" class="btn btn-warning btn-sm" role="buttom" data-title ="Editar"
                                                                   rel="tooltip" data-placement="top">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <a href="empleado_del.php?vemp_cod=<?php echo $empleado['emp_cod'];?>" class="btn btn-danger btn-sm" role="buttom" data-title ="Borrar"
                                                                   rel="tooltip" data-placement="top">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>                                                            
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                           <?php }else{ ?>
                                            <div class="alert alert-info flat">
                                                <span class="glyphicon glyphicon-info-sign"></span> 
                                                No se han registrado empleados...
                                            </div>
                                           <?php  }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>
        <?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->
            <div class="modal fade" id="adduser" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content" id="detalles">
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
            function add_user(id){        
                $.ajax({
                    type    : "GET",
                    url     : "/lp3/empleado_adduser.php/?vemp_cod="+id,
                    cache   : false,
                    beforeSend:function(){
                        $("#detalles").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>')
                    },
                    success:function(data){
                        $("#detalles").html(data)
                    }    

                });
            };
        </script>
    </body>
</html>
