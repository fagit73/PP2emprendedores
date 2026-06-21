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
        // Obtenemos los horarios base para pasarlos al JS sin necesidad de AJAX extra
        $horarios = $this->reservaModel->getAllHorarios();

        $datos = [
            'titulo' => 'Nueva Reserva',
            'horarios' => $horarios, // Pasamos esto a la vista
            'js' => ['reserva.js']
        ];
        $this->view('reserva/nueva', $datos);
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Guardar Reserva
            $id_reserva = $this->reservaModel->guardarReserva([
                'id_usuario' => $_SESSION['id_usuario'],
                'id_tipo_uso' => $_POST['id_tipo_uso'],
                'id_horario' => $_POST['id_horario'],
                'id_sala'    => 1,
                'fecha'      => $_POST['fecha_reserva']
            ]);

            if ($id_reserva) {
                // 2. Si se eligió 'Completar formulario', guardar proyecto
                if (isset($_POST['titulo']) && !empty($_POST['titulo'])) {
                    $this->reservaModel->guardarProyecto([
                        'id_reserva'     => $id_reserva,
                        'tipo_carga'     => 'FORMULARIO',
                        'titulo'         => $_POST['titulo'],
                        'responsable'    => $_POST['responsable_proyecto'],
                        'fecha_inicio'   => $_POST['fecha_inicio'],
                        'fecha_fin'      => $_POST['fecha_fin'],
                        'descripcion'    => $_POST['descripcion'],
                        'evaluacion'     => $_POST['evaluacion'],
                        'palabras_clave' => $_POST['palabras_clave']
                    ]);
                }
                header('Location: ' . URLAPP . '/dashboard');
            }
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
