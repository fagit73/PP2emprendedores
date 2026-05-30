<?php

class UsuarioController extends Controller
{
    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $datos = [

                'nombre_completo' => trim($_POST['nombre_completo']),
                'dni' => trim($_POST['dni']),
                'celular' => trim($_POST['celular']),
                'email_institucional' => trim($_POST['email_institucional']),
                'email_recuperacion' => trim($_POST['email_recuperacion']),
                'password_hash' => password_hash(trim($_POST['password']),PASSWORD_DEFAULT),

                // Rol por defecto
                'id_rol' => 3
            ];

            $usuarioModelo = $this->model('Usuario');

            if ($usuarioModelo->registrar($datos)) {

                $_SESSION['success'] = 'Cuenta creada correctamente';

                header('Location: ' . URLAPP . '/auth/login');
                exit;

            } else {

                $_SESSION['error'] = 'Error al registrar usuario';

                header('Location: ' . URLAPP . '/usuario/registrar');
                exit;
            }

        } else {

            $this->view('usuario/registrar');
        }
    }
}