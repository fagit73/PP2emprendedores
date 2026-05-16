<?php

class DashboardController extends Controller {

    public function index() {

        if(!isset($_SESSION['id_usuario'])){
            header('Location: ' . URLAPP . '/auth/login');
            exit;
        }

        $datos = ['titulo' => 'Panel de Control'];
        $this->view('dashboard/inicio', $datos);
    }
}