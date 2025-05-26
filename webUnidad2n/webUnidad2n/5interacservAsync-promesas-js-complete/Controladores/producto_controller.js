import { productService } from "../service/product-service.js";

const crear_nueva_fila = (nombre, precio, descripcion, id) => {
    const fila = document.createElement('tr');
    const contenido = `
            <td class="td" data-td>
            ${nombre}
            </td>
            <td>${precio}</td>
            <td>${descripcion}</td>
            <td>
            <ul class="table__button-control">
                <li>
                    <a
                    href="../screens/editar_producto.html?id=${id}"
                    class="simple-button simple-button--edit"
                    >Editar</a
                    >
                </li>
                <li>
                    <button
                    class="simple-button simple-button--delete"
                    type="button" id="${id}">
                    Eliminar
                    </button>
                </li>
                </ul>
            </td>
            `;
        fila.innerHTML = contenido;
        const btn = fila.querySelector("button");
        btn.addEventListener("click", () => {
            const id = btn.id;
            productService.eliminarProducto(id).then(respuesta => {
                alert("Producto eliminado");
                window.location.reload();
            }).catch(error => alert("Error al eliminar el producto"));
        });

        return fila; 
};

const table = document.querySelector("[data-table]");
productService
    .listaProductos()
    .then((data) => {
        data.forEach(({nombre, precio, descripcion, id}) => {
            const nuevaLinea = crear_nueva_fila(nombre, precio, descripcion, id);
            table.appendChild(nuevaLinea);
        });
    }).catch((error) => alert("Ocurrió un error al listar productos"));