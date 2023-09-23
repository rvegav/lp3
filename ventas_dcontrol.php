<?php
session_start();
require 'acceso_bloquear_compras.php';
#require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';

$sql = "select sp_detalle_ventas(".$_REQUEST['accion'].",".(!empty($_REQUEST['vven_cod'])? $_REQUEST['vven_cod']:"0").","
        .(!empty($_REQUEST['vdep_cod'])? $_REQUEST['vdep_cod']:"0").","
        .(!empty($_REQUEST['vart_cod'])? "split_part('".$_REQUEST['vart_cod']."','_',1)::integer":"0").","
        .(!empty($_REQUEST['vven_cant'])? $_REQUEST['vven_cant']:"0").","
        .(!empty($_REQUEST['vven_precio'])? $_REQUEST['vven_precio']:"0").") as resul;";


$resultado = consultas::get_datos($sql);

//echo $sql;
if ($resultado[0]['resul']!=null) {
    $_SESSION['mensaje'] = $resultado[0]['resul'];
    header("location:ventas_det.php?vven_cod=".$_REQUEST['vven_cod']);
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:ventas_det.php?vven_cod=".$_REQUEST['vven_cod']);
}