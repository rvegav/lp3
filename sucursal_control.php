<?php

//echo $_REQUEST['vcar_descri'];

require 'clases/conexion.php';

switch ($_REQUEST['accion']) {
    case 1:
        $sql = "insert into sucursal(id_sucursal,suc_descri) "
       . "values((select coalesce(max(id_sucursal),0)+1 from sucursal),'".$_REQUEST['vsuc_descri']."')";
        $mensaje = 'Se guardo correctamente el sucursal';
        break;
    case 2:
        $sql = "update sucursal set suc_descri = '".$_REQUEST['vsuc_descri']."' where id_sucursal =".$_REQUEST['vid_sucursal'];
        $mensaje = 'Se actualizo correctamente la sucursal';
        break;
    case 3:
        $sql = "delete from sucursal where id_sucursal =".$_REQUEST['vid_sucursal'];
        $mensaje = 'Se elimino correctamente la sucursal';
        break;    
    default:
        header("location:cargo_index.php");
        break;
}

session_start();

if (consultas::ejecutar_sql($sql)) {
    $_SESSION['mensaje'] = $mensaje;
    header("location:sucursal_index.php");
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:cargo_index.php");    
}
