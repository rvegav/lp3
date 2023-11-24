<?php
session_start();
        // require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/consultasAjax.php';
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
    require 'menu/css_lte.ctp'; ?>

</head>
<body class="hold-transition skin-blue sidebar-mini" onload="compras()">
    <div class="wrapper">
        <?php require 'menu/header_lte.ctp'; ?>
        <?php require 'menu/toolbar_lte.ctp';?>
        <div class="content-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <i class="ion ion-plus"></i>
                                <h3 class="box-title">Cuentas a pagar</h3>
                                <a href="cuentas_pagar_index.php" class="btn btn-primary pull-right btn-sm" role="buttom" data-title ="Volver"
                                rel="tooltip" data-placement="top">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                        </div>
                        <form action="factura_compra_control.php" method="get" accept-charset="utf-8" class="form-horizontal" id="frm_fact_compra">
                            <div class="box-body">
                                <input type="hidden" name="accion" value="2"/>
                                <input type="hidden" name="vped_cod" value="0"/> 
                                <div class="row">
                                    <div class="col-xs-12 col-lg-6 col-md-6">
                                        <?php $fecha = consultas::get_datos("select current_date as fecha");?>
                                        <label>Fecha:</label>
                                        <input type="date" name ="vped_fecha" class="form-control" required="" value="<?php echo $fecha[0]['fecha']?>"/>
                                    </div>
                                    <div class="col-xs-12 col-lg-6 col-md-6">

                                        <label>Nro Factura:</label>
                                        <input type="text" name ="vnro_factura"  id="nro_factura" class="form-control"required="">
                                    </div>
                                    <div class="col-xs-12 col-lg-6 col-md-6">
                                        <label>Timbrado N°:</label>
                                        <input type="text" name ="vnro_timbrado" class="form-control" required=""/>
                                    </div>
                                    <div class="col-xs-12 col-lg-6 col-md-6">
                                        <label>Fecha de vencimiento:</label>
                                        <input type="date" name ="vped_fecha" class="form-control" required="" min="<?php echo $fecha[0]['fecha']?>" />
                                    </div>
                                    <div class="col-xs-12 col-lg-6 col-md-6">
                                        <label> Metodo de Pago:</label>
                                        <div >
                                            <select class="form-control select2" name="vprv_cod" required="" id="proveedor" onchange="compras()">
                                                <option value="E">Efectivo</option>
                                                <option value="T">Tarjeta</option>
                                            </select>  

                                        </div>                                                                                                                                                  
                                    </div>
                                    <div class="col-xs-12 col-lg-6 col-md-6">
                                        <label> Proveedor:</label>
                                        <div >
                                            <?php $proveedor= consultas::get_datos("select prv_razonsocial, p.prv_cod from ctas_a_pagar c join compras co on co.com_cod = c.com_cod join proveedor p on p.prv_cod = co.prv_cod");?>
                                            <input class="form-control" name="vprv_nombre" type="text"  required="" readonly> value="<?php echo $proveedor[0]['prv_razonsocial'] ?>" />
                                            <input class="form-control" name="vprv_cod" type="hidden" required="" value="<?php echo $proveedor[0]['prv_cod'] ?>" />

                                        </div>                                                                                                                                                  
                                    </div>
                                </div>                   

                                <br> 
                                <div class="row">
                                    <div class="col-xs-12 col-lg-12 col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-condensed table-striped table-hover table-bordered" id="cuenta_pagar">
                                                <thead>
                                                    <!-- <th>#</th> -->
                                                    <th>Nro Cuota</th>
                                                    <th>Monto Cuota</th>
                                                    <th>Fecha de Vencimiento</th>
                                                    <th>Total</th>
                                                </thead>
                                                <?php $cuota = consultas::get_datos("select * from ctas_a_pagar where ctpa_id =".$_REQUEST['vctpa_id']); ?>
                                                <tbody>
                                                    <?php foreach ($cuota as $detalle): ?>
                                                        <tr>
                                                            <td><?php echo $detalle['nro_cuota'] ?></td>
                                                            <td><?php echo $detalle['monto_cuota'] ?></td>
                                                            <td><?php echo $detalle['fecha_venc'] ?></td>
                                                            <td><?php echo $detalle['monto_cuota'] ?></td>
                                                            <input type="hidden" name="vfact_monto" value="<?php echo $detalle['monto_cuota'] ?>">
                                                            <input type="hidden" name="vctpa_id" value="<?php echo $_REQUEST['vctpa_id']?>">
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>                                      
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary pull-right">
                                    <span class="glyphicon glyphicon-floppy-disk"></span> Registrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>                      
            </div>
        </div>
    </div>
    <?php require 'menu/footer_lte.ctp'; ?><!--ARCHIVOS JS-->  
    <!-- MODAL CARGO AGREGAR --> 
    <div class="modal fade" id="verDetalle" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title"> Detalles de la compra</h4>
              </div>
              <div class="modal-body" id="detalle_compras_facturas">
                        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="detalle_compras_facturas">
                            
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="reset" data-dismiss="modal" class="btn btn-default"><i class="fa fa-close"></i> Cerrar</button>
                    </div>
                </div>
            </div>            
        </div>       
        <!-- FIN MODAL CARGO AGREGAR -->                  
    </div>                  
    <?php require 'menu/js_lte.ctp'; ?><!--ARCHIVOS JS-->
    <script type="text/javascript">
            // console.log($('#vcom_cod').val());
        const mostrar =()=>{
            let valor = $('#tipo_detalle').val();
            if (valor == 1) {
                $('#agregar_orden').show();
                $('#agregar_producto').hide();
            }else if(valor ==2){
                articulos();
                $('#agregar_producto').show();
                $('#agregar_orden').hide();
            }
        }
        const compras =()=>{
            $.ajax({
               type: "GET",
               url :"/lp3/factura_compra.php?vprv_cod="+$('#proveedor').val()+"&accion=1",
               cache: false,
               beforeSend:function(){
                   $("#compras_cabecera").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>');
               },
               success : function(data){
                   $("#compras_cabecera").html(data);
               }
           });
        };
        const detalles =()=>{
            $.ajax({
               type: "GET",
               url :"/lp3/factura_detalle_compras.php?vcom_cod="+$('#compra_select').val()+"&vaccion=1",
               cache: false,
               beforeSend:function(){
                   $("#detalle_compras_facturas").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>');
               },
               success : function(data){
                   $("#detalle_compras_facturas").html(data);
               }
           });
        };
        const articulos = ()=>{
            $.ajax({
               type: "GET",
               url :"/lp3/factura_compra.php?vprv_cod="+$('#proveedor').val()+"&accion=2",
               cache: false,
               beforeSend:function(){
                   $("#articulos").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>');
               },
               success : function(data){
                   $("#articulos").html(data);
               }
           });
        }

        const quitarOpcion= ()=> {
          var x = document.getElementById("compra_select");
          x.remove(x.selectedIndex);
      }
      let item =0;
      const agregar = (accion)=>{
        if (accion==1) {
            let valor_compra =$('#compra_select').val()
            item ++;
            if (valor_compra!='') {
                $('<input />', {
                    type:'hidden',
                    name:'cod_com[]',
                    value: $('#compra_select').val()
                }).appendTo($('#frm_fact_compra'));
                $.ajax({
                   type: "GET",
                   url :"/lp3/factura_detalle_compras_add.php?vcom_cod="+$('#compra_select').val()+'&vitem='+item+'&accion=1',
                   cache: false,
                   beforeSend:function(){
                       $("#detalle_compras_facturas").html('<img src="img/ajax-loader.gif"/> <strong>Cargando...</strong>');
                   },
                   success : function(data){
                    if (data!='Sin datos') {
                        $("#cuenta_pagar tbody").append(data);
                        quitarOpcion();
                    }
                }
            });

            }else{
                alert('Debe seleccionar al menos una compra');
            }

        }else if(accion==2){
            let articulo =$('#articulo_select').val();
            let cantidad =$('#cant_add').val();
            let precio =$('#precio_add').val();
                    // alert(articulo);
            $.ajax({
               type: "GET",
               url :"/lp3/factura_detalle_compras_add.php?vart_cod="+articulo+"&accion=2",
               cache: false,
               success : function(data){
                if (cantidad>0&&precio>0) {
                    let   html ='';

                    html+='<tr>';
                    html+='<input type="hidden" name="articulos[\'cod\'][]" value="'+articulo+'">';
                    html+='<td>';
                    html+= data;
                    html+='</td>';
                    html+='<input type="hidden" name="articulos[\'cant\'][]" value="'+cantidad+'">';
                    html+='<td>';
                    html+=cantidad;
                    html+='</td>';
                    html+='<input type="hidden" name="articulos[\'precio\'][]" value="'+precio+'">';
                    html+='<td>';
                    html+=formatNumber.new(precio, "");
                    html+='</td>';
                    html+='<td>';
                    html+= formatNumber.new((precio*cantidad),"");
                    html+='</td>';
                    html+='<td>';
                    html+='<button class="btn btn-danger btn-sm" type="button" data-title="Anular" rel="tooltip" data-placement="top"><i class="fa fa-close"></i></button>';
                    html+='</td>';
                    html+='</tr>';
                    $("#cuenta_pagar tbody").append(html);

                }

            }
        });
        }

    }

    const formatNumber = {
               separador: ".", // separador para los miles
               sepDecimal: ',', // separador para los decimales
               formatear:function (num){
                   num +='';
                   var splitStr = num.split('.');
                   var splitLeft = splitStr[0];
                   var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
                   var regx = /(\d+)(\d{3})/;
                   while (regx.test(splitLeft)) {
                       splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
                   }
                   return this.simbol + splitLeft +splitRight;
               },
               new:function(num, simbol){
                   this.simbol = simbol ||'';
                   return this.formatear(num);
               }
           }
       </script>
   </body>
   </html>


