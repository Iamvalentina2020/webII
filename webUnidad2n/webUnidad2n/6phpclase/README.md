# API REST para Mascotas y Productos

Esta API proporciona endpoints para manejar mascotas y productos en una única interfaz.

## Endpoints

La API tiene una estructura unificada para ambos recursos (mascotas y productos):

### Mascotas (pets)

- `GET /api/pets` - Obtener todas las mascotas
- `GET /api/pets/{id}` - Obtener una mascota específica
- `POST /api/pets` - Crear una nueva mascota
- `PUT /api/pets/{id}` - Actualizar una mascota existente
- `DELETE /api/pets/{id}` - Eliminar una mascota

### Productos (products)

- `GET /api/products` - Obtener todos los productos
- `POST /api/products` - Crear un nuevo producto
- `GET /api/products/{id}` - Obtener un producto específico
- `PUT /api/products/{id}` - Actualizar un producto existente
- `DELETE /api/products/{id}` - Eliminar un producto

## Ejemplos de uso

### Crear una mascota

```http
POST /api/pets
Content-Type: application/json

{
    "nombre": "Luna",
    "edad": "2",
    "descripcion": "Perro labrador"
}
```

### Crear un producto

```http
POST /api/products
Content-Type: application/json

{
    "nombre": "Comida para perros",
    "precio": 29.99,
    "descripcion": "Alimento premium para perros"
}
```

## Instalación

1. Importar el archivo `database.sql` en tu servidor MySQL
2. Configurar los datos de conexión en `config/database.php`
3. Asegurarse de que el servidor web tiene acceso a la carpeta del proyecto

## Estructura del Proyecto

```
├── api/
│   └── index.php          # Punto de entrada único de la API
├── config/
│   └── database.php       # Configuración de la base de datos
├── models/
│   ├── Pet.php           # Modelo para mascotas
│   └── Product.php       # Modelo para productos
└── database.sql          # Estructura de la base de datos
```
