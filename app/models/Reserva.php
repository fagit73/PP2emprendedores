<?php
class Reserva
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getHorariosOcupados($fecha)
    {
        $this->db->query("SELECT id_horario FROM reservas WHERE fecha_reserva = :fecha AND estado != 'CANCELADA'");
        $this->db->bind(':fecha', $fecha);
        // Usamos el nuevo método 'registros()'
        $resultados = $this->db->registros();

        $horariosOcupados = [];
        foreach ($resultados as $fila) {
            $horariosOcupados[] = (int)$fila->id_horario;
        }
        return $horariosOcupados;
    }

    public function getAllHorarios()
    {
        $this->db->query("SELECT * FROM horarios ORDER BY hora_inicio ASC");
        
        return $this->db->registros();
    }

    public function getPalabrasClaves()
    {
        $this->db->query("SELECT * FROM palabras_clave");
        
        return $this->db->registros();
    }

    public function getAllSalas()
    {
        $this->db->query("SELECT * FROM salas WHERE activa = 1 ORDER BY nombre ASC");
        return $this->db->registros();
    }


    public function guardarReserva($datos)
    {
        
        $sql = "INSERT INTO reservas (id_usuario, id_tipo_uso, id_horario, id_sala, fecha_reserva, motivo, estado) 
            VALUES (:id_usuario, :id_tipo_uso, :id_horario, :id_sala, :fecha, :motivo, :estado)";

        $this->db->query($sql);

        $datos['motivo'] = '';

        $this->db->bind(':id_usuario', $datos['id_usuario']);
        $this->db->bind(':id_tipo_uso', $datos['id_tipo_uso']);
        $this->db->bind(':id_horario', $datos['id_horario']);
        $this->db->bind(':id_sala', $datos['id_sala']); 
        $this->db->bind(':fecha', $datos['fecha']);
        $this->db->bind(':motivo', $datos['motivo']);
        $this->db->bind(':estado', $datos['estado']);

        if ($this->db->execute()) {
            return $this->db->ultimoId(); 
        }

        return false;
    }

   public function guardarProyecto($datosProyecto)
    {
        $this->db->query("INSERT INTO proyectos (id_reserva, tipo_carga, titulo, responsable_proyecto, fecha_inicio, fecha_fin, descripcion, evaluacion, palabras_clave) 
                      VALUES (:id_reserva, :tipo, :titulo, :responsable, :f_ini, :f_fin, :desc, :eval, :claves)");

        $this->db->bind(':id_reserva', $datosProyecto['id_reserva']);
        $this->db->bind(':tipo', $datosProyecto['tipo_carga']);
        $this->db->bind(':titulo', $datosProyecto['titulo']);
        $this->db->bind(':responsable', $datosProyecto['responsable']);
        $this->db->bind(':f_ini', $datosProyecto['fecha_inicio']);
        $this->db->bind(':f_fin', $datosProyecto['fecha_fin']);
        $this->db->bind(':desc', $datosProyecto['descripcion']);
        $this->db->bind(':eval', $datosProyecto['evaluacion']);
        $this->db->bind(':claves', $datosProyecto['palabras_clave']);

        return $this->db->execute();
    }

    // Guarda las palabras clave en la tabla normalizada (palabras_clave + proyecto_keywords).
    // Acepta texto separado por comas, ej: "Matemática, Ingles".
    public function asignarPalabrasClave($id_proyecto, $palabras)
    {
        $lista = array_filter(array_map('trim', explode(',', $palabras)));

        foreach ($lista as $nombre) {
            // Crea la palabra clave si no existe todavía.
            $this->db->query("INSERT IGNORE INTO palabras_clave (nombre) VALUES (:nombre)");
            $this->db->bind(':nombre', $nombre);
            $this->db->execute();

            // Obtiene su id.
            $this->db->query("SELECT id_keyword FROM palabras_clave WHERE nombre = :nombre");
            $this->db->bind(':nombre', $nombre);
            $kw = $this->db->registro();
            if (!$kw) {
                continue;
            }

            // Vincula keyword <-> proyecto.
            $this->db->query("INSERT IGNORE INTO proyecto_keywords (id_proyecto, id_keyword) VALUES (:id_proyecto, :id_keyword)");
            $this->db->bind(':id_proyecto', $id_proyecto);
            $this->db->bind(':id_keyword', $kw->id_keyword);
            $this->db->execute();
        }
    }

    public function getReservasUsuarioConfirmadas($id_usuario)
    {
        $this->db->query("SELECT r.*, t.nombre, h.hora_inicio, h.hora_fin, 
                  r.modo_proposito AS tipo_carga, p.titulo, p.archivo, p.docente AS responsable_proyecto,
                  p.fecha_inicio, p.fecha_fin, p.descripcion, p.evaluacion,
                  COALESCE((SELECT GROUP_CONCAT(pc.nombre SEPARATOR ', ')
                            FROM proyecto_keywords pk
                            JOIN palabras_clave pc ON pk.id_keyword = pc.id_keyword
                            WHERE pk.id_proyecto = p.id_proyecto), '') AS palabras_clave
                  FROM reservas r
                  JOIN tipos_uso t ON r.id_tipo_uso = t.id_tipo_uso
                  JOIN horarios h ON r.id_horario = h.id_horario
                  LEFT JOIN proyectos p ON r.id_reserva = p.id_reserva
                          WHERE r.id_usuario = :id_usuario AND r.estado = 'ACTIVA'
                          ORDER BY r.fecha_reserva ASC");
        $this->db->bind(':id_usuario', $id_usuario);
        return $this->db->registros();
    }

    public function getReservasUsuarioPendientes($id_usuario)
    {
        $this->db->query("SELECT r.*, t.nombre, h.hora_inicio, h.hora_fin, 
                  r.modo_proposito AS tipo_carga, p.titulo, p.archivo, p.docente AS responsable_proyecto,
                  p.fecha_inicio, p.fecha_fin, p.descripcion, p.evaluacion,
                  COALESCE((SELECT GROUP_CONCAT(pc.nombre SEPARATOR ', ')
                            FROM proyecto_keywords pk
                            JOIN palabras_clave pc ON pk.id_keyword = pc.id_keyword
                            WHERE pk.id_proyecto = p.id_proyecto), '') AS palabras_clave
                  FROM reservas r
                  JOIN tipos_uso t ON r.id_tipo_uso = t.id_tipo_uso
                  JOIN horarios h ON r.id_horario = h.id_horario
                  LEFT JOIN proyectos p ON r.id_reserva = p.id_reserva
                          WHERE r.id_usuario = :id_usuario AND r.estado = 'PENDIENTE'
                          ORDER BY r.fecha_reserva ASC");
        $this->db->bind(':id_usuario', $id_usuario);
        return $this->db->registros();
    }

    public function getReservasAConfirmar()
    {
        $this->db->query("SELECT r.*, t.nombre, h.hora_inicio, h.hora_fin, 
                  r.modo_proposito AS tipo_carga, p.titulo, p.archivo, p.docente AS responsable_proyecto,
                  p.fecha_inicio, p.fecha_fin, p.descripcion, p.evaluacion,
                  COALESCE((SELECT GROUP_CONCAT(pc.nombre SEPARATOR ', ')
                            FROM proyecto_keywords pk
                            JOIN palabras_clave pc ON pk.id_keyword = pc.id_keyword
                            WHERE pk.id_proyecto = p.id_proyecto), '') AS palabras_clave
                  FROM reservas r
                  JOIN tipos_uso t ON r.id_tipo_uso = t.id_tipo_uso
                  JOIN horarios h ON r.id_horario = h.id_horario
                  LEFT JOIN proyectos p ON r.id_reserva = p.id_reserva
                          WHERE r.estado = 'PENDIENTE'
                          ORDER BY r.fecha_reserva ASC");

        return $this->db->registros();
    }


    // Obtener una reserva por ID
    public function getReservaById($id)
    {
        $this->db->query("SELECT * FROM reservas WHERE id_reserva = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro(); // Usamos 'registro' porque es solo uno
    }

    // Actualizar estado (para confirmar o cancelar)
    public function actualizarEstado($id, $estado)
    {
        $this->db->query("UPDATE reservas SET estado = :estado WHERE id_reserva = :id");
        $this->db->bind(':estado', $estado);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getProyectoByReservaId($id_reserva)
    {
        $this->db->query("SELECT * FROM proyectos WHERE id_reserva = :id_reserva");
        $this->db->bind(':id_reserva', $id_reserva);

        // CAMBIA 'single()' por el nombre que encontraste en tu clase Database
        return $this->db->registro();
    }

    public function getHorarios()
    {
        $this->db->query("SELECT * FROM horarios");
        // Usa 'registros()' porque vas a traer muchos horarios
        return $this->db->registros();
    }
    public function actualizarReserva($datos)
    {
        // Actualizamos la reserva asegurando que pertenezca al usuario de la sesión
        $sql = "UPDATE reservas 
            SET id_tipo_uso = :id_tipo_uso, 
                id_horario = :id_horario, 
                fecha_reserva = :fecha, 
                motivo = :motivo 
            WHERE id_reserva = :id_reserva 
            AND id_usuario = :id_usuario"; // <--- CLAVE DE SEGURIDAD

        $this->db->query($sql);

        $this->db->bind(':id_reserva', $datos['id_reserva']);
        $this->db->bind(':id_usuario', $datos['id_usuario']); // Validado por sesión
        $this->db->bind(':id_tipo_uso', $datos['id_tipo_uso']);
        $this->db->bind(':id_horario', $datos['id_horario']);
        $this->db->bind(':fecha', $datos['fecha']);
        $this->db->bind(':motivo', $datos['motivo']);

        return $this->db->execute();
    }

    public function actualizarProyecto($datos)
    {
        $sql = "UPDATE proyectos SET 
                titulo = :titulo, 
                responsable_proyecto = :responsable, 
                fecha_inicio = :f_ini, 
                fecha_fin = :f_fin, 
                descripcion = :desc, 
                evaluacion = :eval, 
                palabras_clave = :claves 
            WHERE id_reserva = :id_reserva";

        $this->db->query($sql);
        $this->db->bind(':id_reserva', $datos['id_reserva']);
        $this->db->bind(':titulo', $datos['titulo']);
        $this->db->bind(':responsable', $datos['responsable']);
        $this->db->bind(':f_ini', $datos['fecha_inicio']);
        $this->db->bind(':f_fin', $datos['fecha_fin']);
        $this->db->bind(':desc', $datos['descripcion']);
        $this->db->bind(':eval', $datos['evaluacion']);
        $this->db->bind(':claves', $datos['palabras_clave']);

        // Ejecuta y devuelve si tuvo éxito
        return $this->db->execute();
    }
}
