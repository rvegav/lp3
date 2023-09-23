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
                                    <i class="ion ion-person"></i>
                                    <h3 class="box-title">Usuario del Sistema</h3>
                                    <a onclick="add()" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Agregar"                                       
                                    rel="tooltip" data-placement="top" data-toggle="modal" data-target="#adduser">>
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
                                            $usuarios = consultas::get_datos("select * from v_usuarios order by usu_cod");
                                            if (!empty($usuarios)) { ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Empleado</th>
                                                            <th>Alias</th>
                                                            <th>Grupo</th>
                                                            <th>Sucursal</th>
                                                            <th>Estado</th>
                                                            <th class="text-center">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        foreach ($usuarios as $usuario) { ?>
                                                        <tr>
                                                            <td data-title="#">
                                                                <?php echo $usuario['usu_cod'];?>
                                                            </td>
                                                            <td data-title="Empleado">
                                                                <?php echo $usuario['empleado'];?>
                                                            </td>  
                                                            <td data-title="Alias">
                                                                <?php echo $usuario['usu_nick'];?>
                                                            </td>
                                                            <td data-title="Grupo">
                                                                <?php echo $usuario['gru_nombre'];?>
                                                            </td> 
                                                            <td data-title="Sucursal">
                                                                <?php echo $usuario['suc_descri'];?>
                                                            </td>
                                                            <td>
                                                                <?php if ($usuario['intentos']>3) { ?>
                                                                     <button class="btn btn-danger" style="width: 100% !important" onclick="cambiarEstadoUsuario('<?php echo $usuario['usu_cod']?>','1')">Bloqueado</button>
                                                                <?php }else{ ?>
                                                                    <button class="w-100 btn btn-success" style="width: 100% !important" onclick="cambiarEstadoUsuario('<?php echo $usuario['usu_cod']?>','4')">Activo</button>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="empleado_edit.php?vemp_cod=<?php echo $usuario['emp_cod'];?>" class="btn btn-warning btn-sm" role="buttom" data-title ="Editar"
                                                                   rel="tooltip" data-placement="top">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <a href="empleado_del.php?vemp_cod=<?php echo $usuario['emp_cod'];?>" class="btn btn-danger btn-sm" role="buttom" data-title ="Borrar"
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
             function add(){   
                 /*alert(id);*/
                $.ajax({
                    type    : "GET",
                    url     : "/lp3/usuarios_add.php",
                    cache   : false,
                    beforeSend:function(){
                        $("#detalles").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>')
                    },
                    success:function(data){
                        $("#detalles").html(data)
            }    
                
        });
    };
    function cambiarEstadoUsuario(usuario,estado,tipoAccion=1){
        $.ajax({
                url: 'usuarios_control.php',
                type: 'POST',
                data: {usuario,estado,tipoAccion},
            })
            .done(function(r) {
                var json = JSON.parse(r);  
                location.reload();
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
    }
        </script>
    }
    </body>
</html>
