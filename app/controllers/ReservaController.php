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
<<<<<<< HEAD
        $palabrasClaves = $this->reservaModel->getPalabrasClaves();

        $datos = [
            'titulo' => 'Nueva Reserva',
            'horarios' => $horarios,
            'palabrasClaves' => $palabrasClaves,
=======
        $salas = $this->reservaModel->getAllSalas();

        $datos = [
            'titulo' => 'Nueva Reserva',
            'horarios' => $horarios, // Pasamos esto a la vista
            'salas' => $salas,
>>>>>>> 178ad7e5d38638b4b3fce103e820de762c429bc9
            'js' => ['reserva.js']
        ];
        $this->view('reserva/nueva', $datos);
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
<<<<<<< HEAD

            $estadoReserva = (isset($_POST['titulo']) && !empty($_POST['titulo'])) ? 'ACTIVA' : '';

            // 1. Guardar Reserva
            $id_reserva = $this->reservaModel->guardarReserva([
                'id_usuario' => $_SESSION['id_usuario'],
                'id_tipo_uso' => $_POST['id_tipo_uso'],
                'id_horario' => $_POST['id_horario'],
                'id_sala'    => 1,
                'fecha'      => $_POST['fecha_reserva'],
                'estado'     => $estadoReserva
=======
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
>>>>>>> 178ad7e5d38638b4b3fce103e820de762c429bc9
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

        if (!tieneRol(['ADMIN'])) {
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

        if (!tieneRol(['ADMIN'])) {
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

    public function editar($id_reserva)
    {
        $reserva = $this->reservaModel->getReservaById($id_reserva); // Asegúrate que este usa ->registro()
        $proyecto = $this->reservaModel->getProyectoByReservaId($id_reserva);
        $horarios = $this->reservaModel->getHorarios(); // Ahora esto funcionará
        $palabrasClaves = $this->reservaModel->getPalabrasClaves();

        $datos = [
            'reserva' => $reserva,
            'proyecto' => $proyecto,
            'horarios' => $horarios,
            'palabrasClaves' => $palabrasClaves,
            'js' => ['reserva.js']
        ];

        $this->view('reserva/editar', $datos);
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_reserva = $_POST['id_reserva'];
            $id_usuario = $_SESSION['id_usuario'];

            $datos = [
                'id_reserva' => $id_reserva,
                'id_usuario' => $id_usuario,
                'id_tipo_uso' => $_POST['id_tipo_uso'],
                'id_horario' => $_POST['id_horario'],
                'fecha' => $_POST['fecha_reserva'],
                'motivo' => $_POST['descripcion'], // O el campo que uses como motivo
                'titulo' => $_POST['titulo'],
                'responsable' => $_POST['responsable_proyecto'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'descripcion' => $_POST['descripcion'],
                'evaluacion' => $_POST['evaluacion'],
                'palabras_clave' => $_POST['palabras_clave']
            ];

            //echo '<pre>';
            //var_dump($datos);
            //echo '</pre>';
            //die();
            // 1. Actualizar Reserva y Proyecto
            // Asegúrate de que estos métodos existan en tu modelo y usen los datos de $datos
            $reservaOk = $this->reservaModel->actualizarReserva($datos);
            $proyectoOk = $this->reservaModel->actualizarProyecto($datos);

            if ($reservaOk && $proyectoOk) {
                // Guardamos el mensaje en la sesión
                $_SESSION['mensaje_exito'] = "La reserva se ha actualizado correctamente.";
                header('Location: ' . URLAPP . '/dashboard');
                exit;
            } else {
                die("Error al actualizar los datos.");
            }
        }
    }


    // Método AJAX para obtener horarios ocupados
    public function obtenerHorariosOcupados($fecha, $id_reserva_actual = null)
    {
        $ocupados = $this->reservaModel->getHorariosOcupados($fecha);
        header('Content-Type: application/json');
        echo json_encode($ocupados);
    }
}
