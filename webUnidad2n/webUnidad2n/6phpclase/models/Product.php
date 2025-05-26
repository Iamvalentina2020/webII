<?php
class Product {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $nombre;
    public $precio;
    public $descripcion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los productos
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    // Obtener un producto por ID
    public function getOne() {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ?");
        $stmt->bind_param("s", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        if ($product) {
            $this->nombre = $product['nombre'];
            $this->precio = $product['precio'];
            $this->descripcion = $product['descripcion'];
            return true;
        }
        return false;
    }

    // Crear un nuevo producto
    public function create() {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (id, nombre, precio, descripcion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $this->id, $this->nombre, $this->precio, $this->descripcion);
        return $stmt->execute();
    }

    // Actualizar un producto
    public function update() {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET nombre = ?, precio = ?, descripcion = ? WHERE id = ?");
        $stmt->bind_param("sdss", $this->nombre, $this->precio, $this->descripcion, $this->id);
        return $stmt->execute();
    }

    // Eliminar un producto
    public function delete() {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        $stmt->bind_param("s", $this->id);
        return $stmt->execute();
    }
}
?>
