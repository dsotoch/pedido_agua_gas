// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getMessaging, getToken } from "firebase/messaging"; // Asegúrate de importar esto
const idUsuarioAutenticado=document.getElementById('id_usuario_autenticado');

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyC8_mVc3OAr7oqeJk00LCh59cQNnnQEUIY",
    authDomain: "entrega-3dfd0.firebaseapp.com",
    projectId: "entrega-3dfd0",
    storageBucket: "entrega-3dfd0.firebasestorage.app",
    messagingSenderId: "34637998721",
    appId: "1:34637998721:web:2059921cb527fff5493b80"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Obtener el token del dispositivo
export async function requestPermission() {
    try {
        const permission = await Notification.requestPermission();
        if (permission === "granted") {
            const token = await getToken(messaging, { vapidKey: "BLw7VTv8HrcPG4erLsf72eRAWFfj5HZ3ep3vxOZ0TcMgORsy5g8y7BQ2DKEBL6S9SBAbTgFj5YzvA7ezjWwGRTs" });
            if(idUsuarioAutenticado.textContent.trim()!=''){
                sendTokenToServer(token);
            }
        } else {
            console.log("Permiso de notificación denegado");
        }
    } catch (error) {
        console.error("Error obteniendo el token:", error);
    }
}


async function sendTokenToServer(token) {
    const tokenS = document.querySelector('meta[name="token"]').getAttribute('content');
    try {
        const response = await fetch("/guardar-token", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": tokenS, // Se usa la clave correcta
                "Accept": "application/json" // Asegura que Laravel devuelva JSON
            },
            body: JSON.stringify({
                device_token: token,
                user_id: idUsuarioAutenticado.textContent.trim() // Puedes cambiar esto por el ID del usuario autenticado
            }),
        });
          const datos = await response.json();
          console.log(datos);

    } catch (error) {
        console.error("Error enviando el token:", error);
    }
}



document.addEventListener("DOMContentLoaded", () => {
    requestPermission();
});
