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

    // Verificar conexión
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(["error" => "conexion fallida: " . $conn->connect_error]));
    }

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            $id = $_GET['id'] ?? null;
            if ($id) {
                $stmt = $conn->prepare("SELECT * FROM perfil WHERE id = ?");
                $stmt->bind_param("s", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $cliente = $result->fetch_assoc();
                if ($cliente) {
                    echo json_encode($cliente);
                } else {
                    http_response_code(404);
                    echo json_encode(["error" => "Cliente no encontrado"]);
                }
                $stmt->close();
            } else {
                $result = $conn->query("SELECT * FROM perfil");
                $clientes = [];
                while ($row = $result->fetch_assoc()) {
                    $clientes[] = $row;
                }
                echo json_encode($clientes);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input['id']; // Usamos solo el ID enviado desde el cliente
            $nombre = $input['nombre'] ?? '';
            $email = $input['email'] ?? '';

            if (empty($nombre) || empty($email)) {
                http_response_code(400);
                echo json_encode(["error" => "Faltan datos requeridos (nombre y email son obligatorios)"]);
                exit();
            }

            $stmt = $conn->prepare("INSERT INTO perfil (id, nombre, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $id, $nombre, $email);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(["message" => "Cliente creado", "id" => $id, "nombre" => $nombre, "email" => $email]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al crear el cliente: " . $stmt->error]);
            }
            $stmt->close();
            break;

        case 'PUT':
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input['id'] ?? '';
            $nombre = $input['nombre'] ?? '';
            $email = $input['email'] ?? '';
            
            if (empty($id) || empty($nombre) || empty($email)) {
                http_response_code(400);
                echo json_encode(["error" => "Faltan datos requeridos (id, nombre y email son obligatorios)"]);
                exit();
            }
            
            $stmt = $conn->prepare("UPDATE perfil SET nombre = ?, email = ? WHERE id = ?");
            $stmt->bind_param("sss", $nombre, $email, $id);
            
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["message" => "Cliente actualizado", "id" => $id, "nombre" => $nombre, "email" => $email]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al actualizar el cliente"]);
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
            
            $stmt = $conn->prepare("DELETE FROM perfil WHERE id = ?");
            $stmt->bind_param("s", $id);
            
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["message" => "Cliente eliminado"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al eliminar el cliente"]);
            }
            $stmt->close();
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
    }

    $conn->close();
?>