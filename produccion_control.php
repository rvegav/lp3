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

}elseif ($_REQUEST['accion']==5) {

    if (isset($_REQUEST['vprod_id'])) {
        $copr_prod_id = $_REQUEST['vprod_id'];
        $copr_etpr_id = $_REQUEST['vetpr_id'];
        $copr_fecha = date('d-m-Y H:i:s');
        $copr_estado = 'C';
        $copr_canti_producida = $_REQUEST['vcantidad'];
        $copr_observacion = $_REQUEST['vobservacion'];
        $copr_empl_id = $_REQUEST['vemplcod'];
        $sql ="INSERT INTO control_produccion (copr_id, copr_prod_id, copr_fecha, copr_estado, copr_canti_producida, copr_observacion, copr_empl_id, copr_item, copr_etpr_id) VALUES ((select coalesce(max(copr_id),0)+1 from control_produccion), $copr_prod_id, '$copr_fecha', '$copr_estado', $copr_canti_producida, '$copr_observacion', $copr_empl_id, (select coalesce(MAX(copr_item), 0)+1 from control_produccion where copr_prod_id = $copr_prod_id), $copr_etpr_id)";
        $resultado = consultas::ejecutar_sql($sql);
        if ($resultado) {
            echo json_encode('correcto');
        }else{
            echo json_encode('incorrecto');
        }
    }
}elseif($_REQUEST['accion']==6){
    //consulta de datos
    if (isset($_REQUEST['vprod_id'])) {
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
            
        }elseif ($_REQUEST['ruta']=='costo') {
            $sql="select cospr_fecha, cospr_monto_produccion, cospr_monto_mano_obra from costo_produccion c where cospr_prod_id =". $vprod_id; 
            $etapas = consultas::get_datos($sql);
            if ($etapas) {
                foreach ($etapas as $etapa) {
                    $array['etapa']= $etapa['etapa'];
                    $array['mano']= $etapa['cospr_monto_mano_obra'];
                    $array['material']= $etapa['cospr_monto_produccion'];
                    $datos[] = $array;
                }
                $data['data']= $datos;
                
            }else{
                $data['data']= [];
            }
        }

        echo json_encode($data);
    }

}elseif($_REQUEST['accion']==7){
    if (isset($_REQUEST['vprod_id'])) {
        $costoProduccion = consultas::get_datos("select * from costo_produccion where cospr_prod_id =".$_REQUEST['vprod_id']);
        if (empty($costoProduccion)) {
            // code...
            $cospr_prod_id = $_REQUEST['vprod_id'];
            $cospr_monto_produccion = $_REQUEST['vcostoComposion'];
            $cospr_monto_mano_obra = $_REQUEST['vcostoMano'];
            $cospr_fecha = $_REQUEST['vfecha'];                                                                                          
            $sql ="INSERT INTO costo_produccion (cospr_id, cospr_prod_id, cospr_fecha, cospr_monto_produccion, cospr_monto_mano_obra) VALUES ((select coalesce(max(cospr_id),0) +1 from costo_produccion), $cospr_prod_id, '$cospr_fecha', '$cospr_monto_produccion', $cospr_monto_mano_obra)";
            $resultado = consultas::ejecutar_sql($sql);
            if ($resultado) {
                echo json_encode('correcto');
            }else{
                echo json_encode('incorrecto');
            }
        }else{
            echo json_encode('incorrecto');

        }
    }

}elseif ($_REQUEST['accion']==8) {
    if (isset($_REQUEST['vprod_id'])) {
        $sql = "select coca_calificacion calificacion from control_calidad where coca_prod_id =".$_REQUEST['vprod_id'];
        $calificacion = consultas::get_datos($sql);
        if (intval($calificacion[0]['calificacion'])>0) {
            $sql = "select CASE WHEN count(*)=0 THEN 1 ELSE count(*) END lote, depro_art_id articulo from produccion p join detalle_produccion dp on dp.depro_prod_id = prod_id where depro_art_id = (select d.depro_art_id from detalle_produccion d where d.depro_prod_id = ".$_REQUEST['vprod_id'].") group by depro_art_id ";
            echo "<pre>";
            var_dump ($sql);
            echo "</pre>";
            die();
            $lote_nro = consultas::get_datos($sql);
            $sql='update produccion set prod_lote = '. $lote_nro[0]['lote'] .', prod_aprobado = true where prod_id ='. $_REQUEST['vprod_id'];
            $update = consultas::ejecutar_sql($sql);
            $sql = "select cp.copr_canti_producida cantidad from control_produccion cp where cp.copr_id = (select max(c.copr_id) from control_produccion c where c.copr_prod_id =".$_REQUEST['vprod_id'].")";
            $cantidad_producida = consultas::get_datos($sql);
            $sql = "update stock set stoc_cant= (stoc_cant + ".$cantidad_producida[0]['cantidad'].") where art_cod =".$lote_nro[0]['articulo'];
            $update_stock = consultas::ejecutar_sql($sql);
            if ($update_stock) {
                $_SESSION['correcto'] = 'Se actualizó el stock';
                $_SESSION['error'] = '';
                header("location:produccion_index.php"); 

            }else{
                $_SESSION['error'] = 'Hubo un error:';
                $_SESSION['correcto'] = '';
                header("location:produccion_index.php"); 
            }

        }else{
            $_SESSION['error'] = 'Se necesita tener una calificacion en control de calidad:';
            $_SESSION['correcto'] = '';
            header("location:produccion_index.php"); 
        }
    }
}elseif ($_REQUEST['accion']==9) {
    if (isset($_REQUEST['vprod_id'])) {
        $prod_id =$_REQUEST['vprod_id'];
        $calificacion = $_REQUEST['calificacion'];
        $consulta = consultas::get_datos('SElECT * FROM control_calidad c where c.coca_prod_id = '.$prod_id);
        if (empty($consulta)) {
            $sql = 'insert INTO control_calidad (coca_id, coca_prod_id, coca_calificacion) VALUES ((select coalesce(max(coca_id), 0)+1 from control_calidad), '.$prod_id.', '.$calificacion.')';
            $resultado = consultas::ejecutar_sql($sql);
            if ($resultado) {
                echo json_encode('correcto');
            }else{
                echo json_encode('incorrecto');
            }
        }

    }else{
        echo json_encode('incorrecto');
    }
}


?>