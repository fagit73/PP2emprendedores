<?php
class Controller
{
    // Cargar modelo
    public function model($modelo)
    {
        require_once 'app/models/' . $modelo . '.php';
        return new $modelo();
    }

    // Cargar vista
    public function view($vista, $datos = [])
    {
        if (file_exists('app/views/' . $vista . '.php')) {
            // Esto permite usar $titulo en lugar de $datos['titulo']
            extract($datos);
            require_once 'app/views/' . $vista . '.php';
        } else {
            die('La vista no existe');
        }
    }
}
