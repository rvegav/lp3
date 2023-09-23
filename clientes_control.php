<?php
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
//var_dump($_REQUEST);

require 'clases/conexion.php';

$sql = "select sp_clientes(".$_REQUEST['accion'].",".$_REQUEST['vcli_cod']
        .",".$_REQUEST['vcli_ci'].",'".$_REQUEST['vcli_nombre']
        ."','".$_REQUEST['vcli_apellido']."','".$_REQUEST['vcli_telefono']
        ."','".$_REQUEST['vcli_direcc']."') as resul;";

#session_start();

$resultado = consultas::get_datos($sql);

if ($resultado[0]['resul']!=null) {
    $_SESSION['mensaje'] = $resultado[0]['resul'];
    header("location:clientes_index.php");
} else {
    $_SESSION['mensaje'] = 'ERROR:' . $sql;
    header("location:clientes_index.php");
}




