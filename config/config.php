<?php

// Configuracion por entorno.
// - En local (XAMPP) se usan los valores por defecto.
// - En la VM (Docker) los valores llegan por variables de entorno
//   definidas en docker-compose.yaml (servicio php).

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'biblioteca');

define('APPROOT', dirname(dirname(__FILE__)));
define('APPROOT_DESA', 'C:\xampp\htdocs\biblioteca\app');

define('URLAPP', getenv('APP_URL') ?: 'http://localhost/biblioteca');

define('SITENAME', 'Biblioteca Sarmiento');