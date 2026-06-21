<?php

class Reporte {
    private $db;
    public function __construct() { $this->db = new Database; }

    public function getResumenReservas() {
        $this->db->query("SELECT t.nombre, COUNT(r.id_reserva) as total 
                          FROM tipos_uso t 
                          LEFT JOIN reservas r ON t.id_tipo_uso = r.id_tipo_uso 
                          GROUP BY t.nombre");
        return $this->db->registros();
    }
}