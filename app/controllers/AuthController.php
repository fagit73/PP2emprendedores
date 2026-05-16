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

            $usuarioEncontrado = $usuarioModelo->buscarUsuarioPorEmail($email);

            if ($usuarioEncontrado) {
                if (password_verify($password, $usuarioEncontrado->password)) {

                    // Si entro aca, login ok
                    $_SESSION['user_id'] = $usuarioEncontrado->id;
                    $_SESSION['username'] = $usuarioEncontrado->username;

                    header('Location: ' . URLAPP . '/dashboard/index');
                } else {
                    $datos= ['error' => 'Contraseña incorrecta'];
                    $this->view('auth/login', $datos);
                }
            } else {
                $datos= ['error' => 'El correo electrónico no está registrado'];
                $this->view('auth/login', $datos);
            }
        }
    }

    public function logout(){
        session_destroy();
        header('Location:' . URLAPP . '/auth/login');
    }
}
