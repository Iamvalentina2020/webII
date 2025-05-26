import {clientService} from "../service/client-service.js"
const crear_nueva_fila=(nombre,email,id)=>{// recepciono datos 
    const fila = document.createElement('tr');// creo una nueva filla en la tabla
    //guardo html en una variable y tambien llamo a mis datos de entrada
    const contenido = `
            <td class="td" data-td>
            ${nombre}
            </td>
            <td>${email}</td>
            <td>
            <ul class="table__button-control">
                <li>
                    <a
                    href="../screens/editar_cliente.html?id=${id}"
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
        fila.innerHTML=contenido;
        const btn =fila.querySelector("button")
        btn.addEventListener("click",()=>{
            const id=btn.id;
            clientService.eliminarCliente(id).then(respuesta=>{
                alert("eliminado")
            }).catch(error=> alert("error"))

        })

        return fila; 
};
// --------- inicial echo en clases ------------
/*
const table = document.querySelector("[data-table]");
clientService.listaclientes()
    .then((data)=>{
     data.forEach((perfil)=>{ 
        const nuevafila=crear_nueva_fila(perfil.nombre,perfil.email,perfil.id) // para eliminar y actualizar agregamos id
        table.appendChild(nuevafila)
    });
}).catch((error)=>alert("error"));
*/



// _----------- mejorado codigo ordenado limpio--------------
const table = document.querySelector("[data-table]");
clientService
.listaclientes()
    .then((data)=>{
    data.forEach(({nombre,email,id}) => {
                const nuevaLinea= crear_nueva_fila(nombre,email,id)// llamo a 3 referencias
                table.appendChild(nuevaLinea)
                
                });
        

    console.log(data);// verifico datos 
}).catch((error)=>alert("ocurrio un error"));