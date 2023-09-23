<?php

require './clases/conexion.php';
session_start();
$sql1 = "select * from v_usuarios where usu_nick='".$_REQUEST['usuario']."'";
$resultadoUsuario = consultas::get_datos($sql1);
if(!empty($resultadoUsuario)){
    $intentos = $resultadoUsuario[0]['intentos'];
    $usuaCod = $resultadoUsuario[0]['usu_cod'];
    $fechaIntento = $resultadoUsuario[0]['fecha'];
    $fechaHoy = date('Y-m-d');
    if ($intentos<4) {
        $sql = "select * from v_usuarios where usu_nick = '" . $_REQUEST['usuario'] . "'
                and usu_clave = md5('" . $_REQUEST['clave'] . "')";
        $resultado = consultas::get_datos($sql);
        if ($resultado[0]['usu_cod'] == null) {
            $_SESSION['error'] = 'Clave incorrecto.';
            if (strtotime($fechaIntento) == strtotime(date('Y-m-d'))) {
                if ($intentos>3) {
                    echo "Bloqueado";
                }else{
                    $totalIntento = $intentos + 1;
                    $sqlIntento = "UPDATE usuarios SET  intentos='$totalIntento', fecha='$fechaHoy' WHERE usu_cod='$usuaCod'";
                    $resultado = consultas::get_datos($sqlIntento);
                }
            }else{
                $sqlIntento = "UPDATE usuarios SET  intentos='1', fecha='$fechaHoy' WHERE usu_cod='$usuaCod'";
                $resultado = consultas::get_datos($sqlIntento);
            }
            header('location:index.php');
        } else {
            echo '<pre>';
            print_r($resultado);
            $sqlIntento = "UPDATE usuarios SET  intentos='1', fecha='$fechaHoy' WHERE usu_cod='$usuaCod'";
            $resultadoUpdate = consultas::get_datos($sqlIntento);
            $_SESSION['usu_cod'] = $resultado[0]['usu_cod'];
            $_SESSION['usu_nick'] = $resultado[0]['usu_nick'];
            $_SESSION['usu_fot'] = '';
            $_SESSION['emp_cod'] = $resultado[0]['emp_cod'];
            $_SESSION['cedula'] = $resultado[0]['cedula'];
            $_SESSION['nombres'] = $resultado[0]['empleado'];
            $_SESSION['cargo'] = $resultado[0]['car_descri'];
            $_SESSION['gru_cod'] = $resultado[0]['gru_cod'];
            $_SESSION['grupo'] = $resultado[0]['gru_nombre'];
            $_SESSION['id_sucursal'] = $resultado[0]['id_sucursal'];
            $_SESSION['sucursal'] = $resultado[0]['suc_descri'];
            header('location:menu.php');
        }
    }else{
        $_SESSION['error'] = 'Usuario bloqueado.';
        header('location:index.php');
    }
}else{
    $_SESSION['error'] = 'El usuario no existe.';
    header('location:index.php');
}


