<?php

class AuthController extends Controller
{

    public function index()
    {
        // si el usuario ya esta logueado, se manda al dashboard
        if (isset($_SESSION['id_usuario'])) {
            header('Location: ' . URLAPP . '/dashboard/index');
            exit;
        }
        $this->view('auth/login');
    }

    public function recuperar()
    {
        $this->view('auth/recuperar');
    }

    public function verificar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);


            $usuarioModelo = $this->model('Usuario');


            $usuario = $usuarioModelo->buscarUsuarioPorEmail($email);

            if ($usuario) {

                if (password_verify($password, $usuario->password_hash)) {

                    $_SESSION['id_usuario'] = $usuario->id_usuario;
                    $_SESSION['nombre'] = $usuario->nombre_completo;
                    $_SESSION['rol'] = $usuario->nombre_rol;
                    $_SESSION['id_rol'] = $usuario->id_rol;

                    header('Location: ' . URLAPP . '/dashboard/');
                    exit;
                } else {

                    $_SESSION['error'] = 'Contraseña incorrecta';

                    header('Location: ' . URLAPP . '/auth/login');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Usuario no encontrado';

                header('Location: ' . URLAPP . '/auth/login');
                exit;
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location:' . URLAPP . '/auth/login');
    }
}
