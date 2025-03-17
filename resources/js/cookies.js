import Swal from "sweetalert2";

const id_usuario_autenticado = document.getElementById('id_usuario_autenticado');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const btn_predeterminado = document.getElementById('btn_predeterminado');
let favoritos = [];


//favoritos Bd

export async function esFavoritoPrincipal(dominio) {
    if (window.location.pathname != '/') {
        await obtenerFavoritos();
    }
    const urlActual = dominio;
    // Aplanar el array y buscar el dominio
    const favoritosLista = favoritos.flat(); // Convierte [[...], [...]] en [...]
    if (Array.isArray(favoritosLista) && favoritosLista.length > 0 && favoritosLista.every(fav => fav !== null && fav !== undefined)) {


        return favoritosLista.some(fav => fav.dominio === urlActual);
    }

    return false;


}
obtenerFavoritos();
export async function esFavorito() {
    if (window.location.pathname != '/') {
        await obtenerFavoritos();
    }
    const urlActual = window.location.pathname.slice(1);
    // Aplanar el array y buscar el dominio
    const favoritosLista = favoritos.flat(); // Convierte [[...], [...]] en [...]
    if (Array.isArray(favoritosLista) && favoritosLista.length > 0 && favoritosLista.every(fav => fav !== null && fav !== undefined)) {

        return favoritosLista.some(fav => fav.dominio === urlActual);
    }

    return false;


}
export async function obtenerFavoritos() {
    favoritos.length = 0;
    if (id_usuario_autenticado.textContent != '') {
        try {
            const response = await fetch(`/getFavoritos`);
            const data = await response.json();

            if (response.ok) {
                favoritos.push(data.mensaje);
                return data.mensaje; // Retorna la lista de favoritos
            } else {
                throw new Error(data.mensaje || "Error al obtener favoritos");
            }
        } catch (error) {
            console.error("Error:", error);
            return [];
        }
    }
}


export async function guardarFavorito(usuarioId, dominio) {
    if (id_usuario_autenticado.textContent.trim() != '') {
        try {
            const response = await fetch('guardarFavorito', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X_CSRF_TOKEN': token },
                body: JSON.stringify({ usuario_id: usuarioId, empresa_id: dominio })
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire({
                    title: 'Distribuidora Favorita',
                    text: "La distribuidora  se registro dentro de tus favoritas.",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    customClass: {
                        timerProgressBar: 'bg-green-500 h2 rounded',
                    }
                });
            } else {
                throw new Error(data.mensaje || "Error al guardar favorito");
            }
        } catch (error) {
            Swal.fire({
                title: 'Distribuidora Favorita',
                text: "Ocurrio un error procesando la solicitud.",
                icon: 'error',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    timerProgressBar: 'bg-red-500 h2 rounded',
                }
            });
            console.error("Error:", error);
        }
    } else {
        Swal.fire({
            title: 'Requerimiento faltante!',
            text: "Inicia sesion para realizar esta operacion.",
            icon: 'warning',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                timerProgressBar: 'bg-red-500 h2 rounded',
            }
        });
    }
}


export async function eliminarFavorito(empresaId) {
    try {
        const response = await fetch('/eliminarFavorito', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X_CSRF_TOKEN': token },
            body: JSON.stringify({ usuario_id: id_usuario_autenticado.textContent.trim(), empresa_id: empresaId })
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                title: 'Eliminado',
                text: "La distribuidora  se eliminó de favoritos.",
                icon: 'success',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    timerProgressBar: 'bg-red-500 h2 rounded',
                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: "Hubo un error al procesar la solicitud de eliminacion.",
                icon: 'error',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    timerProgressBar: 'bg-red-500 h2 rounded',
                }
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Error',
            text: "Hubo un error al procesar la solicitud de eliminacion.",
            icon: 'error',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                timerProgressBar: 'bg-red-500 h2 rounded',
            }
        });
        console.error("Error:", error);

    }
}



//fin favoritos BD
async function obtenerDatosPredeterminada(url) {
    if (id_usuario_autenticado.textContent != '') {
        try {
            const response = await fetch(url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json"
                }
            });

            if (!response.ok) {
                throw new Error(`Error: ${response.status}`);
            }
            const data = await response.json();
            if (data && data.mensaje && data.mensaje.trim() !== null) {
                guardarPaginaPredeterminadaDesdeBD(window.location.origin + "/" + data.mensaje);
            } else {
            }
        } catch (error) {
        }
    }
}
obtenerDatosPredeterminada("/getpredeterminada");
async function eliminarPredeterminadaBD() {
    try {
        const response = await fetch('eliminarpredeterminada', {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                'X_CSRF_TOKEN': token
            }
        });

        if (!response.ok) {
            throw new Error(`Error: ${response.status}`);
        }

        const data = await response.json();
    } catch (error) {
        console.error("Error al eliminar:", error);
    }
}


function guardarPaginaPredeterminadaDesdeBD(url) {
    let clienteID = obtenerClienteID();
    if (!clienteID) return '';

    localStorage.setItem(`${clienteID}_paginaPredeterminada`, url);
    document.cookie = `${clienteID}=${encodeURIComponent(url)}; path=/; max-age=${60 * 60 * 24 * 30};`;
    if (window.location.href === url) {
        btn_predeterminado.classList.add('text-green-500');
    }
    if (window.location.pathname === '/') {
        window.location.href = url;
    }
}
// Función para obtener el ID del cliente desde cookies o localStorage
function obtenerClienteID() {
    if (sessionStorage.getItem("ignorar_cliente_id") === "true") {
        return null;
    }

    let clienteID = localStorage.getItem("cliente_id") || obtenerCookie("cliente_id");

    if (!clienteID) {
        clienteID = "cliente_" + Math.random().toString(36).substr(2, 9);
        localStorage.setItem("cliente_id", clienteID);
        document.cookie = `cliente_id=${clienteID}; path=/; max-age=${60 * 60 * 24 * 365};`;
    }

    return clienteID;
}

// Función auxiliar para obtener cookies
function obtenerCookie(nombre) {
    let cookies = document.cookie.split("; ");
    for (let cookie of cookies) {
        let [key, value] = cookie.split("=");
        if (key.trim() === nombre) {
            return value;
        }
    }
    return null;
}

// Verificar si el usuario autenticado está definido
if (!id_usuario_autenticado || !id_usuario_autenticado.textContent) {
    habilitarClienteID();
}

export function deshabilitarClienteID() {
    sessionStorage.setItem("ignorar_cliente_id", "true");
}

export function habilitarClienteID() {
    sessionStorage.removeItem("ignorar_cliente_id");
}

export async function guardarPaginaPredeterminada(url, empresa, procedencia) {
    if (id_usuario_autenticado.textContent == '') {
        Swal.fire({
            title: 'Requerimiento faltante',
            text: `Inicia Sesion para realizar esta operacion.`,
            icon: 'warning',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                timerProgressBar: 'bg-red-500 h2 rounded'
            }
        });
        return false;
    }
    const esPrincipal = procedencia === 'principal' ? esPaginaPredeterminada_Principal(empresa.trim()) : esPaginaPredeterminada();

    if (esPrincipal) {
        borrarPaginaPredeterminada();
        return false;
    }

    let clienteID = obtenerClienteID();
    if (!clienteID) return false;

    localStorage.setItem(`${clienteID}_paginaPredeterminada`, url);
    document.cookie = `${clienteID}=${encodeURIComponent(url)}; path=/; max-age=${60 * 60 * 24 * 30};`;
    Swal.fire({
        title: 'Confirmación',
        text: `Tu Distribuidora predeterminada ahora es ${empresa}`,
        icon: 'success',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            timerProgressBar: 'bg-green-500 h2 rounded',
        }
    });
    await guardarPaginaPredeterminadaBD(url);
    return true;
}
async function guardarPaginaPredeterminadaBD(url) {
    try {
        const user = id_usuario_autenticado.textContent.trim();
        const response = await fetch('/predeterminada', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                usuario_id: user,
                predeterminado: url,
            })
        });

        if (!response.ok) {
            throw new Error(`Error: ${response.status}`);
        }
    } catch (error) {
        console.error('Error al guardar la página predeterminada:', error);
    }
}

export function paginaPredeterminada() {
    let clienteID = obtenerClienteID();
    let paginaPredeterminada = localStorage.getItem(`${clienteID}_paginaPredeterminada`) || obtenerCookie(clienteID);
    return paginaPredeterminada;
}
export function esPaginaPredeterminada_Principal(dominio) {
    let clienteID = obtenerClienteID();
    let paginaPredeterminada = localStorage.getItem(`${clienteID}_paginaPredeterminada`) || obtenerCookie(clienteID);
    return paginaPredeterminada === window.location.origin + '/' + dominio;
}

export function esPaginaPredeterminada() {
    let clienteID = obtenerClienteID();
    let paginaPredeterminada = localStorage.getItem(`${clienteID}_paginaPredeterminada`) || obtenerCookie(clienteID);

    return paginaPredeterminada === window.location.href;
}






export function ir_pagina_predeterminada() {
    let clienteID = obtenerClienteID();
    if (!clienteID) return;

    let paginaPredeterminada = localStorage.getItem(`${clienteID}_paginaPredeterminada`) || obtenerCookie(clienteID);
    if (id_usuario_autenticado.textContent == '') {
        let clienteID = obtenerClienteID();
        localStorage.removeItem(`${clienteID}_paginaPredeterminada`);
        document.cookie = `${clienteID}=; path=/; max-age=0;`;
        return;
    }
    if (paginaPredeterminada && window.location.pathname === "/") {
        window.location.href = decodeURIComponent(paginaPredeterminada);
    }
}

function borrarPaginaPredeterminada() {
    let clienteID = obtenerClienteID();
    localStorage.removeItem(`${clienteID}_paginaPredeterminada`);
    document.cookie = `${clienteID}=; path=/; max-age=0;`;
    eliminarPredeterminadaBD();
    Swal.fire({
        title: 'Eliminado',
        text: "La distribuidora predeterminada se eliminó correctamente.",
        icon: 'success',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            timerProgressBar: 'bg-red-500 h2 rounded',
        }
    });
}

// Ejecutar la función al cargar la página
ir_pagina_predeterminada();
