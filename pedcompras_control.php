<?php
session_start();
#require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
#session_start();
if ($_REQUEST['metodo']==1) {
     $depositoSel = $_REQUEST['opcion'];
        $sqlDep = "SELECT * FROM V_STOCK WHERE DEP_COD = '$depositoSel' AND STOC_CANT > 0"; 
        $resultadoArticulos = consultas::get_datos($sqlDep);
        $datos['articulos'] =  $resultadoArticulos;
        echo json_encode($datos);
        exit;
}else{

$sql = "select sp_pedcompras(".$_REQUEST['accion'].",".(!empty($_REQUEST['vped_cod'])? $_REQUEST['vped_cod']:"0").","
        .$_SESSION['emp_cod'].",".(!empty($_REQUEST['vprv_cod'])? $_REQUEST['vprv_cod']:"0").",".$_SESSION['id_sucursal'].") as resul;";


$resultado = consultas::get_datos($sql);


if ($resultado[0]['resul']!=null) {
    $valor = explode("*", $resultado[0]['resul']);
    $_SESSION['mensaje'] = $valor[0];
    header("location:pedcompras_index.php");
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:pedcompras_index.php");    
}   
}