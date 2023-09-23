<?php
        if ($_SESSION['grupo'] == 'ventas') {
            $_SESSION['bloquear_acceso'] = 'EL ROL DE VENTAS NO TIENE ROLES SUFICIENTES PARA ACCEDER AL MÓDULO';
            header('Location: menu.php');
        }
