<?php
header("Content-Type: text/html; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "doguitodb";

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

echo "<h1>Configuración de la base de datos</h1>";

// Crear la base de datos si no existe
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "<p>Base de datos creada o ya existente</p>";
} else {
    echo "<p>Error al crear la base de datos: " . $conn->error . "</p>";
}

// Seleccionar la base de datos
$conn->select_db($dbname);

// Crear la tabla perfil si no existe
$sql = "CREATE TABLE IF NOT EXISTS perfil (
    id VARCHAR(36) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>Tabla 'perfil' creada o ya existente</p>";
} else {
    echo "<p>Error al crear la tabla perfil: " . $conn->error . "</p>";
}

// Crear la tabla productos si no existe
$sql = "CREATE TABLE IF NOT EXISTS productos (
    id VARCHAR(36) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    descripcion TEXT
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>Tabla 'productos' creada o ya existente</p>";
} else {
    echo "<p>Error al crear la tabla productos: " . $conn->error . "</p>";
}

// Crear la tabla pets si no existe
$sql = "CREATE TABLE IF NOT EXISTS pets (
    id VARCHAR(36) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    edad VARCHAR(20),
    descripcion TEXT
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>Tabla 'pets' creada o ya existente</p>";
} else {
    echo "<p>Error al crear la tabla pets: " . $conn->error . "</p>";
}

// Insertar algunos datos de ejemplo en la tabla perfil si está vacía
$result = $conn->query("SELECT COUNT(*) as count FROM perfil");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $sql = "INSERT INTO perfil (id, nombre, email) VALUES 
            ('1', 'Ana García', 'ana@ejemplo.com'),
            ('2', 'Luis Rodríguez', 'luis@ejemplo.com'),
            ('3', 'María López', 'maria@ejemplo.com')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>Datos de ejemplo añadidos a la tabla 'perfil'</p>";
    } else {
        echo "<p>Error al insertar datos de ejemplo en perfil: " . $conn->error . "</p>";
    }
}

// Insertar algunos datos de ejemplo en la tabla productos si está vacía
$result = $conn->query("SELECT COUNT(*) as count FROM productos");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $sql = "INSERT INTO productos (id, nombre, precio, descripcion) VALUES 
            ('1', 'Comida para perros', 25.99, 'Alimento premium para perros adultos'),
            ('2', 'Juguete para gatos', 12.50, 'Juguete interactivo con plumas'),
            ('3', 'Correa para perros', 18.75, 'Correa extensible resistente')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>Datos de ejemplo añadidos a la tabla 'productos'</p>";
    } else {
        echo "<p>Error al insertar datos de ejemplo en productos: " . $conn->error . "</p>";
    }
}

// Insertar algunos datos de ejemplo en la tabla pets si está vacía
$result = $conn->query("SELECT COUNT(*) as count FROM pets");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $sql = "INSERT INTO pets (id, nombre, edad, descripcion) VALUES 
            ('1', 'Max', '5', 'Labrador Retriever, muy amigable'),
            ('2', 'Luna', '3', 'Gato siamés, juguetona'),
            ('3', 'Rocky', '2', 'Bulldog francés, tranquilo')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>Datos de ejemplo añadidos a la tabla 'pets'</p>";
    } else {
        echo "<p>Error al insertar datos de ejemplo en pets: " . $conn->error . "</p>";
    }
}

$conn->close();

echo "<p>¡Configuración completada con éxito!</p>";
echo "<p><a href='http://localhost/api1/conexion.php'>Probar API de clientes</a></p>";
echo "<p><a href='http://localhost/api1/productos.php'>Probar API de productos</a></p>";
echo "<p><a href='http://localhost/api1/pets.php'>Probar API de mascotas</a></p>";
?>