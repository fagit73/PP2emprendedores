<?php

class AuthController extends Controller
{

    public function login()
    {
        // si el usuario ya esta logueado, se manda al dashboard
        if ((isset($_SESSION['user_id']))) {
            header('Location: /dashboard');
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

                    header('Location: ' . URLAPP . '/dashboard');
                    exit;
                } else {

                    $datos= ['error' => 'Contraseña incorrecta'];

                    $this->view('auth/login', $datos);
                    exit;
                }
            } else {
                $datos= ['error' => 'Usuario no encontrado'];
                $this->view('auth/login', $datos);
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
