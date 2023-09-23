<?php

require 'clases/conexion.php';
session_start();
$accion = $_POST['tipoAccion'];
if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];
}
if (isset($_POST['usuario'])) {
    $usuaCod = $_POST['usuario'];
}
if ($accion==1) {
    $sqlEstadoUsuario = "UPDATE usuarios SET intentos = '$estado' WHERE usu_cod = '$usuaCod'";
    $resultadoActualizacion = consultas::get_datos($sqlEstadoUsuario);
    $datos['mensaje'] = "Actualizado correctamente";
    echo json_encode($datos);
    exit;
}elseif($accion=='uppass'){
    $newPass = $_POST['newPass'];
    $query = "UPDATE usuarios set usu_clave = md5 ('$newPass')";
    $ejecucion = consultas::ejecutar_sql($query);
    if ($ejecucion) {
        $_SESSION['mensaje'] = 'CORRECTO';
        header("location:perfil.php"); 
    }

}else{
    $sql = "select sp_usuario(".$_REQUEST['accion'].",".(!empty($_REQUEST['vusu_cod'])? $_REQUEST['vusu_cod']:"0")
    .",'".(!empty($_REQUEST['vusu_nick'])? $_REQUEST['vusu_nick']:"")."','"
    .(!empty($_REQUEST['vusu_clave'])? $_REQUEST['vusu_clave']:"0")
    ."',".(!empty($_REQUEST['vemp_cod'])? $_REQUEST['vemp_cod']:"0").","
    .(!empty($_REQUEST['vgru_cod'])? $_REQUEST['vgru_cod']:"0").","
    .$_SESSION['id_sucursal'].") as resul;";


    $resultado = consultas::get_datos($sql);


    if ($resultado[0]['resul']!=null) {
        $valor = explode("*", $resultado[0]['resul']);
        $_SESSION['mensaje'] = $valor[0];
        header("location:".$valor[1].".php");
    }else{
        $_SESSION['mensaje'] = 'ERROR:'.$sql;
        header("location:usuario_index.php");    
    }
}