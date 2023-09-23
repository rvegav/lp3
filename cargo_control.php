<?php
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
//echo $_REQUEST['vcar_descri'];

require 'clases/conexion.php';

switch ($_REQUEST['accion']) {
    case 1:
        $sql = "insert into cargo(car_cod,car_descri) "
       . "values((select coalesce(max(car_cod),0)+1 from cargo),'".$_REQUEST['vcar_descri']."')";
        $mensaje = 'Se guardo correctamente el cargo';
        break;
    case 2:
        $sql = "update cargo set car_descri = '".$_REQUEST['vcar_descri']."' where car_cod =".$_REQUEST['vcar_cod'];
        $mensaje = 'Se actualizo correctamente el cargo';
        break;
    case 3:
        $sql = "delete from cargo where car_cod =".$_REQUEST['vcar_cod'];
        $mensaje = 'Se elimino correctamente el cargo';
        break;    
    default:
        header("location:cargo_index.php");
        break;
}

#session_start();

if (consultas::ejecutar_sql($sql)) {
    $_SESSION['mensaje'] = $mensaje;
    header("location:cargo_index.php");
}else{
    $_SESSION['mensaje'] = 'ERROR:'.$sql;
    header("location:cargo_index.php");    
}
