<?php
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
//echo $_REQUEST['vmar_descri'];

require 'clases/conexion.php';

$sql = "select sp_marca(".$_REQUEST['accion'].",".$_REQUEST['vmar_cod'].",'".$_REQUEST['vmar_descri']."') as resul;";

#session_start();
$resultado = consultas::get_datos($sql);

if ($resultado[0]['resul']!==null) {
    $_SESSION['mensaje'] = $resultado[0]['resul'];
    header("location:marca_index.php");
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:marca_index.php");    
}
