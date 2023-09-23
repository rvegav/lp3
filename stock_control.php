<?php
session_start();
#require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
require 'clases/conexion.php';
#session_start();
//vart_cod

$accion = $_REQUEST['accion'];
$vdep_cod = $_REQUEST['vdep_cod'];
$vart_cod = $_REQUEST['vart_cod'];
$vart_cantidad = $_REQUEST['vart_cantidad'];

$consultaStockExiste = "select * from stock where dep_cod = '$vdep_cod' and art_cod = '$vart_cod'";
$resConsultaStockExiste = consultas::get_datos($consultaStockExiste);
if($accion==1){
    if (empty($resConsultaStockExiste)) {
       $sql = "select sp_stock('$accion','$vdep_cod','$vart_cod','0','$vart_cantidad') as resul;";
       $resultado = consultas::get_datos($sql);
       if ($resultado[0]['resul']!=null) {
            $valor = explode("*", $resultado[0]['resul']);
            $_SESSION['mensaje'] = $valor[0];
            header("location:stock_index.php");
        }else{
            //$_SESSION['mensaje'] = 'ERROR:'.$sql;
            $_SESSION['mensajeValid'] = 'ERROR: Ya existe el stock del deposito con el articulo';
            header("location:stock_index.php");    
        }
    }else{
        //$_SESSION['mensaje'] = 'ERROR: Ya existe el stock dede deposito con elarticulo';
        $_SESSION['mensajeValid'] = 'ERROR: Ya existe el stock dede deposito con elarticulo';
        header("location:stock_index.php"); 
    }

}else{
    if (!empty($resConsultaStockExiste)) {
            $sql = "select sp_stock('$accion','$vdep_cod','$vart_cod','0','$vart_cantidad') as resul;";
            $resultado = consultas::get_datos($sql);
            if ($resultado[0]['resul']!=null) {
                $valor = explode("*", $resultado[0]['resul']);
                $_SESSION['mensaje'] = "Modificado correctamente";
                header("location:stock_index.php"); 
            }else{
                $_SESSION['mensajeValid'] = 'ERROR: ocurrio un error';
                header("location:stock_index.php");    
            }
        }else{ 
        $_SESSION['mensajeValid'] = 'ERROR: no existe el stock del deposito con el articulo';
        header("location:stock_index.php"); 
    }
}
?>