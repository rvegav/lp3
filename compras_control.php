<?php
session_start();
#require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';

$compraCod = $_REQUEST['vcom_cod'];
$accion = $_REQUEST['accion'];

    $sql = "select sp_compras(".$_REQUEST['accion'].",".(!empty($_REQUEST['vcom_cod'])? $_REQUEST['vcom_cod']:"0").","
         .$_SESSION['emp_cod'].",".(!empty($_REQUEST['vprv_cod'])? $_REQUEST['vprv_cod']:"0")
        .",'".(!empty($_REQUEST['vtipo_compra'])? $_REQUEST['vtipo_compra']:"null")."',"
            .(!empty($_REQUEST['vcan_cuota'])? $_REQUEST['vcan_cuota']:"0").","
            .(!empty($_REQUEST['vcom_plazo'])? $_REQUEST['vcom_plazo']:"0").",".$_SESSION['id_sucursal'].","
            .(!empty($_REQUEST['vped_cod'])? $_REQUEST['vped_cod']:"0").") as resul;";
    $resultado = consultas::get_datos($sql);






    if ($resultado[0]['resul']!=null) {
        $valor = explode("*", $resultado[0]['resul']);
        $_SESSION['mensaje'] = $valor[0];
        if($accion==2){
                $sqlPedidoCompra = "SELECT * FROM pedido_compra WHERE com_cod = '$compraCod'";
                $resultadoPedComp = consultas::get_datos($sqlPedidoCompra);
                $codPedido = $resultadoPedComp[0]['ped_cod'];
                $sqlActuCabeCompras = "UPDATE pedido_cabcompra SET estado = 'C' WHERE ped_cod = '$codPedido'"; 
                $resultadoActuCabCompras = consultas::get_datos($sqlActuCabeCompras);
                $sqlCompra = "SELECT * FROM COMPRAS WHERE com_cod= '$compraCod'"; 
                $resultadoCompra = consultas::get_datos($sqlCompra);
                $sqlDetalleCompra = "SELECT sum(com_cant * com_precio) total_venta from detalle_compras where com_cod ='$compraCod'" ;
                $resultadoDetalleCompra = consultas::get_datos($sqlDetalleCompra);
                if ($resultadoCompra[0]['tipo_compra']=='CONTADO') {
                    $nro_cuota = $resultadoCompra[0]['can_cuota'] ;
                    $monto_cuota = $resultadoDetalleCompra[0]['total_venta'];
                    $saldo_cuota = $monto_cuota;
                    $estado = 'P';
                    $sqlInsert = "INSERT INTO ctas_a_pagar (nro_cuota, monto_cuota, saldo_cuota, estado_cuota, com_cod) VALUES ($nro_cuota, $monto_cuota, $saldo_cuota, '$estado',$compraCod)";
                    $resultadoInsert = consultas::get_datos($sqlInsert);
                }else{
                    $monto_primer_cuota = (int)($resultadoDetalleCompra[0]['total_venta']/$resultadoCompra[0]['can_cuota']);
                    $saldo = $resultadoDetalleCompra[0]['total_venta'];
                    $nro_cuota = 0;
                    while ($saldo >= $monto_primer_cuota) {
                        $nro_cuota ++;
                        $monto_cuota = $monto_primer_cuota;
                        $saldo_cuota = $monto_cuota;
                        $estado = 'P';
                        $saldo = $saldo - $monto_primer_cuota;
                        $sqlInsert = "INSERT INTO ctas_a_pagar (nro_cuota, monto_cuota, saldo_cuota, estado_cuota, com_cod) VALUES ($nro_cuota, $monto_cuota, $saldo_cuota, '$estado',$compraCod)";
                        $resultadoInsert = consultas::get_datos($sqlInsert);
                    }
                    $sqlInsert = "INSERT INTO ctas_a_pagar (nro_cuota, monto_cuota, saldo_cuota, estado_cuota, com_cod) VALUES ($nro_cuota, $saldo, $saldo_cuota, '$estado',$compraCod)";
                    $resultadoInsert = consultas::get_datos($sqlInsert);
                }
        }
        header("location:$valor[1]");
    }else{
        $_SESSION['mensaje'] = 'ERROR:'.$sql;
        header("location:compras_index.php");    
    }


