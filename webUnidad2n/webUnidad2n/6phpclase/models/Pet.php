<?php
class Pet {
    private $conn;
    private $table_name = "pets";

    public $id;
    public $nombre;
    public $edad;
    public $descripcion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las mascotas
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);
        $pets = [];
        while ($row = $result->fetch_assoc()) {
            $pets[] = $row;
        }
        return $pets;
    }

    // Obtener una mascota por ID
    public function getOne() {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ?");
        $stmt->bind_param("s", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pet = $result->fetch_assoc();
        if ($pet) {
            $this->nombre = $pet['nombre'];
            $this->edad = $pet['edad'];
            $this->descripcion = $pet['descripcion'];
            return true;
        }
        return false;
    }

    // Crear una nueva mascota
    public function create() {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (id, nombre, edad, descripcion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->id, $this->nombre, $this->edad, $this->descripcion);
        return $stmt->execute();
    }

    // Actualizar una mascota
    public function update() {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET nombre = ?, edad = ?, descripcion = ? WHERE id = ?");
        $stmt->bind_param("ssss", $this->nombre, $this->edad, $this->descripcion, $this->id);
        return $stmt->execute();
    }

    // Eliminar una mascota
    public function delete() {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        $stmt->bind_param("s", $this->id);
        return $stmt->execute();
    }
}
?>
