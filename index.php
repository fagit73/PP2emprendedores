<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'config/config.php';

require_once 'app/helpers/session_helper.php';

spl_autoload_register(function ($nombreClase) {
    require_once 'app/core/' . $nombreClase . '.php';
});


$init = new Core();
