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
        // La app está en primer plano, podemos manejarlo sin notificación
        procesarNotificacion(payload);
    } else {
        // La app está en segundo plano, mostrar notificación con SW
        navigator.serviceWorker.getRegistration().then(registration => {
            if (registration) {
                registration.showNotification(payload.notification.title, {
                    body: payload.notification.body,
                    icon: "/imagenes/Ola-64x64-Orange.png",
                    badge: "/imagenes/Ola-64x64-Orange.png",
                    data: { url: payload.data?.url ?? "/" }
                });
            }
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
        case 'anulacion':
            actualizarEstadoYPagoPanelAdministrador(payload.data.pedido_id, payload.data.estado);
            return true;

        case 'asignacion':
            agregarPedido(payload.data.pedido, "repartidor", payload.data.tiempo);
            return true;

        default:
            agregarPedido(payload.data.pedido, "admin", payload.data.tiempo);
            return true;
    }
}

