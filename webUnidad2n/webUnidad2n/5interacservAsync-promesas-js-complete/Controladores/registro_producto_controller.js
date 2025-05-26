import { productService } from "../service/product-service.js";

const formulario = document.querySelector("[data-form]");
formulario.addEventListener("submit", (evento) => {
    evento.preventDefault();
    const nombre = document.querySelector("[data-nombre]").value;
    const precio = document.querySelector("[data-precio]").value;
    const descripcion = document.querySelector("[data-descripcion]").value;

    productService.crearProducto(nombre, precio, descripcion).then((respuesta) => {
        window.location.href = "/Async-promesas-js/screens/registro_producto_completado.html";
    }).catch(error => console.log(error));
});