<?php
session_start();
require 'acceso_bloquear_ventas.php';
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
}elseif ($_REQUEST['accion']==7) {
    $vorpr = $_REQUEST['vorpr_id'];
    $fechaActual = date('d-m-Y H:i:s');
    $anhoActual = date('Y');
    $sql = "UPDATE orden_produccion SET orpr_estado = 'A', orpr_fecha_confe = '".$fechaActual."' WHERE orpr_id = ".$vorpr;
    $update = consultas::ejecutar_sql($sql);
    if ($update) {
        $sql = "SELECT deor_art_id, sum(deor_cantidad) FROM detalle_orden_prod WHERE deor_orpr_id =".$vorpr."group by deor_art_id";
        $detalles_orden = consultas::get_datos($sql);
        if (!empty($detalles_orden)) {
            foreach ($detalles_orden as $detalle) {
                $sql = "INSERT INTO produccion (prod_id, prod_fecha, prod_orpr_id, prod_aprobado, prod_nro, prod_anho) VALUES((select coalesce(max(prod_id),0)+1 from produccion), '$fechaActual', $vorpr, false, (select coalesce(max(prod_nro),0)+1 from produccion where prod_anho = '$anhoActual'), '$anhoActual')";
                $insert = consultas::ejecutar_sql($sql);
                if ($insert) {
                    $sql = "SELECT max(prod_id) prod_id from produccion";
                    $consulta = consultas::get_datos($sql);
                    $prod_id = $consulta[0]['prod_id'];
                    $sql = "insert INTO detalle_produccion (depro_id, depro_art_id, depro_cantidad, depro_prod_id) VALUES ((select coalesce(max(depro_id),0)+1 from detalle_produccion),". $detalle['deor_art_id'].", ".$detalle['deor_cantidad'].",". $prod_id.")";
                    $insert = consultas::ejecutar_sql($sql);
                    if (!$insert) {
                        $_SESSION['error'] = 'Hubo un error: No se pudo registrar el detalle';
                    }
                }else{
                    $_SESSION['error'] = 'Hubo un error: No se pudo generar la produccion';
                    $_SESSION['correcto'] = '';
                    header("location:orden_produccion_index.php");    

                }
            }
            $_SESSION['correcto'] = 'Se actualizó correctamente la orden nro:'.$vorpr;
            $_SESSION['error'] = '';
            header("location:orden_produccion_index.php");    
            
        }
    }else{
        $_SESSION['error'] = 'Hubo un error: No se pudo aprobar la orden';
        $_SESSION['correcto'] = '';
        header("location:orden_produccion_index.php");    
    }

}


?>