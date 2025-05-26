<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/database.php';
include_once '../models/Pet.php';
include_once '../models/Product.php';

$database = new Database();
$db = $database->getConnection();

// Obtener el tipo de recurso (pets o products) y la acción de la URL
$request = $_SERVER['REQUEST_URI'];
$params = explode('/', trim($request, '/'));
$resource = $params[count($params)-2] ?? ''; // pets o products
$id = $params[count($params)-1] ?? null;

if ($id === 'api' || $id === $resource) {
    $id = null;
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

function handleRequest($resource, $method, $id, $data, $db) {
    $model = null;
    
    // Inicializar el modelo correcto según el recurso
    switch($resource) {
        case 'pets':
            $model = new Pet($db);
            break;
        case 'products':
            $model = new Product($db);
            break;
        default:
            http_response_code(404);
            return ["error" => "Recurso no encontrado"];
    }

    // Manejar la petición según el método HTTP
    switch($method) {
        case 'GET':
            if($id) {
                $model->id = $id;
                if($model->getOne()) {
                    return $model;
                } else {
                    http_response_code(404);
                    return ["error" => "Elemento no encontrado"];
                }
            } else {
                return $model->getAll();
            }

        case 'POST':
            if(empty($data)) {
                http_response_code(400);
                return ["error" => "Datos no proporcionados"];
            }

            foreach($data as $key => $value) {
                if(property_exists($model, $key)) {
                    $model->$key = $value;
                }
            }

            if($model->create()) {
                http_response_code(201);
                return ["message" => "Elemento creado con éxito", "data" => $model];
            } else {
                http_response_code(503);
                return ["error" => "No se pudo crear el elemento"];
            }

        case 'PUT':
            if(empty($data) || empty($id)) {
                http_response_code(400);
                return ["error" => "Datos no proporcionados"];
            }

            $model->id = $id;
            foreach($data as $key => $value) {
                if(property_exists($model, $key)) {
                    $model->$key = $value;
                }
            }

            if($model->update()) {
                return ["message" => "Elemento actualizado con éxito", "data" => $model];
            } else {
                http_response_code(503);
                return ["error" => "No se pudo actualizar el elemento"];
            }

        case 'DELETE':
            if(empty($id)) {
                http_response_code(400);
                return ["error" => "ID no proporcionado"];
            }

            $model->id = $id;
            if($model->delete()) {
                return ["message" => "Elemento eliminado con éxito"];
            } else {
                http_response_code(503);
                return ["error" => "No se pudo eliminar el elemento"];
            }

        default:
            http_response_code(405);
            return ["error" => "Método no permitido"];
    }
}

// Procesar la petición
$result = handleRequest($resource, $method, $id, $data, $db);
echo json_encode($result);
?>
