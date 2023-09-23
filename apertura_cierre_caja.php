<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
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
        <style type="text/css">
            .e-i-1{
                color: red;
            }
            .e-i-1:hover{
                color: #390005;
                cursor: pointer;
            }

        </style>
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
                                    <h3 class="box-title"> Ventas</h3>
                                    <a href="ventas_add.php" class="btn btn-primary btn-sm pull-right" role="button"><i class="fa fa-plus"></i></a>                                                                                                                
                                </div>                                
                                <div class="box-body">
                            <?php if (!empty($_SESSION['mensaje'])) { ?>
                            <div class="alert alert-success" role="alert" id="mensaje">
                                <span class="glyphicon glyphicon-info-sign"></span>
                                <?php echo $_SESSION['mensaje'];
                                $_SESSION['mensaje'] = '';?>
                            </div>        
                            <?php }else if(!empty($_SESSION['mensajeValid'])){ ?>
                                <div class="alert alert-danger" role="alert" id="mensaje">
                                <span class="glyphicon glyphicon-info-sign"></span>
                                <?php echo $_SESSION['mensajeValid'];
                                $_SESSION['mensajeValid'] = '';?>
                            </div>
                                <?php } ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                             
                                            <?php $caja = consultas::get_datos("select * from caja c where c.id_sucursal =".$_SESSION['id_sucursal']);
                                            if (!empty($caja)) { ?>
                                                <!-- crear tabla con datos -->
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-25">Descripcion de caja</th>
                                                                <th>Estado</th>
                                                                <th>Detalle</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php for($i=0;$i< count($caja);$i++){ ?>
                                                                <tr>
                                                                    <td><?php echo $caja[$i]['caj_descri'] ?></td>
                                                                    <?php $aperCierre = consultas::get_datos("select * from apertura_cierre ap
                                                                    where caj_cod = ".$caja[$i]['caj_cod']." and ap.id_sucursal = ".$_SESSION["id_sucursal"]." and aper_fecha is not null and aper_cierre is null");
                                                                    if (!empty($aperCierre)) { ?>
                                                                        <td>Habilitado</td>
                                                                        <td><i onclick="obtenerDetalleAperCierre(<?php echo $caja[$i]['caj_cod'] ?>,2)" class="fa fa-external-link e-i-1" aria-hidden="true"></i></td>
                                                                    <?php }else{ ?>
                                                                        <td>Inhabilitado</td>
                                                                        <td><i onclick="obtenerDetalleAperCierre(<?php echo $caja[$i]['caj_cod'] ?>,1)" class="fa fa-external-link e-i-1" aria-hidden="true"></i></td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <!--mostrar mensaje de alerta tipo info -->
                                                <div class="alert alert-info flat">
                                                    <i class="fa fa-info-circle"></i> No se han registrado cajas...
                                                </div>
                                        <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col p-2">
                                            <div class="table-responsive">
                                            <?php $sqlMovCajas = "select * from apertura_cierre ac inner join caja c on c.caj_cod = ac.caj_cod order by nro_aper desc"; ?>
                                            <?php $resMovCaja = consultas::get_datos($sqlMovCajas); ?>

                                            <h3 class="ml-1">Historial Cajas</h3>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Nro Apertura</th>
                                                            <th>Nro caja</th>
                                                            <th>Caja</th>
                                                            <th>Fecha apertura</th>
                                                            <th>Fecha cierre</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!empty($resMovCaja)){ ?>
                                                        <?php for($x=0;$x < count($resMovCaja);$x++){ ?>
                                                        <tr>
                                                            <td><?php echo $resMovCaja[$x]['nro_aper']; ?></td>
                                                            <td><?php echo $resMovCaja[$x]['caj_cod']; ?></td>
                                                            <td><?php echo $resMovCaja[$x]['caj_descri']; ?></td>
                                                            <td><?php echo $resMovCaja[$x]['aper_fecha']; ?></td>
                                                            <td><?php echo $resMovCaja[$x]['aper_cierre']; ?></td>
                                                            <?php if($resMovCaja[$x]['aper_cierre']==null){ ?>
                                                            <td>Caja Abierta</td>
                                                            <?php }else{ ?>
                                                            <td>Caja cerrada <button type="button" onclick="idDetalleCajaCerrada(<?php echo $resMovCaja[$x]['nro_aper']; ?>)" class="btn"><i class="fa fa-info text-warning"></i></button></td>

                                                            <?php } ?>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php }else{ ?>

                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->  
        
        <div class="modal fade" id="detalleVentas" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                        <h4 class="modal-title">Detalles</h4>
                    </div>                    
                        <div class="modal-body">
                            <div id="">
                            <h4 class="text-center">Apertura de caja</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Monto Apertura</th>
                                            <th>Fecha Apertura</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lisAperturaCaj">
                                        <tr>
                                            <td>Total efectivo</td>
                                            <td>Total credito</td>
                                        </tr>
                                    </tbody>
                                    
                                </table>
                            </div>
                            <h4 class="text-center">Cierre de caja</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Total efectivo</th>
                                            <th>Total credito</th>
                                            <th>Total Cierre</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lisCierreCaj">
                                        <tr>
                                            <td>Total efectivo</td>
                                            <td>Total credito</td>
                                            <td>Total Cierre</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h4 class="text-center">Detalles de venta</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Fecha</th>
                                            <th>Monto</th>
                                            <th>Tipo venta</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lisDetalleCaj">
                                        <tr>
                                            <td>Total efectivo</td>
                                            <td>Total credito</td>
                                            <td>Total Cierre</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">                            
                            <button data-dismiss="modal" class="btn btn-secundary"><i class="fa fa-close"></i>Cerrar</button>
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
        <!-- MODAL CONFIRMAR VENTA -->
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
        <!-- FIN MODAL CONFIRMAR VENTA -->
        


        </div>


<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button> -->

<!-- Modal -->
<div id="modalAperturaCierre" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Apertura y Cierre de caja</h4>
      </div>
        <form id="formAperturaCierre" action="apertura_cierre_caja_control.php" method="POST">
          <div class="modal-body">
            <label>Caja</label>
            <input type="text" class="form-control" value="" id="txtCaja">
            <input type="hidden" class="form-control" value="" name="txtCajah" id="txtCajah">
            <input type="hidden" class="form-control" value="" name="txtAccion" id="txtAccion">
            <label>Cedula</label>
            <input type="text" class="form-control" value="<?php echo $_SESSION['cedula'] ?>" name="txtCedula">
            <label>Nombre y Apellido</label>
            <input type="text" class="form-control" value="<?php echo $_SESSION['nombres'] ?>" name="txtNombre">
            <label>Monto apertura</label>
            <input type="number" class="form-control" value="0" name="txtMontoApertura" id="txtMontoApertura">
            <label>Fecha</label>
            <input type="input" class="form-control" value="<?php echo date('d-m-Y') ?>" name="txtFecha">
            <label>Hora</label>
            <input type="input" class="form-control" value="<?php echo date('h:i:s') ?>" name="txtHora">
            <label>Timbrado</label>

            <?php $sqlTimbrado = "select * from timbrado where vencimiento > '".date('Y-m-d')."'"; ?>
            <?php $resSqlTimbrado = consultas::get_datos($sqlTimbrado); ?>
            <br>
            <select class="form-control" id="slTimbrado" name="slTimbrado">
                <option value="">Seleccionar</option>
                <?php if(!empty($resSqlTimbrado)){ ?>
                <?php for($j=0; $j < count($resSqlTimbrado);$j++){ ?>
                <option value="<?php echo $resSqlTimbrado[$j]['cod_timbrado'] ?>"><?php echo $resSqlTimbrado[$j]['nro_timbrado'] ?></option>
                <?php } ?>
                <?php } else { ?>
                <option value="">Sin timbrado</option>
                <?php } ?>
            </select>
            <label>Vencimiento</label>
            <input type="text" class="form-control" id="vencimiento">   

            <div id="listArqueo">
            <h4 class="text-center">Cierre de caja</h4>
                <div class="">
                    <div class="" style="margin: 10px; background-color: #C2C2C2; padding: 10px;">
                        <div class="p-5">
                            <div class="">
                                <div class="">
                                    <label>Total efectivo</label>
                                    <input type="" class="form-control" name="" id="TotalEfectivo">
                                </div>
                                <div class="">
                                    <label>Total Credito</label>
                                    <input type="" class="form-control" name="" id="TotalCredito">
                                </div>
                            </div>
                            <div class="">
                                <div class="">
                                    <label>Total cierre</label>
                                    <input type="" class="form-control" name="" id="TotalCierre">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="text-center">Arqueo</h4>
                <div class="">
                    <div class="" style="margin: 10px; background-color: #C2C2C2; padding: 10px;">
                        <div class="p-5">
                            <div class="">
                                <div class="">
                                    <label>Total efectivo</label>
                                    <input type="" class="form-control" name="" id="TotalEfectivoArq">
                                </div>
                                <div class="">
                                    <label>Total Credito</label>
                                    <input type="" class="form-control" name="" id="TotalCreditoArq">
                                </div>
                            </div>
                            <br>
                            <button type="button" class="btn btn-success" style="margin: auto !important" id="botonGenerarArqueo">Generar Arqueo</button>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="botonModalAccion">n</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </form>
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
    function anular(datos){
        var dat = datos.split('_');
        $("#si").attr('href','ventas_control.php?vven_cod='+dat[0]+'&accion=3');
        $("#confirmacion").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea anular la venta N°<i><strong>'
                +dat[0]+'</strong> de fecha <i><strong>'+dat[2]+'</strong> y cliente <i><strong>'+dat[1]+'</strong></i>?');
    };    
    function confirmar(datos){
        var dat = datos.split('_');
        $("#sic").attr('href','ventas_control.php?vven_cod='+dat[0]+'&accion=2&vped_cod='+dat[3]);
        $("#confirmacionc").html('<span class="glyphicon glyphicon-warning-sign"></span> Desea confirmar la venta N°<i><strong>'
                +dat[0]+'</strong> de fecha <i><strong>'+dat[2]+'</strong> y cliente <i><strong>'+dat[1]+'</strong></i>?');
    };




    function obtenerDetalleAperCierre(nroCaja,accion){
        $('#modalAperturaCierre').modal('show');
        $('#txtCaja').val(nroCaja);
        $('#txtCajah').val(nroCaja);
        $('#txtAccion').val(accion);
        if (accion==1) {
            $("#botonModalAccion").text('Abrir caja');
            $("#listArqueo").hide();
        }else{
            $("#listArqueo").show();
            $.ajax({
                url: 'apertura_cierre_caja_control.php',
                type: 'POST',
                data: {nroCaja},
            })
            .done(function(r) {
                var json = JSON.parse(r);
                $("#txtMontoApertura").val(json.resConsCajaAperCierre[0]['monto_aper']);
                $("#TotalEfectivo").val(json.totalVentaContado);
                $("#TotalCredito").val(json.totalVentaCredito);
                $("#TotalCierre").val(json.totalCierre);
                var select = "";
                select += "<option>"+json.resConsCajaAperCierre[0]['nro_timbrado']+"<option>";
                $('#slTimbrado').html(select);
                alert(select);
                $("#vencimiento").val(json.resConsCajaAperCierre[0]['vencimiento']);
                

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
            $("#botonModalAccion").text('Cerrar caja');
        }

    }

    $('#formAperturaCierre').bind('submit', function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
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

    })

    $(document).on('click', "#botonGenerarArqueo", function(event){
        event.preventDefault();
        //var efectivo = $("#TotalEfectivo").val();
        //var credito = $("#TotalCredito").val();
        $('#TotalEfectivoArq').val($("#TotalEfectivo").val());
        $('#TotalCreditoArq').val($("#TotalCredito").val());
    })

    function idDetalleCajaCerrada(nro_aper){
        $("#detalleVentas").modal('show');
        $.ajax({
            url: 'apertura_cierre_caja_dcontrol.php',
            type: 'POST',
            data: {nro_aper},
        })
        .done(function(r) {
            var json = JSON.parse(r);
            var html1 = "";
            html1 += "<tr>";
            html1 += "<td>"+json.detalleCierre[0]['monto_aper']+"</td>";
            html1 += "<td>"+json.detalleCierre[0]['aper_fecha']+"</td>";
            html1 += "</tr>";
            $('#lisAperturaCaj').html(html1);
            var html2 = "";
            html2 += "<tr>";
            html2 += "<td>"+json.totalVentaContado+"</td>";
            html2 += "<td>"+json.totalVentaCredito+"</td>";
            html2 += "<td>"+json.totalCierre+"</td>";
            html2 += "</tr>";
            $('#lisCierreCaj').html(html2);


            var html3 = "";
            for (var i = 1; i < json.detalleVentas.length; i++) {
                json.detalleVentas[i][''];
                html3 += "<tr>";
                html3 += "<td>"+i+"</td>";
                html3 += "<td>"+json.detalleVentas[i]['ven_fecha']+"</td>";
                html3 += "<td>"+json.detalleVentas[i]['ven_total']+"</td>";
                html3 += "<td>"+json.detalleVentas[i]['tipo_venta']+"</td>";
                html3 += "</tr>";
            }
            $('#lisDetalleCaj').html(html3);
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

    $(document).on('change', '#slTimbrado', function () 
    {
        var timbrado = $('#slTimbrado').val();
        var accion = 'obtenerTimbrado';
        $.ajax({
            url: 'timbrado_control.php',
            type: 'POST',
            data: {timbrado,accion},
        })
        .done(function(r) {
            var json = JSON.parse(r);
            $("#vencimiento").val(json.timbrado[0].vencimiento);
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

    });

</script>
    </body>
</html>