<?php

function estaLogueado() {
    return isset($_SESSION['user_id']);
}


function tieneRol($rolesPermitidos)
{
    if (!isset($_SESSION['rol'])) {
        return false;
    }

    return in_array($_SESSION['rol'], $rolesPermitidos);
}