<?php
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';

$accion = $_REQUEST['accion'];
if ($accion == 1) {
    $sql = "select COALESCE( max(etpr_id)+1, 1)  as etpr_id from etapas_produccion";
    $resultadoId = consultas::get_datos($sql);
    $descripcion = $_REQUEST['vetapas_desc'];
    $id = $resultadoId[0]['etpr_id'];
    $sql = "INSERT INTO etapas_produccion (etpr_id, etpr_descripcion) VALUES ($id, '$descripcion')";
    $resultadoInsert = consultas::ejecutar_sql($sql);
    if ($resultadoInsert) {
        $_SESSION['correcto'] = 'Se insertó correctamente la orden nro:'.$id;
        $_SESSION['error'] = '';
        header("location:etapas_index.php"); 

    }else{
        $_SESSION['error'] = 'Hubo un error:';
        $_SESSION['correcto'] = '';
        header("location:etapas_index.php"); 
    }

}elseif ($accion == 2){
    $id = $_REQUEST['vetapas_cod'];
    $descripcion = $_REQUEST['vetapas_desc'];
    $sql = "UPDATE etapas_produccion set etpr_descripcion ='".$descripcion."' where etpr_id = ".$id;
    $resultadoUpdate = consultas::ejecutar_sql($sql);
    // if ($resultadoUpdate) {
    //     $_SESSION['correcto'] = 'Se actualizó correctamente la etapa de produccion:'.$id;
    //     $_SESSION['error'] = '';
    //     header("location:etapas_edit.php?vmate_cod= ".$orpr_id); 

    // }else{
    //     $_SESSION['error'] = 'Hubo un error: Line 37';
    //     $_SESSION['correcto'] = '';
    //     header("location:etapas_edit.php?vmate_cod= ".$orpr_id); 
    // }

}elseif($accion == 3){

    $sql = "delete from etapas_produccion where etpr_id =".$_REQUEST['vmate_prima_cod'];
    $resultadoDelete = consultas::ejecutar_sql($sql);
    if ($resultadoDelete) {
        $_SESSION['correcto'] = 'Se eliminó correctamente la etapa de produccion:'.$_REQUEST['vorpr_id'];
        $_SESSION['error'] = '';
        header("location:orden_produccion_index.php"); 
    }else{
        $_SESSION['error'] = 'Hubo un error:';
        $_SESSION['correcto'] = '';
        header("location:orden_produccion_index.php"); 
    }

}
if ($resultado[0]['resul']!=null) {
    $valor = explode("*", $resultado[0]['resul']);
    $_SESSION['mensaje'] = $valor[0];
    header("location:etapas_index.php");
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:etapas_index.php");    
}