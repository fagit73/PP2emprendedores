<?php

class ReporteController extends Controller
{
    public function index()
    {
        $reporteModel = $this->model('Reporte');
        $stats = $reporteModel->getResumenReservas();
        $this->view('reportes/index', ['stats' => $stats]);
    }
}
