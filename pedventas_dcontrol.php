<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
#session_start();

if ($_REQUEST['accion']==1) {
    $depCod = $_REQUEST['vdep_cod'];
    $artCod = explode('_',$_REQUEST['vart_cod']);
    $cant = $_REQUEST['vped_cant'];
     $sqlDep = "SELECT * FROM V_STOCK WHERE DEP_COD = '$depCod' AND STOC_CANT > 0 AND ART_COD = '$artCod[0]'"; 
    $resultadoArticulos = consultas::get_datos($sqlDep);

    $pedidoCod = $_REQUEST['vped_cod'];

    if ($resultadoArticulos[0]['stoc_cant']<$cant) {
        //$_SESSION['mensaje'] = 'La cantidad supera el stock del producto';
        $_SESSION['mensajeValid'] = 'La cantidad supera el stock del producto';
        header("location:pedventas_det.php?vped_cod=".$_REQUEST['vped_cod']);
        exit();
    }
}


if ($_REQUEST['accion']==3) {
    $pedidoCod = $_REQUEST['vped_cod'];
$sqlDetAnular = "select * from v_detalle_pedventa where ped_cod ='$pedidoCod'";
            $resultadoDetAnular = consultas::get_datos($sqlDetAnular);
}
$sql = "select sp_detalle_pedventa(".$_REQUEST['accion'].",".(!empty($_REQUEST['vped_cod'])? $_REQUEST['vped_cod']:"0").","
        .(!empty($_REQUEST['vdep_cod'])? $_REQUEST['vdep_cod']:"0").","
        .(!empty($_REQUEST['vart_cod'])? "split_part('".$_REQUEST['vart_cod']."','_',1)::integer":"0").","
        .(!empty($_REQUEST['vped_cant'])? $_REQUEST['vped_cant']:"0").","
        .(!empty($_REQUEST['vped_precio'])? $_REQUEST['vped_precio']:"0").") as resul;";


$resultado = consultas::get_datos($sql);
       


if ($resultado[0]['resul']!=null) {
    if ($_REQUEST['accion']==1 or $_REQUEST['accion']==3) {
        $depCod = $_REQUEST['vdep_cod'];
        $artCod = explode('_',$_REQUEST['vart_cod']);
        $sqlActStock = "SELECT * FROM V_STOCK WHERE DEP_COD = '$depCod' AND STOC_CANT > 0 AND ART_COD = '$artCod[0]'"; 
        $resultadoActStoc = consultas::get_datos($sqlActStock);
        if ($_REQUEST['accion']==1) {
        $totalActualStock = $resultadoArticulos[0]['stoc_cant'];
            $cantidad = $_REQUEST['vped_cant'];
            $totalStock = abs($totalActualStock-$cantidad);
            // code...
            $sqlActualizar = "select sp_stock('2','$depCod','$artCod[0]','0','$totalStock') as resul;";
            $resultadoActualizado = consultas::get_datos($sqlActualizar);
        }else if ($_REQUEST['accion']==3) {
            $sqlDep = "SELECT * FROM V_STOCK WHERE DEP_COD = '$depCod' AND STOC_CANT > 0 AND ART_COD = '$artCod[0]'"; 
            $resultadoArticulos = consultas::get_datos($sqlDep);
            $totalActualStock = $resultadoArticulos[0]['stoc_cant'];
            $totalStockQuitar = abs($totalActualStock+$resultadoDetAnular[0]['ped_cant']);
            $sqlActualizarQuitar = "select sp_stock('2','$depCod','$artCod[0]','0','$totalStockQuitar') as resul;";
            $resultadoActualizado = consultas::get_datos($sqlActualizarQuitar);
        }
    }

    $_SESSION['mensaje'] = $resultado[0]['resul'];
   header("location:pedventas_det.php?vped_cod=".$_REQUEST['vped_cod']);
}else{
    $_SESSION['mensajeValid'] = 'ERROR:'.$sql;
   header("location:pedventas_det.php?vped_cod=".$_REQUEST['vped_cod']);
}