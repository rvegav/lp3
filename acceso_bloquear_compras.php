<?php
        if ($_SESSION['grupo'] == 'compras') {
            $_SESSION['bloquear_acceso'] = 'EL ROL DE COMPRAS NO TIENE ROLES SUFICIENTES PARA ACCEDER AL MÓDULO';
            header('Location: menu.php');
        }
