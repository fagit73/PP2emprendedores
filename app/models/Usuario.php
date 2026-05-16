<?php

class Usuario
{
    private $db;

    public function __construct()
    {

        $this->db = new Database();
    }

    // Buscar usuario por email
    public function buscarUsuarioPorEmail($email)
    {
        $this->db->query("
        SELECT u.*, r.nombre_rol
        FROM usuarios u
        INNER JOIN roles r ON u.id_rol = r.id_rol
        WHERE u.email_institucional = :email
        AND u.activo = 1
    ");

        $this->db->bind(':email', $email);

        return $this->db->registro();
    }


    public function registrar($datos)
    {
        $this->db->query("INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)");

        // Vinculamos los datos
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':email', $datos['email']);
        $this->db->bind(':password', $datos['password']); // Ya viene encriptada del controlador

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
