<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
#session_start();

$sqlCaja = "select * from apertura_cierre where usu_cod = '".$_SESSION['usu_cod']."' and aper_cierre is null";
$resultadoCaja = consultas::get_datos($sqlCaja);
$_REQUEST['accion'];
if (!empty($resultadoCaja)) {
    if ($_REQUEST['accion']==3) {
        // code...
        $codVenta = $_REQUEST['vven_cod'];
        $sqlVentaAnular = "select * from v_ventas where ven_cod = ".$codVenta;
        $resultadoVentaAnular = consultas::get_datos($sqlVentaAnular);
        $codDetalleVenta = $resultadoVentaAnular[0]['ped_cod'];
        $sqlDetalleVenta = "select * from v_detalle_pedventa where ped_cod =".$codDetalleVenta;
        $resultadoDetalleVenta = consultas::get_datos($sqlDetalleVenta);
            
        if (!empty($resultadoDetalleVenta)) {
            for ($i=0; $i < count($resultadoDetalleVenta); $i++) { 
                $cantArtAnular = $resultadoDetalleVenta[$i]['ped_cant'];
                $depCod = $resultadoDetalleVenta[$i]['dep_cod'];
                $artCod = $resultadoDetalleVenta[$i]['art_cod'];
                $sqlDep = "SELECT * FROM V_STOCK WHERE DEP_COD = '$depCod' AND STOC_CANT > 0 AND ART_COD = '$artCod'"; 
                $resultadoArticuloStock = consultas::get_datos($sqlDep);
                $totalActualStock = $resultadoArticuloStock[0]['stoc_cant'];
                $totalStockQuitar = abs($totalActualStock+$cantArtAnular);
                $sqlActualizarQuitar = "select sp_stock('2','$depCod','$artCod','0','$totalStockQuitar') as resul;";
                $resultadoActualizado = consultas::get_datos($sqlActualizarQuitar);
            }
        }
    }

    if ($_REQUEST['accion']==2) {
        $codTimbrado = $resultadoCaja[0]['cod_timbrado'];
        $consTimbrado = "select * from timbrado where cod_timbrado = '$codTimbrado'";
        $resConsTimbrado = consultas::get_datos($consTimbrado);
        if (!empty($resConsTimbrado)) {
            $fechaActual = date('Y-m-d');$fechaVencimiento = $resConsTimbrado[0]['vencimiento'];
            if (strtotime($fechaActual)>strtotime($fechaVencimiento)) {
                $_SESSION['mensajeValid'] = 'ERROR: La fecha actual ya ha superado la fecha de vencimiento del timbrado';
                header("location:ventas_index.php"); 
                exit;
            }
        }else{
            $_SESSION['mensajeValid'] = 'ERROR: error inesperado, no se ha encontrado el timbrado.';
            header("location:ventas_index.php"); 
            exit;
        }
    }

    $sql = "select sp_ventas(".$_REQUEST['accion'].",".(!empty($_REQUEST['vven_cod'])? $_REQUEST['vven_cod']:"0").","
         .$_SESSION['emp_cod'].",".(!empty($_REQUEST['vcli_cod'])? $_REQUEST['vcli_cod']:"0")
        .",'".(!empty($_REQUEST['vtipo_venta'])? $_REQUEST['vtipo_venta']:"null")."',"
            .(!empty($_REQUEST['vcan_cuota'])? $_REQUEST['vcan_cuota']:"0").","
            .(!empty($_REQUEST['vven_plazo'])? $_REQUEST['vven_plazo']:"0").",".$_SESSION['id_sucursal'].","
            .(!empty($_REQUEST['vped_cod'])? $_REQUEST['vped_cod']:"0").") as resul;";

   $resultado = consultas::get_datos($sql);
   if (!empty($resultado[0]['resul'])) {
        if($_REQUEST['accion']==1){
            $sqlUltimoId = "select max(ven_cod) from ventas";
            $resUltimoId = consultas::get_datos($sqlUltimoId);
            $idVendCod = $resUltimoId[0]['max'];
            $sqlPedidoVenta = "select * from pedido_venta where ven_cod = '$idVendCod'";
            $resPedidoVenta = consultas::get_datos($sqlPedidoVenta);
            if (!empty($resPedidoVenta)) {
                $pedCod = $resPedidoVenta[0]['ped_cod'];
                $sqlDetallePedidoVenta = "select * from detalle_pedventa where ped_cod = '$pedCod'";
                $resDetalleVentas = consultas::get_datos($sqlDetallePedidoVenta);
                $totalPrecio = 0;
                for ($i=0; $i < count($resDetalleVentas); $i++) { 
                    $totalPrecio += $resDetalleVentas[$i]['ped_precio']*$resDetalleVentas[$i]['ped_cant'];
                }
                if (!empty($resUltimoId)) {
                    $resUltimoId[0]['max'];
                    $actualizarVentaCaja = "update ventas set nro_aper = ".$resultadoCaja[0]['nro_aper'].", caj_cod = ".$resultadoCaja[0]['caj_cod'].",ven_total = ".$totalPrecio." where   ven_cod = ".$resUltimoId[0]['max'];
                    $resActuVentaCaja = consultas::get_datos($actualizarVentaCaja);
                }
            }
            $sql = "select * from ventas where ven_cod = ".$idVendCod;
            $ultimaVenta = consultas::get_datos($sql);
            $plazo = $_REQUEST['vven_plazo'];
            if ($_REQUEST['vtipo_venta']=='CONTADO') {            
                $nro_cuota = $_REQUEST['vcan_cuota'];
                $monto_cuota = $ultimaVenta[0]['ven_total'];
                $fecha_venc = date('d-m-Y H:i:s');
                $saldo_cuota = $monto_cuota;
                $estado_cuota  = 'P';
                $sqlInsert = "INSERT INTO ctas_a_cobrar (nro_cuota, monto_cuota, saldo_cuota, estado_cuota, fecha_venc, ven_cod) VALUES ($nro_cuota, $monto_cuota, $saldo_cuota, '$estado_cuota','$fecha_venc', $idVendCod)";
                $resultadoInsert = consultas::get_datos($sqlInsert);
            }else{
                $monto_cuota = (int)($ultimaVenta[0]['ven_total']/$ultimaVenta[0]['can_cuota']);
                $saldo = $ultimaVenta[0]['ven_total'];
                $fecha_venc =   date('d-m-Y', strtotime($fecha_venc. ' +'+$plazo+' days'));
                $nro_cuota = 0;
                $estado_cuota = 'P';
                while ($saldo >= $monto_cuota) {
                    $nro_cuota ++;
                        // $saldo_cuota = $monto_cuota;
                    $estado = 'P';
                    $saldo = $saldo - $monto_cuota;
                    $sqlInsert = "INSERT INTO ctas_a_cobrar (nro_cuota, monto_cuota, saldo_cuota, fecha_venc, estado_cuota, ven_cod) VALUES ($nro_cuota, $monto_cuota, $saldo, '$fecha_venc', '$estado_cuota',$idVendCod)";
                    $resultadoInsert = consultas::get_datos($sqlInsert);
                    $fecha_venc = date('d-m-Y', strtotime($fecha_venc.  ' +'+$plazo+' days'));

                }
                if ($saldo > 0) {
                    $sqlInsert = "INSERT INTO ctas_a_cobrar (nro_cuota, monto_cuota, saldo_cuota, fecha_venc, estado_cuota, ven_cod) VALUES ($nro_cuota++, $saldo, $saldo, '$fecha_venc', '$estado_cuota',$idVendCod)";
                    $resultadoInsert = consultas::get_datos($sqlInsert);
                }
            }
        }
    $valor = explode("*", $resultado[0]['resul']);
        $_SESSION['mensaje'] = $valor[0];
        $sql2 = "select sp_pedventas(5,".(!empty($_REQUEST['vped_cod'])? $_REQUEST['vped_cod']:"0").","
            .$_SESSION['emp_cod'].",".(!empty($_REQUEST['vcli_cod'])? $_REQUEST['vcli_cod']:"0").",".$_SESSION['id_sucursal'].") as resul;";
        consultas::get_datos($sql2);
        header("location:$valor[1]");
    }else{
        $_SESSION['mensajeValid'] = 'ERROR:'.$sql;
        header("location:ventas_index.php");    
    }
}else{
    $_SESSION['mensajeValid'] = 'No hay caja abierta para la venta';
    header("location:ventas_index.php");
}