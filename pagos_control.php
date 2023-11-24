<?php
session_start();
require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
if ($_REQUEST['accion']==1) {
    $id = $_REQUEST['vctco_id'];
    $fecha = $_REQUEST['vpago_fecha'];
    $metodo = $_REQUEST['vmetodo'];
    $sql = "SELECT * from ctas_a_cobrar where ctco_id= $id";
    $cuota = consultas::get_datos($sql);
    if ($metodo =='T') {
        $nro_tarjeta=$_REQUEST['nro_tarjeta'];
    }else{
        $nro_tarjeta='';
    }
    $nro_cuota = intval($cuota[0]['nro_cuota']);
    $ven_cod = intval($cuota[0]['ven_cod']);
    $monto_cuota = intval($cuota[0]['monto_cuota']);
    $sql = "INSERT INTO detalle_cobros (nro_cuota, ven_cod, monto_cobrado, deco_metodo,deco_fecha_pago,deco_nro_tarjeta, deco_ctco_id) VALUES ($nro_cuota, $ven_cod, $monto_cuota, '$metodo', '$fecha', '$nro_tarjeta', $id)"; 
    $resultadoInsert = consultas::ejecutar_sql($sql);
    if ($resultadoInsert) {
        $sql = "UPDATE ctas_a_cobrar set estado_cuota = 'A' where ctco_id = $id";
        $resultadoUpdate = consultas::ejecutar_sql($sql);
        if ($resultadoUpdate) {
            $_SESSION['mensaje'] = 'Se realizo el pago con exito';
            header("location:cuentas_cobrar_index.php"); 
        }else{
            die();
            $_SESSION['mensaje'] = 'Hubo un errord:';
            header("location:cuentas_cobrar_index.php");    
        }

    }else{
        $_SESSION['mensaje'] = 'Hubo un error:';
        header("location:cuentas_cobrar_index.php"); 
    }

}
?>