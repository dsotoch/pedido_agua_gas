import Swal from "sweetalert2";

const id_usuario_autenticado = document.getElementById('id_usuario_autenticado');

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

export function guardarPaginaPredeterminada(url, empresa, procedencia) {
    const esPrincipal = procedencia === 'principal' ? esPaginaPredeterminada_Principal() : esPaginaPredeterminada();

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

    return true;
}

export function esPaginaPredeterminada_Principal(dominio) {
    let clienteID = obtenerClienteID();
    let paginaPredeterminada = localStorage.getItem(`${clienteID}_paginaPredeterminada`) || obtenerCookie(clienteID);
    return paginaPredeterminada === window.location.href + dominio;
}

export function esPaginaPredeterminada() {
    let clienteID = obtenerClienteID();
    let paginaPredeterminada = localStorage.getItem(`${clienteID}_paginaPredeterminada`) || obtenerCookie(clienteID);
    return paginaPredeterminada === window.location.href;
}

export function esFavorito() {
    let clienteID = obtenerClienteID();
    let favoritos = JSON.parse(localStorage.getItem(`${clienteID}_favoritos`)) || [];

    return favoritos.some(fav => fav.url === window.location.href);
}

export function agregarFavorito(url, nombre) {
    let clienteID = obtenerClienteID();
    let favoritos = JSON.parse(localStorage.getItem(`${clienteID}_favoritos`)) || [];

    if (favoritos.some(fav => fav.url === url)) {
        eliminarFavorito(url);
        return false;
    }

    favoritos.push({ url, nombre });

    if (favoritos.length > 3) {
        favoritos.shift();
    }

    localStorage.setItem(`${clienteID}_favoritos`, JSON.stringify(favoritos));

    Swal.fire({
        title: 'Confirmación',
        text: `${nombre} ha sido añadido a tus favoritos.`,
        icon: 'success',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            timerProgressBar: 'bg-green-500 h2 rounded'
        }
    });

    return true;
}

function eliminarFavorito(url) {
    let clienteID = obtenerClienteID();
    let favoritos = JSON.parse(localStorage.getItem(`${clienteID}_favoritos`)) || [];

    let nuevosFavoritos = favoritos.filter(fav => fav.url !== url);
    localStorage.setItem(`${clienteID}_favoritos`, JSON.stringify(nuevosFavoritos));

    Swal.fire({
        title: 'Eliminado',
        text: 'La página ha sido eliminada de tus favoritos.',
        icon: 'success',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            timerProgressBar: 'bg-red-500 h2 rounded'
        }
    });
}

export function ir_pagina_predeterminada() {
    let clienteID = obtenerClienteID();
    if (!clienteID) return;

    let paginaPredeterminada = localStorage.getItem(`${clienteID}_paginaPredeterminada`) || obtenerCookie(clienteID);

    if (paginaPredeterminada && window.location.pathname === "/") {
        window.location.href = decodeURIComponent(paginaPredeterminada);
    }
}

function borrarPaginaPredeterminada() {
    let clienteID = obtenerClienteID();
    localStorage.removeItem(`${clienteID}_paginaPredeterminada`);
    document.cookie = `${clienteID}=; path=/; max-age=0;`;

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
