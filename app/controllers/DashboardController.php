<?php

class DashboardController extends Controller
{

    public function index()
    {

        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . URLAPP . '/auth/login');
            exit;
        }

        $reservaModel = $this->model('Reserva');
        $id_usuario = $_SESSION['id_usuario'];
        $reservasPendientes = $reservaModel->getReservasUsuarioPendientes($id_usuario);
        $reservasConfirmadas = $reservaModel->getReservasUsuarioConfirmadas($id_usuario);
        $reservasAConfirmar = $reservaModel->getReservasAConfirmar();

        $datos = [
            'titulo' => 'Panel de Control',
            'reservasPendientes' => $reservasPendientes, 
            'reservasConfirmadas' => $reservasConfirmadas, 
            'reservasAConfirmar' => $reservasAConfirmar, 
            ];
        $this->view('dashboard/index', $datos);
    }
}
