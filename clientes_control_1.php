<?php
session_start();
require 'acceso_bloquear_compras.php';
require 'acceso_bloquear_ventas.php';
//var_dump($_REQUEST);

require 'clases/conexion.php';



switch ($_REQUEST['accion']) {
    case 1:
        $sql = "INSERT INTO clientes(
            cli_cod, cli_ci, cli_nombre, cli_apellido, cli_telefono, cli_direcc)
        VALUES ((select coalesce(max(cli_cod),0)+1 from clientes)," . $_REQUEST['vcli_ci'] . ",upper('"
                . $_REQUEST['vcli_nombre'] . "'),upper('" . $_REQUEST['vcli_apellido'] . "'), '" . $_REQUEST['vcli_telefono']
                . "', upper('" . $_REQUEST['vcli_direcc'] . "'));";
        $mensaje = 'Se registro correctamente al cliente';
        break;
    case 2:
        $sql = "update clientes set cli_ci =" . $_REQUEST['vcli_ci']
                . ",cli_nombre=upper('" . $_REQUEST['vcli_nombre'] . "'),"
                . "cli_apellido=upper('" . $_REQUEST['vcli_apellido'] . "'),"
                . "cli_telefono=upper('" . $_REQUEST['vcli_telefono'] . "'),"
                . "cli_direcc=upper('" . $_REQUEST['vcli_direcc'] . "') where cli_cod =" . $_REQUEST['vcli_cod'];        
        $mensaje = 'Se modifico correctamente al cliente';
        break;
    case 3:
        $sql = "delete from clientes where cli_cod=".$_REQUEST['vcli_cod'];
        $mensaje = 'Se elimino correctamente al cliente';
        break;
}

#session_start();

if (consultas::ejecutar_sql($sql)) {
    $_SESSION['mensaje'] = $mensaje;
    header("location:clientes_index.php");
} else {
    $_SESSION['mensaje'] = 'ERROR:' . $sql;
    header("location:clientes_index.php");
}


