<?php
class UsuariosController extends Controller
{

    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Tomamos los datos del formulario
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            // ENCRIPTAR CONTRASEÑA (Vital para sistemas reales)
            $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);

            // Llamar al modelo
            $usuarioModelo = $this->model('Usuario');

            if ($usuarioModelo->registrar($datos)) {
                // Redirigir al login usando la constante de config
                header('Location: ' . URLAPP . '/auth/login');
            } else {
                die('Algo salió mal');
            }
        } else {
            // Si no es POST, mostrar la vista del formulario
            $this->view('usuarios/registrar');
        }
    }
}
