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
                $stmt = $conn->prepare("SELECT * FROM pets WHERE id = ?");
                $stmt->bind_param("s", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $mascota = $result->fetch_assoc();
                if ($mascota) {
                    echo json_encode($mascota);
                } else {
                    http_response_code(404);
                    echo json_encode(["error" => "Mascota no encontrada"]);
                }
                $stmt->close();
            } else {
                $result = $conn->query("SELECT * FROM pets");
                $mascotas = [];
                while ($row = $result->fetch_assoc()) {
                    $mascotas[] = $row;
                }
                echo json_encode($mascotas);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input['id'] ?? uniqid(); 
            $nombre = $input['nombre'] ?? '';
            $edad = $input['edad'] ?? '';
            $descripcion = $input['descripcion'] ?? '';

            if (empty($nombre)) {
                http_response_code(400);
                echo json_encode(["error" => "Falta el nombre de la mascota (obligatorio)"]);
                exit();
            }

            $stmt = $conn->prepare("INSERT INTO pets (id, nombre, edad, descripcion) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $id, $nombre, $edad, $descripcion);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(["message" => "Mascota creada", "id" => $id, "nombre" => $nombre, "edad" => $edad]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al crear la mascota: " . $stmt->error]);
            }
            $stmt->close();
            break;

        case 'PUT':
            $input = json_decode(file_get_contents("php://input"), true);
            $id = $input['id'] ?? '';
            $nombre = $input['nombre'] ?? '';
            $edad = $input['edad'] ?? '';
            $descripcion = $input['descripcion'] ?? '';
            
            if (empty($id) || empty($nombre)) {
                http_response_code(400);
                echo json_encode(["error" => "Faltan datos requeridos (id y nombre son obligatorios)"]);
                exit();
            }
            
            $stmt = $conn->prepare("UPDATE pets SET nombre = ?, edad = ?, descripcion = ? WHERE id = ?");
            $stmt->bind_param("ssss", $nombre, $edad, $descripcion, $id);
            
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["message" => "Mascota actualizada", "id" => $id, "nombre" => $nombre, "edad" => $edad]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al actualizar la mascota"]);
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
            
            $stmt = $conn->prepare("DELETE FROM pets WHERE id = ?");
            $stmt->bind_param("s", $id);
            
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["message" => "Mascota eliminada"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al eliminar la mascota"]);
            }
            $stmt->close();
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
    }

    $conn->close();
?>