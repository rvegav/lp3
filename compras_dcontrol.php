<?php
session_start();
#require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
$sql = "select sp_detalle_compras(".$_REQUEST['accion'].",".(!empty($_REQUEST['vcom_cod'])? $_REQUEST['vcom_cod']:"0").","
        .(!empty($_REQUEST['vdep_cod'])? $_REQUEST['vdep_cod']:"0").","
        .(!empty($_REQUEST['vart_cod'])? "split_part('".$_REQUEST['vart_cod']."','_',1)::integer":"0").","
        .(!empty($_REQUEST['vcom_cant'])? $_REQUEST['vcom_cant']:"0").","
        .(!empty($_REQUEST['vcom_precio'])? $_REQUEST['vcom_precio']:"0").") as resul;";


$resultado = consultas::get_datos($sql);

//echo $sql;
if ($resultado[0]['resul']!=null) {

    $_SESSION['mensaje'] = $resultado[0]['resul'];
    header("location:compras_det.php?vcom_cod=".$_REQUEST['vcom_cod']);
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:compras_det.php?vcom_cod=".$_REQUEST['vcom_cod']);
}