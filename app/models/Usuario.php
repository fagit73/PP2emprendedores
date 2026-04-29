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
        // 1. Escribimos la consulta con un marcador (:email)
        $this->db->query("SELECT * FROM usuarios WHERE email = :email");

        // 2. Vinculamos el marcador con el valor real
        $this->db->bind(':email', $email);

        // 3. Obtenemos el resultado
        $usuario = $this->db->registro();

        return $usuario; // Devuelve el objeto con id, nombre, password, etc.
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
