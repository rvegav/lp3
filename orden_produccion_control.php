<?php
require 'acceso_bloquear_ventas.php';
session_start();
require 'clases/conexion.php';
if ($_REQUEST['accion']==1) {
    $id = $_REQUEST['orpr_id'];
    $fecha = $_REQUEST['vorden_fecha'];
    $sql = "SELECT orpr_id FROM orden_produccion WHERE orpr_id = ".$id; 
    $resultadoControl = consultas::get_datos($sql);
    if ($resultadoControl) {
        $sql = "select max(orpr_id)+1 as orpr_id from orden_produccion";
        $resultadoId = consultas::get_datos($sql);
        $id = $resultadoId[0]['orpr_id'];
    }
    $sql = "INSERT INTO orden_produccion (orpr_id, orpr_fecha_pedido, orpr_estado) VALUES ($id, TO_DATE(' $fecha','YYYY-MM-DD'), 'P')";
    $resultadoInsert = consultas::ejecutar_sql($sql);
    if ($resultadoInsert) {
        $_SESSION['correcto'] = 'Se insertó correctamente la orden nro:'.$id;
        $_SESSION['error'] = '';
        header("location:orden_produccion_index.php"); 

    }else{
        $_SESSION['error'] = 'Hubo un error:';
        $_SESSION['correcto'] = '';
        header("location:orden_produccion_index.php"); 
    }

}elseif($_REQUEST['accion']==3){
    $sql = "delete from orden_produccion where orpr_id =".$_REQUEST['vorpr_id'];
    $resultadoDelete = consultas::ejecutar_sql($sql);
    if ($resultadoDelete) {
        $_SESSION['correcto'] = 'Se eliminó correctamente la orden nro:'.$_REQUEST['vorpr_id'];
        $_SESSION['error'] = '';
        header("location:orden_produccion_index.php"); 
    }else{
        $_SESSION['error'] = 'Hubo un error:';
        $_SESSION['correcto'] = '';
        header("location:orden_produccion_index.php"); 
    }

}elseif($_REQUEST['accion']==4){
    $art_id = $_REQUEST['vart_cod'];
    $vart_cantidad = $_REQUEST['vart_cant'];
    $orpr_id = $_REQUEST['vorpr_id'];
    $sql = "SELECT  COALESCE(max(deor_id)+1, 1) as deor_id from detalle_orden_prod";
    $resultadoId = consultas::get_datos($sql);
    $id = $resultadoId[0]['deor_id'];
    $sql = "INSERT INTO detalle_orden_prod (deor_id, deor_art_id, deor_orpr_id, deor_cantidad) VALUES ($id, $art_id, $orpr_id, $vart_cantidad)";

    $resultadoInsert = consultas::ejecutar_sql($sql);
    if ($resultadoInsert) {
        $_SESSION['correcto'] = 'Se insertó correctamente la orden nro:'.$id;
        $_SESSION['error'] = '';
        header("location:orden_produccion_detalle.php?vorpr_id= ".$orpr_id); 

    }else{
        $_SESSION['error'] = 'Hubo un error:';
        $_SESSION['correcto'] = '';
        header("location:orden_produccion_detalle.php?vorpr_id= ".$orpr_id); 
    }
}elseif ($_REQUEST['accion']==5) {
    $vdeor_id = $_REQUEST['vdeor_id'];
    $orpr_id = $_REQUEST['vorpr_id'];
    $cantidad = $_REQUEST['vart_cantidad'];
    $sql = "UPDATE detalle_orden_prod set deor_cantidad =".$cantidad." where deor_id = ".$vdeor_id;
    $resultadoUpdate = consultas::ejecutar_sql($sql);
    if ($resultadoUpdate) {
        $_SESSION['correcto'] = 'Se actualizó correctamente la orden nro:'.$id;
        $_SESSION['error'] = '';
        header("location:orden_produccion_detalle.php?vorpr_id= ".$orpr_id); 

    }else{
        $_SESSION['error'] = 'Hubo un error:';
        $_SESSION['correcto'] = '';
        header("location:orden_produccion_detalle.php?vorpr_id= ".$orpr_id); 
    }
}elseif ($_REQUEST['accion']==6) {
    $vdeor_id = $_REQUEST['vdeor_id'];
    $vorpr = $_REQUEST['vorpr'];
    $sql = "DELETE from detalle_orden_prod where deor_id = ".$vdeor_id;
    $resultadoDelete = consultas::ejecutar_sql($sql);
    if ($resultadoDelete) {
        $_SESSION['correcto'] = 'Se eliminó correctamente el detalle';
        $_SESSION['error'] = '';
        header("location:orden_produccion_detalle.php?vorpr_id= ".$vorpr); 

    }else{
        $_SESSION['error'] = 'Hubo un error:';
        $_SESSION['correcto'] = '';
        header("location:orden_produccion_detalle.php?vorpr_id= ".$vorpr); 
    }
}


?>