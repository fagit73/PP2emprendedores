<?php

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_PERSISTENT => true, // conexion persistente para mayor velocidad
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // que avise si hay errores
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo "Error de conexion: " . $this->error;
        }
    }

    // preparacion de consulta para evitar sql injection
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // vinculamos los valores 
    public function bind($param, $valor, $tipo = null) {
        if (is_null($tipo)) {
            if (is_int($valor)) $tipo = PDO::PARAM_INT;
            elseif (is_bool($valor)) $tipo = PDO::PARAM_BOOL;
            elseif (is_null($valor)) $tipo = PDO::PARAM_NULL;
            else $tipo = PDO::PARAM_STR;
        }
        $this->stmt->bindValue($param, $valor, $tipo);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    // obtener un solo registro
    public function registro() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

}