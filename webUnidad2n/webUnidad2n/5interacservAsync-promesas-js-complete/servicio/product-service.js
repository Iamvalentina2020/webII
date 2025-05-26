const API_URL = "http://localhost:3000";

const listaProductos = () => 
    fetch(`${API_URL}/producto`)
        .then(response => response.json())
        .catch(error => {
            console.error("Error:", error);
            throw new Error("Error al listar los productos");
        });

const crearProducto = (nombre, precio, descripcion) => {
    return fetch(`${API_URL}/producto`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ 
            nombre, 
            precio, 
            descripcion,
            id: crypto.randomUUID()
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('Error al crear el producto');
        return response.json();
    });
};

const eliminarProducto = (id) => {
    return fetch(`${API_URL}/producto/${id}`, {
        method: "DELETE"
    })
    .then(response => {
        if (!response.ok) throw new Error('Error al eliminar el producto');
        return response.json();
    });
};

const producto = (id) => {
    return fetch(`${API_URL}/producto/${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Producto no encontrado');
            return response.json();
        });
};

const actualizarProducto = (nombre, precio, descripcion, id) => {
    return fetch(`${API_URL}/producto/${id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ nombre, precio, descripcion })
    })
    .then(response => {
        if (!response.ok) throw new Error('Error al actualizar el producto');
        return response.json();
    });
};

export const productService = {
    listaProductos,
    crearProducto,
    eliminarProducto,
    producto,
    actualizarProducto
};