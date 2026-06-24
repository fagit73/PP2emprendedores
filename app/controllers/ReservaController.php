<?php

class ReservaController extends Controller
{
    private $reservaModel;

    public function __construct()
    {
        $this->reservaModel = $this->model('Reserva');
    }

    public function nueva()
    {
        // Obtenemos los horarios y las salas para pasarlos a la vista
        $horarios = $this->reservaModel->getAllHorarios();
        $salas = $this->reservaModel->getAllSalas();

        $datos = [
            'titulo' => 'Nueva Reserva',
            'horarios' => $horarios, // Pasamos esto a la vista
            'salas' => $salas,
            'js' => ['reserva.js']
        ];
        $this->view('reserva/nueva', $datos);
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Determinar el modo de propósito según lo que cargó el usuario.
            // (La subida de archivo todavía no está implementada: ver TODO.)
            $modo = !empty($_POST['titulo']) ? 'FORMULARIO' : 'SIN_DATOS';

            // Día de la semana en español a partir de la fecha.
            $fecha = $_POST['fecha_reserva'] ?? null;
            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            $dia_semana = $fecha ? $dias[(int)date('w', strtotime($fecha))] : null;

            // 1. Guardar Reserva
            $id_reserva = $this->reservaModel->guardarReserva([
                'id_usuario'     => $_SESSION['id_usuario'],
                'id_tipo_uso'    => $_POST['id_tipo_uso'],
                'id_horario'     => $_POST['id_horario'],
                'id_sala'        => $_POST['id_sala'] ?? 1,
                'fecha'          => $fecha,
                'dia_semana'     => $dia_semana,
                'motivo'         => $_POST['motivo'] ?? null,
                'modo_proposito' => $modo
            ]);

            if ($id_reserva) {
                // 2. Si se eligió 'Completar formulario', guardar proyecto + keywords
                if ($modo === 'FORMULARIO') {
                    $this->reservaModel->guardarProyecto([
                        'id_reserva'     => $id_reserva,
                        'titulo'         => $_POST['titulo'],
                        'responsable'    => $_POST['responsable_proyecto'] ?? null,
                        'fecha_inicio'   => $_POST['fecha_inicio'] ?? null,
                        'fecha_fin'      => $_POST['fecha_fin'] ?? null,
                        'descripcion'    => $_POST['descripcion'] ?? null,
                        'evaluacion'     => $_POST['evaluacion'] ?? null,
                        'palabras_clave' => $_POST['palabras_clave'] ?? ''
                    ]);
                }
            }

            header('Location: ' . URLAPP . '/dashboard');
            exit;
        }
    }

    public function misReservas()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $reservas = $this->reservaModel->getReservasUsuario($id_usuario);

        $datos = [
            'titulo' => 'Mis Reservas',
            'reservas' => $reservas
        ];
        $this->view('reserva/mis_reservas', $datos);
    }

    public function confirmar($id_reserva)
    {

        if (!tieneRol(['Bibliotecario', 'Administrador'])) {
            $_SESSION['error'] = "No tienes permiso para confirmar reservas.";
            header('Location: ' . URLAPP . '/dashboard');
            exit;
        }

        if ($this->reservaModel->actualizarEstado($id_reserva, 'ACTIVA')) {
            $_SESSION['mensaje'] = "Reserva confirmada con éxito";
        } else {
            $_SESSION['error'] = "No se pudo confirmar la reserva";
        }

        header('Location: ' . URLAPP . '/dashboard');
        exit;
    }

    public function cancelar($id_reserva)
    {

        if (!tieneRol(['Bibliotecario', 'Administrador'])) {
            $_SESSION['error'] = "No tienes permiso para cancelar reservas.";
            header('Location: ' . URLAPP . '/dashboard');
            exit;
        }
        if ($this->reservaModel->actualizarEstado($id_reserva, 'CANCELADA')) {
            $_SESSION['mensaje'] = "Reserva confirmada con éxito";
        } else {
            $_SESSION['error'] = "No se pudo cancelar la reserva";
        }

        header('Location: ' . URLAPP . '/dashboard');
        exit;
    }

    // Método AJAX para obtener horarios ocupados
    public function obtenerHorariosOcupados($fecha)
    {
        $ocupados = $this->reservaModel->getHorariosOcupados($fecha);
        header('Content-Type: application/json');
        echo json_encode($ocupados);
    }
}
