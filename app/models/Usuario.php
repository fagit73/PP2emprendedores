<?php

class Usuario
{
    private $db;

    public function __construct()
    {

        $this->db = new Database();
    }

    public function registro()
    {
        $this->stmt->execute();

        return $this->stmt->fetch(PDO::FETCH_OBJ);
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
        $this->db->query("
        INSERT INTO usuarios (
            nombre_completo,
            dni,
            celular,
            email_institucional,
            email_recuperacion,
            password_hash,
            id_rol,
            activo
        )
        VALUES (
            :nombre_completo,
            :dni,
            :celular,
            :email_institucional,
            :email_recuperacion,
            :password_hash,
            :id_rol,
            :activo
        )
    ");

        $this->db->bind(':nombre_completo', $datos['nombre_completo']);
        $this->db->bind(':dni', $datos['dni']);
        $this->db->bind(':celular', $datos['celular']);
        $this->db->bind(':email_institucional', $datos['email_institucional']);
        $this->db->bind(':email_recuperacion', $datos['email_recuperacion']);
        $this->db->bind(':password_hash', $datos['password_hash']);
        $this->db->bind(':id_rol', $datos['id_rol']);
        $this->db->bind(':activo', 1);

        return $this->db->execute();
    }
}
