<?php
session_start();
#require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
//echo $_REQUEST['vdep_descri'];

require 'clases/conexion.php';

switch ($_REQUEST['accion']) {
    case 1:
        $sql = "insert into deposito(dep_cod,dep_descri,id_sucursal) "
       . "values((select coalesce(max(dep_cod),0)+1 from deposito),'".$_REQUEST['vdep_descri']."','".$_REQUEST['vdep_sucursal']."')";
        $mensaje = 'Se guardo correctamente el deposito';
        break;
    case 2:
        $sql = "update deposito set dep_descri = '".$_REQUEST['vdep_descri']."' where dep_cod =".$_REQUEST['vdep_cod'];
        $mensaje = 'Se actualizo correctamente el deposito';
        break;
    case 3:
        $sql = "delete from deposito where dep_cod =".$_REQUEST['vdep_cod'];
        $mensaje = 'Se elimino correctamente el deposito';
        break;    
    default:
        header("location:deposito_index.php");
        break;
}


if (consultas::ejecutar_sql($sql)) {
    $_SESSION['mensaje'] = $mensaje;
    header("location:deposito_index.php");
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:deposito_index.php");    
}
