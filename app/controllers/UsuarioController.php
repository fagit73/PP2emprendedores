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
                'password_hash' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),

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

    public function listar()
    {
        if (!tieneRol(['Bibliotecario', 'Administrador'])) {
            $_SESSION['error'] = "No tienes permiso para ver el listado de usuarios.";
            header('Location: ' . URLAPP . '/dashboard');
            exit;
        }

        $usuarioModel = $this->model('Usuario');
        $usuarios = $usuarioModel->getTodosUsuarios();
        $this->view('usuario/listado', ['usuarios' => $usuarios]);
    }

    public function editar($id)
    {
        if (!tieneRol(['Bibliotecario', 'Administrador'])) {
            $_SESSION['error'] = "No tienes permiso para editar usuarios.";
            header('Location: ' . URLAPP . '/dashboard');
            exit;
        }

        $usuarioModel = $this->model('Usuario');
        $usuario = $usuarioModel->getUsuarioById($id);

        if (!$usuario) {
            header('Location: ' . URLAPP . '/usuario');
            exit;
        }

        $this->view('usuario/editar', ['usuario' => $usuario]);
    }

    public function actualizar()
    {
        if (!tieneRol(['Bibliotecario', 'Administrador'])) {
            $_SESSION['error'] = "No tienes permiso para actualizar usuarios.";
            header('Location: ' . URLAPP . '/dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuarioModel = $this->model('Usuario');

            $datos = [
                'id_usuario' => $_POST['id_usuario'],
                'nombre_completo'     => $_POST['nombre_completo'],
                'email_institucional' => $_POST['email_institucional'],
                'email_recuperacion' => $_POST['email_recuperacion'],
                'id_rol'     => $_POST['id_rol'],
                'activo'     => $_POST['activo']
            ];

            if ($usuarioModel->actualizarUsuario($datos)) {
                header('Location: ' . URLAPP . '/usuario/listar');
            } else {
                die("Error al actualizar usuario.");
            }
        }
    }
}
