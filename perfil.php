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
     #session_start(); /* Reanudar sesion */
    require 'menu/css_lte.ctp';
    ?>
    <!-- <link rel="stylesheet" href="css/style.css"> -->

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
                                <h3 class="box-title"><?php echo $_SESSION['nombres'];?></h3>
                            </div>
                            <form action="usuarios_control.php" class="form-horizontal" method="post">
                                <input type="hidden" name="tipoAccion" value="uppass">
                                <div class="box-body">
                                    <?php if (!empty($_SESSION['mensaje'])) { ?>
                                        <div class="alert alert-success" role="alert" id="mensaje">
                                            <span class="glyphicon glyphicon-info-sign"></span>
                                            <?php echo $_SESSION['mensaje'];$_SESSION['mensaje'] = '';?>
                                        </div>        
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="control-label col-lg-2 col-md-2 col-md-offset-1">Contraseña nueva</label>
                                                <div class="col-lg-8 col-md-8">
                                                    <input type="password" name="newPass" class="form-control" id="password-field" required="" autofocus="">
                                                    <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-lg-2 col-md-2 col-md-offset-1">Confirmar contraseña</label>
                                                <div class="col-lg-8 col-md-8">
                                                    <input type="password" name="confirPass" class="form-control" id="confpass-field" required="" autofocus="">
                                                    <span toggle="#confpass-field" class="fa fa-fw fa-eye field-icon toggle-confpass"></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right">
                                        <span class="glyphicon glyphicon-floppy-disk"></span> Actualizar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php require 'menu/footer_lte.ctp'; ?>
    </div>                  
    <?php require 'menu/js_lte.ctp'; ?>
    <script>
        $("#mensaje").delay(4000).slideUp(200,function() {
            $(this).alert('close');
        });
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            let input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
        $(".toggle-confpass").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            let input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
</body>
</html>


