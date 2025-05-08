import { productoService } from "../service/producto-service.js";

const formulario = document.querySelector("[data-form]");

formulario.addEventListener("submit", (evento) => {
  evento.preventDefault();

  const nombre = document.querySelector("[data-nombre]").value;
  const precio = document.querySelector("[data-precio]").value;
  const descripcion = document.querySelector("[data-descripcion]").value;

  productoService
    .crearProducto(nombre, precio, descripcion)
    .then(() => {
      window.location.href = "../screens/registro_completado.html";
    })
    .catch((err) => console.error(err));
});
