<?php

function estaLogueado() {
    return isset($_SESSION['user_id']);
}

function redirect($pagina){
    header('Location: /miproyecto/' . $pagina);
}