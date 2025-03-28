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

// 📌 Escuchar mensajes cuando la app está en PRIMER PLANO
onMessage(messaging, (payload) => {

    const notificationOptions = {
        body: payload.notification.body,
        icon: "/icon.png",
        badge: "/badge.png",
        vibrate: [200, 100, 200],
        data: { url: payload.data?.url ?? "/" }, // Evita errores si URL no está definida
        tag: "pedido-123",
        renotify: true,
        silent: false,
        timestamp: Date.now(),
    };

    if (Notification.permission === "granted") {
        new Notification(payload.notification.title, notificationOptions);
    } else {
        console.warn("⚠️ Permiso de notificación no concedido.");
    }

    procesarNotificacion(payload);
});

// 📌 Función para procesar las notificaciones
export function procesarNotificacion(payload) {

    if (!payload.data?.operacion) {
        console.error("⚠️ Payload sin operación válida:", payload);
        return;
    }

    switch (payload.data.operacion) {
        case 'pedido_tomado':
            actualizar_Estado_delivery_panel_cliente(payload.data.pedido_id, payload.data.estado);
            break;
        case 'finalizado':
            actualizarEstadoYPagoPanelAdministrador(payload.data.pedido_id, payload.data.estado);
            break;
        case 'asignacion':
            agregarPedido(payload.data.pedido, "repartidor", payload.data.tiempo);
            break;
        case 'anulacion':
            actualizarEstadoYPagoPanelAdministrador(payload.data.pedido_id, payload.data.estado);
            break;
        default:
            agregarPedido(payload.data.pedido, "admin", payload.data.tiempo);
            break;
    }
}
