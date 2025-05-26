<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../models/Pet.php';

$database = new Database();
$db = $database->getConnection();
$pet = new Pet($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            $pet->id = $_GET['id'];
            if($pet->getOne()) {
                http_response_code(200);
                echo json_encode([
                    "id" => $pet->id,
                    "nombre" => $pet->nombre,
                    "edad" => $pet->edad,
                    "descripcion" => $pet->descripcion
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Mascota no encontrada."]);
            }
        } else {
            $pets = $pet->getAll();
            http_response_code(200);
            echo json_encode($pets);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->nombre)) {
            $pet->id = uniqid();
            $pet->nombre = $data->nombre;
            $pet->edad = $data->edad ?? '';
            $pet->descripcion = $data->descripcion ?? '';

            if($pet->create()) {
                http_response_code(201);
                echo json_encode([
                    "message" => "Mascota creada con éxito.",
                    "id" => $pet->id,
                    "nombre" => $pet->nombre,
                    "edad" => $pet->edad,
                    "descripcion" => $pet->descripcion
                ]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo crear la mascota."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "No se puede crear la mascota. Datos incompletos."]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id) && !empty($data->nombre)) {
            $pet->id = $data->id;
            $pet->nombre = $data->nombre;
            $pet->edad = $data->edad ?? '';
            $pet->descripcion = $data->descripcion ?? '';

            if($pet->update()) {
                http_response_code(200);
                echo json_encode([
                    "message" => "Mascota actualizada con éxito.",
                    "id" => $pet->id,
                    "nombre" => $pet->nombre,
                    "edad" => $pet->edad,
                    "descripcion" => $pet->descripcion
                ]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo actualizar la mascota."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "No se puede actualizar la mascota. Datos incompletos."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $pet->id = $data->id;
            if($pet->delete()) {
                http_response_code(200);
                echo json_encode(["message" => "Mascota eliminada con éxito."]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo eliminar la mascota."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "No se puede eliminar la mascota. ID no proporcionado."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
?>
