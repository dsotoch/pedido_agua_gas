import Swal from "sweetalert2";
mostrarNotificacion("PROBANDO EN ESCRITORIO","dadasdad","asdasda");
async function verificar_permisos() {
    if (!("Notification" in window)) {
        Swal.fire({
            title: 'Error',
            text: 'Este navegador no soporta notificaciones, es posible que el sistema no funcione correctamente.',
            timerProgressBar: true,
            showConfirmButton: false,
            icon: 'error',
            timer: 3000,
            customClass: {
                timerProgressBar: 'bg-red-500'
            }
        });
        return false;
    }

    if (Notification.permission === "granted") {
        return true;
    }

    if (Notification.permission === "denied") {
        return false; // No solicitar de nuevo, ya fue rechazado.
    }

    const permiso = await Notification.requestPermission();
    return permiso === "granted";
}

export async function mostrarNotificacion(titulo, texto, tag) {
    const permiso = await verificar_permisos();
    if (permiso && 'serviceWorker' in navigator) {
        // Verifica si el Service Worker está listo
        navigator.serviceWorker.ready.then(reg => {
            reg.showNotification(titulo, {
                body: texto,
                icon: "/imagenes/noti.png",
                tag: tag
            });
        }).catch(error => {
            console.error('Error al mostrar notificación:', error);
            Swal.fire({
                title: 'Error',
                text: 'No se pudo mostrar la notificación.',
                timerProgressBar: true,
                showConfirmButton: false,
                icon: 'error',
                timer: 3000,
                customClass: {
                    timerProgressBar: 'bg-red-500 h-2 rounded-md'
                }
            });
        });
    } else {
        Swal.fire({
            title: 'Error',
            text: 'Las notificaciones están desactivadas. Activa los permisos en la configuración.',
            timerProgressBar: true,
            showConfirmButton: false,
            icon: 'error',
            timer: 3000,
            customClass: {
                timerProgressBar: 'bg-red-500 h-2 rounded-md'
            }
        });
    }
}






