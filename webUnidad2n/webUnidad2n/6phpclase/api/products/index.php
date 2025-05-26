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
include_once '../../models/Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            $product->id = $_GET['id'];
            if($product->getOne()) {
                http_response_code(200);
                echo json_encode([
                    "id" => $product->id,
                    "nombre" => $product->nombre,
                    "precio" => $product->precio,
                    "descripcion" => $product->descripcion
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Producto no encontrado."]);
            }
        } else {
            $products = $product->getAll();
            http_response_code(200);
            echo json_encode($products);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->nombre) && isset($data->precio)) {
            $product->id = uniqid();
            $product->nombre = $data->nombre;
            $product->precio = $data->precio;
            $product->descripcion = $data->descripcion ?? '';

            if($product->create()) {
                http_response_code(201);
                echo json_encode([
                    "message" => "Producto creado con éxito.",
                    "id" => $product->id,
                    "nombre" => $product->nombre,
                    "precio" => $product->precio,
                    "descripcion" => $product->descripcion
                ]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo crear el producto."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "No se puede crear el producto. Datos incompletos."]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id) && !empty($data->nombre) && isset($data->precio)) {
            $product->id = $data->id;
            $product->nombre = $data->nombre;
            $product->precio = $data->precio;
            $product->descripcion = $data->descripcion ?? '';

            if($product->update()) {
                http_response_code(200);
                echo json_encode([
                    "message" => "Producto actualizado con éxito.",
                    "id" => $product->id,
                    "nombre" => $product->nombre,
                    "precio" => $product->precio,
                    "descripcion" => $product->descripcion
                ]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo actualizar el producto."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "No se puede actualizar el producto. Datos incompletos."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $product->id = $data->id;
            if($product->delete()) {
                http_response_code(200);
                echo json_encode(["message" => "Producto eliminado con éxito."]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo eliminar el producto."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "No se puede eliminar el producto. ID no proporcionado."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
?>
