/*const crear_nueva_fila=(nombre,email)=>{ //recepciono datos
    const fila = document.createElement('tr') //creo una nueva fila en la tabla
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
                        href="../screens/editar_cliente.html"
                        class="simple-button simple-button--edit"
                        >Editar</a
                    >
                    </li>
                    <li>
                    <button
                        class="simple-button simple-button--delete"
                        type="button"
                    >
                        Eliminar
                    </button>
                    </li>
                </ul>
            </td>
            `;
        fila.innerHTML=contenido;
        return fila;
};

const table = document.querySelector("[data-table]");*/

//esta es la primera promesa que hicimos en clase pero la comentamos ya que usamos el fetch
/*const lista_clientes=()=>{                                  //metodo antiguo
    const promesa= new Promise((resolve,reject)=>{
        const http = new XMLHttpRequest();                    //variable con request http y xml
        http.open("GET","http://localhost:3000/perfil");
        http.send();
        http.onload=()=>{
            const response = JSON.parse(http.response);       //convierto que mi respuesta http sea json
            if(http.response>=400){
                reject(response)
            } else{
                resolve(response)
            }
        };
    });
    return promesa;
}*/

const listaclientes=()=>fetch("http://localhost:3000/perfil").then(respuesta=>respuesta.json());

/*lista_clientes()
    .then((data)=>{ //en caso de que todo este bien
        data.forEach((perfil)=>{
            const nuevafila= crear_nueva_fila(perfil.nombre,perfil.email);
            table.appendChild(nuevafila)
        });
    })
    .catch((error)=> alert("No existe conexion")); //en caso de que no funcione*/


    /*
const crearCliente=(nombre, email)=>{
    return fetch("http://localhost:3000/perfil", {
        method : "POST",
        Headers:{
            "Content-type":"application/json"
        },
        body:JSON.stringify({nombre, email, id : uuid.v4()})
    })
}
const eliminaCliente = (id)=>{
    return fetch (`http://localhost:3000/perfil/${id}`, {
        method: "DELETE"
    })
}

const clientes = (id)=>{
    return fetch(`http://localhost:3000/perfil/${id}`).then((respuesta)=>respuesta.json())}

const actualizarCliente = (nombre, email, id) =>{
    return fetch(`http://localhost:3000/perfil/${id}`,
    {
        method:"PUT",
        Headers:{
            "Content-Type":"applicacion/json"
        },
        body:JSON.stringify((nombre,email))
    }).then(respuesta=>console.log(respuesta)).catch((error)=>console(error));
}

export const clientService={
    listaclientes,
crearCliente,
eliminaCliente,
actualizarCliente};

*/

const API_BASE_URL = 'http://localhost/Api/conexion.php'
const lista_clientes=()=>{
    return fetch(API_BASE_URL).then(response=>{
        if(!response.ok)throw new Error ('error clientes');
        return response.json(); 
    })
}
const crearCliente = (nombre, email)=>{
    return fetch(API_BASE_URL, {
        method:'POST',
        headers:{
            'Content-type': 'application/json'
        },
        body:JSON.stringify({
            nombre,email,id:uuid.v4()
        })
        
    }),then(response=>{
        if(!response.ok)throw new Error ('error clientes');
        return response.json(); 
    })
}
const eliminarCliente = (id) => {
    
    return fetch(`${API_BASE_URL}/${id}`, {
        method: 'DELETE'
    }).then(response => {
        if (!response.ok) {
            throw new Error('Error al eliminar el cliente');
        }
        return response.json();
    })
}
const clientes = (id)=>{
    
}
const actualizarCliente = (nombre, email, id) =>{
    return fetch(API_BASE_URL,
    {
        method:"PUT",
        Headers:{
            "Content-Type":"applicacion/json"
        },
        body:JSON.stringify((nombre,email))
    }).then(respuesta=>console.log(respuesta)).catch((error)=>console(error));
}
    
