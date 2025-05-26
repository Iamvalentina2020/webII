<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once './config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Obtener la ruta de la petición
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));
$endpoint = end($path_parts);

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Obtener el ID si existe en la URL
$id = null;
if (preg_match('/\/(\d+)$/', $path, $matches)) {
    $id = $matches[1];
    $endpoint = prev($path_parts); // Ajustar el endpoint si hay ID
}

switch ($endpoint) {
    case 'perfil':
        handlePerfilRequest($method, $conn, $id);
        break;
    case 'producto':
        handleProductoRequest($method, $conn, $id);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint no encontrado']);
        break;
}

function handlePerfilRequest($method, $conn, $id) {
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $conn->prepare("SELECT * FROM perfil WHERE id = ?");
                $stmt->bind_param("s", $id);
            } else {
                $stmt = $conn->prepare("SELECT * FROM perfil");
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            echo json_encode($id ? ($data[0] ?? null) : $data);
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"));
            $stmt = $conn->prepare("INSERT INTO perfil (id, nombre, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $data->id, $data->nombre, $data->email);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'id' => $data->id,
                    'nombre' => $data->nombre,
                    'email' => $data->email
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al crear el perfil']);
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));
            $stmt = $conn->prepare("UPDATE perfil SET nombre = ?, email = ? WHERE id = ?");
            $stmt->bind_param("sss", $data->nombre, $data->email, $id);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'id' => $id,
                    'nombre' => $data->nombre,
                    'email' => $data->email
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al actualizar el perfil']);
            }
            break;

        case 'DELETE':
            $stmt = $conn->prepare("DELETE FROM perfil WHERE id = ?");
            $stmt->bind_param("s", $id);
            
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Perfil eliminado']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al eliminar el perfil']);
            }
            break;
    }
}

function handleProductoRequest($method, $conn, $id) {
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
                $stmt->bind_param("s", $id);
            } else {
                $stmt = $conn->prepare("SELECT * FROM productos");
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            echo json_encode($id ? ($data[0] ?? null) : $data);
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"));
            $stmt = $conn->prepare("INSERT INTO productos (id, nombre, precio, descripcion) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $data->id, $data->nombre, $data->precio, $data->descripcion);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'id' => $data->id,
                    'nombre' => $data->nombre,
                    'precio' => $data->precio,
                    'descripcion' => $data->descripcion
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al crear el producto']);
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));
            $stmt = $conn->prepare("UPDATE productos SET nombre = ?, precio = ?, descripcion = ? WHERE id = ?");
            $stmt->bind_param("sdss", $data->nombre, $data->precio, $data->descripcion, $id);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'id' => $id,
                    'nombre' => $data->nombre,
                    'precio' => $data->precio,
                    'descripcion' => $data->descripcion
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al actualizar el producto']);
            }
            break;

        case 'DELETE':
            $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->bind_param("s", $id);
            
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Producto eliminado']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al eliminar el producto']);
            }
            break;
    }
}
?>
