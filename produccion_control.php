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

}elseif($_REQUEST['accion']==6){
    //consulta de datos
    if ($_REQUEST['vprod_id']) {
        $vprod_id = $_REQUEST['vprod_id'];
        if ($_REQUEST['ruta']=='etapa') {
            $sql="select e.etpr_descripcion etapa, c.copr_fecha fecha, c.copr_observacion observacion, c.copr_canti_producida cantidad, c.copr_estado estado from control_produccion c join etapas_produccion e on e.etpr_id = c.copr_etpr_id where copr_prod_id =". $vprod_id; 
            $etapas = consultas::get_datos($sql);
            if ($etapas) {
                foreach ($etapas as $etapa) {
                    $array['etapa']= $etapa['etapa'];
                    $array['fecha']= $etapa['fecha'];
                    $array['observacion']= $etapa['observacion'];
                    $array['cantidad']= $etapa['cantidad'];
                    $array['estado']= $etapa['estado'];
                    $datos[] = $array;
                }
                $data['data']= $datos;
                
            }else{
                $data['data']= [];
            }
            
        }

        
        echo json_encode($data);
        // code...
    }

}


?>