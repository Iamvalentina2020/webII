const crearProducto = (nombre, precio, descripcion) => {
    return fetch("http://localhost:3000/productos", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ nombre, precio, descripcion, id: uuid.v4() }),
    });
  };
  
  export const productoService = {
    crearProducto,
  };
  