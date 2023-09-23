<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
#session_start();

$sqlCajaAbierto = "select * from apertura_cierre where usu_cod = '".$_SESSION['usu_cod']."' and aper_cierre is null";
$resCajaAbierto = consultas::get_datos($sqlCajaAbierto);
if (!empty($resCajaAbierto)) {
    if (isset($_REQUEST['metodo'])==1) {
        $depositoSel = $_REQUEST['opcion'];
        $sqlDep = "SELECT * FROM V_STOCK WHERE DEP_COD = '$depositoSel' AND STOC_CANT > 0"; 
        $resultadoArticulos = consultas::get_datos($sqlDep);
        $datos['articulos'] =  $resultadoArticulos;
        echo json_encode($datos);
        exit;
    }else{

        if(isset($_REQUEST['accion'])==3){
            $codDetalleVenta = $_REQUEST['vped_cod'];
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

       $sql = "select sp_pedventas(".$_REQUEST['accion'].",".(!empty($_REQUEST['vped_cod'])? $_REQUEST['vped_cod']:"0").","
                .$_SESSION['emp_cod'].",".(!empty($_REQUEST['vcli_cod'])? $_REQUEST['vcli_cod']:"0").",".$_SESSION['id_sucursal'].") as resul;";
        $resultado = consultas::get_datos($sql);

        if ($resultado[0]['resul']!=null) {
            $valor = explode("*", $resultado[0]['resul']);
            
            $_SESSION['mensaje'] = $valor[0];
            header("location:pedventas_index.php");
        }else{
            $_SESSION['mensaje'] = 'ERROR:'.$sql;
            $_SESSION['mensajeValid'] = 'ERROR:'.$sql;
            header("location:pedventas_index.php");    
        }
    }
}else{
    if (isset($_REQUEST['metodo'])==1) {
        $datos['mensajeValid'] = 'El usuario no tiene caja abierta, generar la apertura de caja antes de la accion';
        http_response_code(401);
        echo json_encode($datos);
        exit;
    }else{
        $_SESSION['mensajeValid'] = 'El usuario no tiene caja abierta, generar la apertura de caja antes de la accion';
        header("location:pedventas_index.php");       
    }
}

