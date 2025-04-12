import { initializeApp } from "firebase/app";
import { getMessaging, onMessage } from "firebase/messaging";
import { agregarPedido } from "./nueva_tarjeta";
import { actualizar_Estado_delivery_panel_cliente, actualizarEstadoYPagoPanelAdministrador } from "./pedidos";

// 📌 Configuración de Firebase
const firebaseConfig = {
    apiKey: "AIzaSyC8_mVc3OAr7oqeJk00LCh59cQNnnQEUIY",
    authDomain: "entrega-3dfd0.firebaseapp.com",
    projectId: "entrega-3dfd0",
    storageBucket: "entrega-3dfd0.firebasestorage.app",
    messagingSenderId: "34637998721",
    appId: "1:34637998721:web:2059921cb527fff5493b80"
};

// 📌 Inicializar Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

onMessage(messaging, (payload) => {
    if (document.visibilityState === "visible") {
        // 📌 Si la app está en primer plano en escritorio, mostramos la notificación
        if (window.matchMedia("(min-width: 768px)").matches) { 
            if(procesarNotificacion(payload)){
                new Notification(payload.notification.title, {
                    body: payload.notification.body,
                    requireInteraction:true,
                    icon: "/imagenes/Ola-64x64-Orange.png",
                    badge: "/imagenes/Ola-64x64-Orange.png",
                    data: { url: payload.data?.url ?? "/" }
                });
            }
           
        } else {
            // En móvil solo procesamos la notificación sin mostrarla
            procesarNotificacion(payload);
        }
    } else {
        // 📌 Si la app está en segundo plano, usamos el Service Worker para mostrarla
        navigator.serviceWorker.getRegistration().then(registration => {
            if (registration) {
                registration.showNotification(payload.notification.title, {
                    body: payload.notification.body,
                    requireInteraction:true,
                    icon: "/imagenes/Ola-64x64-Orange.png",
                    badge: "/imagenes/Ola-64x64-Orange.png",
                    data: { url: payload.data?.url ?? "/" }
                });
            }
        }).catch(error => {
            console.error("Error al mostrar notificación:", error);
        });
    }
});




// 📌 Función para procesar las notificaciones
export function procesarNotificacion(payload) {
    if (!payload.data?.operacion) {
        console.error("⚠️ Payload sin operación válida:", payload);
        return false;
    }

    switch (payload.data.operacion) {
        case 'pedido_tomado':
            actualizar_Estado_delivery_panel_cliente(payload.data.pedido_id, payload.data.estado);
            return true;
        case 'aceptacion':
            actualizar_Estado_delivery_panel_cliente(payload.data.pedido_id, payload.data.estado);
            return false;

        case 'finalizado':
            actualizarEstadoYPagoPanelAdministrador(payload.data.pedido_id, payload.data.estado);
            return true;
        case 'anulacion':
            actualizarEstadoYPagoPanelAdministrador(payload.data.pedido_id, payload.data.estado);
            return false;

        case 'asignacion':
            agregarPedido(payload.data.pedido, "repartidor", payload.data.tiempo);
            return true;

        default:
            agregarPedido(payload.data.pedido, "admin", payload.data.tiempo);
            return true;
    }
}

