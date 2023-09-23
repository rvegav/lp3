<?php
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
//echo $_REQUEST['vmar_descri'];

require 'clases/conexion.php';

switch ($_REQUEST['accion']) {
    case 1:
        $sql = "insert into marca(mar_cod,mar_descri) "
       . "values((select coalesce(max(mar_cod),0)+1 from marca),'".$_REQUEST['vmar_descri']."')";
        $mensaje = 'Se guardo correctamente la marca';
        break;
    case 2:
        $sql = "update marca set mar_descri = '".$_REQUEST['vmar_descri']."' where mar_cod =".$_REQUEST['vmar_cod'];
        $mensaje = 'Se actualizo correctamente la marca';
        break;
    case 3:
        $sql = "delete from marca where mar_cod =".$_REQUEST['vmar_cod'];
        $mensaje = 'Se elimino correctamente la marca';
        break;    
    default:
        header("location:marca_index.php");
        break;
}

#session_start();

if (consultas::ejecutar_sql($sql)) {
    $_SESSION['mensaje'] = $mensaje;
    header("location:marca_index.php");
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:marca_index.php");    
}
