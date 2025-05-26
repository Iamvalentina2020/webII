<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "doguitodb";

    // Conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexion
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(["error" => "conexion fallida: " . $conn->connect_error]));
    }

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            $id = $_GET['id'] ?? null;
            if ($id) {
                $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
                $stmt->bind_param("s", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $producto = $result->fetch_assoc();
                if ($producto) {
                    echo json_encode($producto);
                } else {
                    http_response_code(404);
                    echo json_encode(["error" => "Producto no encontrado"]);
                }
                $stmt->close();
            } else {
                $result = $conn->query("SELECT * FROM productos");
                $productos = [];
                while ($row = $result->fetch_assoc()) {
                    $productos[] = $row;
                }
                echo json_encode($productos);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input['id'] ?? uniqid(); 
            $nombre = $input['nombre'] ?? '';
            $precio = $input['precio'] ?? null;
            $descripcion = $input['descripcion'] ?? '';

            if (empty($nombre) || !is_numeric($precio)) {
                http_response_code(400);
                echo json_encode(["error" => "Faltan datos requeridos o el precio no es válido (nombre y precio son obligatorios)"]);
                exit();
            }

            $stmt = $conn->prepare("INSERT INTO productos (id, nombre, precio, descripcion) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $id, $nombre, $precio, $descripcion);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(["message" => "Producto creado", "id" => $id, "nombre" => $nombre, "precio" => $precio, "descripcion" => $descripcion]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al crear el producto: " . $stmt->error]);
            }
            $stmt->close();
            break;

        case 'PUT':
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input['id'] ?? '';
            $nombre = $input['nombre'] ?? '';
            $precio = $input['precio'] ?? 0.00;
            $descripcion = $input['descripcion'] ?? '';
            if (empty($id) || empty($nombre) || $precio === null) {
                http_response_code(400);
                echo json_encode(["error" => "Faltan datos requeridos (id, nombre y precio son obligatorios)"]);
                exit();
            }
            $stmt = $conn->prepare("UPDATE productos SET nombre = ?, precio = ?, descripcion = ? WHERE id = ?");
            $stmt->bind_param("sdss", $nombre, $precio, $descripcion, $id);
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["message" => "Producto actualizado", "id" => $id, "nombre" => $nombre, "precio" => $precio, "descripcion" => $descripcion]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al actualizar el producto"]);
            }
            $stmt->close();
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                http_response_code(400);
                echo json_encode(["error" => "ID requerido"]);
                exit();
            }
            $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->bind_param("s", $id);
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["message" => "Producto eliminado"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al eliminar el producto"]);
            }
            $stmt->close();
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
    }

    $conn->close();
?>