
const botonregistrarsepanelcliente = document.getElementById('botonregistrarsepanelcliente');
const formLogindiv = document.getElementById('formLogindiv');
const contenedor_login = document.getElementById('contenedor_login');
const boton_regresar_a_login = document.getElementById('boton_regresar_a_login');
const btn_acceder = document.getElementById('btn_acceder');
const formulario_login_pagina_principal = document.getElementById('formulario_login_pagina_principal');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const contenedor_registrarse = document.getElementById('contenedor_registrarse');
const form_registrar_usuario = document.getElementById('form_registrar_usuario');
const contenedor_login_distribuidora =document.getElementById('contenedor_login_distribuidora');
const form_login_distribuidora=document.getElementById('form_login_distribuidora');


//Iniciar sesion Distribuidora
if (form_login_distribuidora) {
    form_login_distribuidora.addEventListener('submit', async (e) => {
        e.preventDefault();
        contenedor_login_distribuidora.classList.add('after:content-[""]', 'after:absolute', 'after:inset-0', 'after:bg-white', 'after:bg-opacity-70', 'after:cursor-not-allowed', 'after:z-10');
        let respuesta = await SolicitudFecthFormularios_POST(form_login_distribuidora, 'login');
        if (respuesta) {
            window.location.reload();
        }else{
            contenedor_login_distribuidora.classList.remove('after:content-[""]', 'after:absolute', 'after:inset-0', 'after:bg-white', 'after:bg-opacity-70', 'after:cursor-not-allowed', 'after:z-10');

        }
    });
}

if (form_registrar_usuario) {
    form_registrar_usuario.addEventListener('submit', (event) => {
        const submitButton = form_registrar_usuario.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
        }
        event.preventDefault(); // Evita el comportamiento predeterminado del formulario

        // Crear un objeto FormData para recopilar los datos
        const formData = new FormData(form_registrar_usuario);

        // Convertir FormData en un objeto JSON
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        // Obtener la URL de acción del formulario
        const actionURL = form_registrar_usuario.getAttribute('action');

        // Realizar la petición al servidor
        fetch(actionURL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', // Define el contenido como JSON
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(data), // Convierte los datos en una cadena JSON
        })
            .then(response => {
                // Verificar el código de estado
                if (response.status !== 201) {
                    // Obtener el mensaje de error del servidor
                    return response.text().then((text) => {
                        throw new Error(text); // Lanza un error con el mensaje del servidor
                    });
                }
                return response.json(); // Convertir la respuesta exitosa a JSON
            })
            .then(result => {
                mensajeExito(result.mensaje); // Manejo del resultado exitoso
                regresar_a_login();
                if (submitButton) {
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                mensajeError(error.message); // Mostrar mensaje de error
                if (submitButton) {
                    submitButton.disabled = false;
                }
            });
    });
}

function regresar_a_login() {
    formLogindiv.classList.remove('hidden');
    contenedor_registrarse.classList.add('hidden');
}

function regresar_a_registrarse() {
    formLogindiv.classList.add('hidden');
    contenedor_registrarse.classList.remove('hidden');
}

//Mostrar panel de registrarse
if (botonregistrarsepanelcliente) {
    botonregistrarsepanelcliente.addEventListener('click', () => {
        regresar_a_registrarse();

    });
}
//Mostrar panel de Login

if (boton_regresar_a_login) {
    boton_regresar_a_login.addEventListener('click', () => {
        regresar_a_login();

    });
}

//Iniciar sesion Principal
if (formulario_login_pagina_principal) {
    formulario_login_pagina_principal.addEventListener('submit', async (e) => {
        e.preventDefault();
        contenedor_login.classList.add('after:content-[""]', 'after:absolute', 'after:inset-0', 'after:bg-white', 'after:bg-opacity-70', 'after:cursor-not-allowed', 'after:z-10');

        let respuesta = await SolicitudFecthFormularios_POST(formulario_login_pagina_principal, 'login');
        if (respuesta) {
            window.location.href = '/mi-cuenta'
        }else{
            contenedor_login.classList.remove('after:content-[""]', 'after:absolute', 'after:inset-0', 'after:bg-white', 'after:bg-opacity-70', 'after:cursor-not-allowed', 'after:z-10');

        }
    });
}


async function SolicitudFecthFormularios_POST(formulario, tipo) {
    try {
        const formData = new FormData(formulario);
        // Convertir FormData en un objeto JSON
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        // Obtener la URL de acción del formulario
        const actionURL = formulario.getAttribute('action');

        // Realizar la petición al servidor
        const response = await fetch(actionURL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', // Define el contenido como JSON
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(data), // Convierte los datos en una cadena JSON
        });

        // Verificar el código de estado
        if (!response.ok) {
            const errorMessage = await response.text(); // Obtener el mensaje de error del servidor
            throw new Error(errorMessage); // Lanza un error con el mensaje del servidor
        }

        const result = await response.json(); // Convertir la respuesta exitosa a JSON
        if (tipo != 'login') {
            mensajeExito(result.mensaje); // Manejo del resultado exitoso
        }
        return true;
    } catch (error) {
        mensajeError(error.message); // Mostrar mensaje de error
        return false;
    }
}
//Mensaje de Error
function mensajeError(texto) {
    Swal.fire({
        title: 'Ocurrio un Error!',
        text: texto,
        icon: 'error',
        confirmButtonText: 'Aceptar'
    })
}
//Mensaje de Exito
function mensajeExito(texto) {
    Swal.fire({
        title: 'Confirmación!',
        text: texto,
        icon: 'success',
        confirmButtonText: 'Aceptar'
    })
}