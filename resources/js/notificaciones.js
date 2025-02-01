import Swal from "sweetalert2";

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

    if (permiso) {
        const notification = new Notification(titulo, {
            body: texto,
            icon: "/imagenes/noti.png",
            tag: tag
        });

        // Cierra la notificación automáticamente después de 3 segundos
        setTimeout(() => notification.close(), 5000);

        // Cierra la notificación si el usuario hace clic en ella
        notification.onclick = ()  =>{
            window.focus();
            notification.close();
        }
    } else {
        Swal.fire({
            title: 'Error',
            text: 'Las notificaciones están desactivadas. Puede que tengas nuevos pedidos pendientes.',
            timerProgressBar: true,
            showConfirmButton: false,
            icon: 'error',
            timer: 3000,
            customClass: {
                timerProgressBar: 'bg-red-500'
            }
        });
    }
}



