<?php
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
if ($_REQUEST['accion']==1) {

    $mapr = $_REQUEST['vmapr_id'];
    $cant = $_REQUEST['vcant_materia'];
    $art = $_REQUEST['vart_cod'];
    
    $sql = "INSERT INTO composion_articulos (coar_id, coar_mapr_id, coar_art_id, coar_cant_requerida) VALUES ((select coalesce(max(coar_id),0)+1 from composion_articulos), $mapr, $art, $cant)";
    $resultadoInsert = consultas::ejecutar_sql($sql);
    if ($resultadoInsert) {
        echo json_encode("correcto");
    }else{
        echo json_encode("incorrecto");
    }

}elseif ($_REQUEST['accion']==2) {
    if ($_REQUEST['vart_cod']) {
        $vart_cod = $_REQUEST['vart_cod'];
        $sql="select mapr_descripcion, coar_cant_requerida from composion_articulos c join material_primario m on m.mapr_id = c.coar_mapr_id where coar_art_id =". $vart_cod; 

        $componentes = consultas::get_datos($sql);
        if ($componentes) {
            foreach ($componentes as $componente) {
                $array['material']= $componente['mapr_descripcion'];
                $array['cantidad']= $componente['coar_cant_requerida'];
                $datos[] = $array;
            }
            $data['data']= $datos;
            
        }else{
            $data['data']= [];
        }
        
        echo json_encode($data);
        // code...
    }
} else{
    echo $sql = "select sp_articulo(".$_REQUEST['accion'].",".(!empty($_REQUEST['vart_cod'])? $_REQUEST['vart_cod']:"0").",'"
    .(!empty($_REQUEST['vart_codbarra'])? $_REQUEST['vart_codbarra']:"0")."',".(!empty($_REQUEST['vmar_cod'])? $_REQUEST['vmar_cod']:"0").",'"
    .(!empty($_REQUEST['vart_descri'])? $_REQUEST['vart_descri']:"0")
    ."',".(!empty($_REQUEST['vart_precioc'])? $_REQUEST['vart_precioc']:"0").","
    .(!empty($_REQUEST['vart_preciov'])? $_REQUEST['vart_preciov']:"0").","
    .(!empty($_REQUEST['vtipo_cod'])? $_REQUEST['vtipo_cod']:"0").") as resul;";


    $resultado = consultas::get_datos($sql);


    if ($resultado[0]['resul']!=null) {
        $valor = explode("*", $resultado[0]['resul']);
        $_SESSION['mensaje'] = $valor[0];
        header("location:articulo_index.php");
    }else{
        $_SESSION['mensaje'] = 'ERROR:'.$sql;
        header("location:articulo_index.php");    
    }

}
