<?php
session_start();
#session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';

require 'clases/conexion.php';

$sql = "select sp_articulo(".$_REQUEST['accion'].",".(!empty($_REQUEST['vart_cod'])? $_REQUEST['vart_cod']:"0").",'"
        .(!empty($_REQUEST['vart_codbarra'])? $_REQUEST['vart_codbarra']:"0")."',".(!empty($_REQUEST['vmar_cod'])? $_REQUEST['vmar_cod']:"0").",'"
        .(!empty($_REQUEST['vart_descri'])? $_REQUEST['vart_descri']:"0")
        ."',".(!empty($_REQUEST['vart_precioc'])? $_REQUEST['vart_precioc']:"0").","
        .(!empty($_REQUEST['vart_preciov'])? $_REQUEST['vart_preciov']:"0").","
        .(!empty($_REQUEST['vtipo_cod'])? $_REQUEST['vtipo_cod']:"0").") as resul;";

$resultado = consultas::get_datos($sql);


if ($resultado[0]['resul']!=null) {
    $valor = explode("*", $resultado[0]['resul']);
    $_SESSION['mensaje'] = $valor[0];
    header("location:".$valor[1].".php");
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:articulo_index.php");    
}