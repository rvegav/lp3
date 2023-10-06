<?php
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';

$accion = $_REQUEST['accion'];
if ($accion == 1) {
    $sql = "select max(mapr_id)+1 as mapr_id from materia_prima";
    $resultadoId = consultas::get_datos($sql);
    $descripcion = $_REQUEST['vmate_prima_descri'];
    $id = $resultadoId[0]['mapr_id'];
    $sql = "INSERT INTO materia_prima (mapr_id, mapr_descripcion) VALUES ($id, $descripcion)";
    $resultadoInsert = consultas::ejecutar_sql($sql);
    if ($resultadoInsert) {
        $_SESSION['correcto'] = 'Se insertó correctamente la orden nro:'.$id;
        $_SESSION['error'] = '';
        header("location:materia_prima_index.php"); 

    }else{
        $_SESSION['error'] = 'Hubo un error:';
        $_SESSION['correcto'] = '';
        header("location:materia_prima_index.php"); 
    }

}elseif ($accion == 2){
    $id = $_REQUEST['vmate_prima_cod'];
    $descripcion = $_REQUEST['vmate_prima_descri'];
    $sql = "UPDATE materia_prima set mapr_descripcion =".$descripcion." where mapr_id = ".$id;
    $resultadoUpdate = consultas::ejecutar_sql($sql);
    if ($resultadoUpdate) {
        $_SESSION['correcto'] = 'Se actualizó correctamente la materia_prima:'.$id;
        $_SESSION['error'] = '';
        header("location:materia_prima_edit.php?vmate_cod= ".$orpr_id); 

    }else{
        $_SESSION['error'] = 'Hubo un error:';
        $_SESSION['correcto'] = '';
        header("location:materia_prima_edit.php?vmate_cod= ".$orpr_id); 
    }

}elseif($accion == 3){

    $sql = "delete from materia_prima where mapr_id =".$_REQUEST['vmate_prima_cod'];
    $resultadoDelete = consultas::ejecutar_sql($sql);
    if ($resultadoDelete) {
        $_SESSION['correcto'] = 'Se eliminó correctamente la materia_prima:'.$_REQUEST['vorpr_id'];
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
    header("location:materia_prima_index.php");
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:materia_prima_index.php");    
}