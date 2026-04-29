<?php


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'biblioteca');

// Ruta de la aplicación (Para usar en internal includes)
// __FILE__ da la ruta actual, dirname sube un nivel.
define('APPROOT', dirname(dirname(__FILE__)));
define('APPROOT_DESA', 'C:\xampp\htdocs\biblioteca\app');
// URL Raíz (Para enlaces en el HTML, CSS y redirecciones)
define('URLAPP', 'http://localhost/biblioteca');

// Nombre del sitio
define('SITENAME', 'Biblioteca Sarmiento');