import Swal from "sweetalert2";
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const modal = document.getElementById('modal');
const openModal = document.getElementById('openModal');
const closeModal = document.getElementById('closeModal');
const modalUsuario = document.getElementById('contenedor_login');
const openModalUsuario = document.getElementById('openModalUsuario');
const closeModalUsuario = document.getElementById('closeModalUsuario');
const modalCrearCliente = document.getElementById('modalCrearCliente');
const closeModalCliente = document.getElementById('closeModalCliente');
const formLoginCompra = document.getElementById('formLoginCompra');
const compraLogin = document.getElementById('compraLogin');
const compraregistrarse = document.querySelector('.compraregistrarse');
const compradivBotones = document.getElementById('compradivBotones');
const divPrincipal = document.getElementById('principal');
const nav = document.getElementById('nav');
const compraloginbotonregistrarse = document.getElementById('compraloginbotonregistrarse');
const compraloginbotonlogin = document.getElementById('compraloginbotonlogin');
const formregistrarclientecompra = document.getElementById('formregistrarclientecompra');
const formregistrarclientecompraform = document.getElementById('formregistrarclientecompraform');
const detalles_cliente = document.getElementById('detalles-cliente');
const cliente_nologin = document.getElementById('cliente-nologin');
const btnregresar = document.getElementById('btnregresar');
const dominio = window.location.pathname;

if (btnregresar) {
    btnregresar.addEventListener('click', () => {
        modalUsuario.classList.add('md:w-1/2');
        panelmicuenta.classList.add('hidden');
        botonesclientepanel.classList.remove('hidden');
    });
}


//Boton login enlace Compra
if (compraloginbotonlogin) {
    compraloginbotonlogin.addEventListener('click', () => {
        formregistrarclientecompra.classList.add('hidden');
        formLoginCompra.classList.remove('hidden');

    });
}
//Boton registrase enlace Compra
if (compraloginbotonregistrarse) {
    compraloginbotonregistrarse.addEventListener('click', () => {
        formLoginCompra.classList.add('hidden');
        formregistrarclientecompra.classList.remove('hidden');

    });
}
//Boton iniciar sesion Compra
if (compraLogin) {
    compraLogin.addEventListener('click', () => {

        formLoginCompra.classList.remove('hidden');
        compradivBotones.classList.remove('flex');
        compradivBotones.classList.add('hidden');

    });
}
//Boton registrarse sesion Compra
if (compraregistrarse) {

    compraregistrarse.addEventListener('click', () => {
        formregistrarclientecompra.classList.remove('hidden');
        compradivBotones.classList.remove('flex');
        compradivBotones.classList.add('hidden');

    });
}
// Abrir modal
if (openModal) {
    openModal.addEventListener('click', () => {
        modal.classList.remove('translate-x-full');

    });
}

// Cerrar modal
if (closeModal) {
    closeModal.addEventListener('click', () => {

        modal.classList.add('translate-x-full');
        divPrincipal.style.opacity = 1;
        nav.style.opacity = 1;
        // Habilitar todos los botones dentro del nav
        const buttons = nav.querySelectorAll('button');
        buttons.forEach(button => {
            button.disabled = false;
        });
    });
}

if (openModalUsuario) {
    // Abrir modal Usuario
    openModalUsuario.addEventListener('click', () => {

        if (modalUsuario.classList.contains('hidden')) {
            // Mostrar el modal
            modalUsuario.classList.remove('hidden');
            modalUsuario.classList.remove('translate-x-full');

            // Cambiar el contenido del botón a "X"
            openModalUsuario.innerHTML = 'X';  // Esto reemplaza el contenido con la "X"
            openModalUsuario.classList.add('bg-red-500'); // Cambia el fondo a rojo
            openModalUsuario.classList.add('px-4'); // Cambia el fondo a rojo
            openModalUsuario.classList.add('text-white'); // Cambia el fondo a rojo
            openModalUsuario.classList.add('font-semibold'); // Cambia el fondo a rojo




        } else {
            // Ocultar el modal
            modalUsuario.classList.add('hidden');
            modalUsuario.classList.add('translate-x-full');

            // Restaurar el contenido del botón a su estado original
            openModalUsuario.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1"
                class="h-[30px] w-[30px]" x="0px" y="0px" viewBox="0 0 720 720" xml:space="preserve">
                <rect fill="#293241" width="720" height="58.5"></rect>
                <rect y="661.5" fill="#293241" width="720" height="58.5"></rect>
                <rect y="330.75" fill="#293241" width="720" height="58.5"></rect>
            </svg>
        `;
            openModalUsuario.classList.remove('bg-red-500');  // Eliminar el fondo rojo
        }

    });
}
if (modalUsuario) {
    modalUsuario.addEventListener('mouseleave', () => {
        // Solo cambiar si el modal está cerrado, de lo contrario, no lo hacemos
        if (modalUsuario.classList.contains('hidden')) {
            openModalUsuario.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1"
                    class="h-[30px] w-[30px]" x="0px" y="0px" viewBox="0 0 720 720" xml:space="preserve">
                    <rect fill="#293241" width="720" height="58.5"></rect>
                    <rect y="661.5" fill="#293241" width="720" height="58.5"></rect>
                    <rect y="330.75" fill="#293241" width="720" height="58.5"></rect>
                </svg>
            `;
            openModalUsuario.classList.remove('bg-red-500');
            openModalUsuario.classList.remove('px-4');
            openModalUsuario.classList.remove('text-white');
            openModalUsuario.classList.remove('font-semibold');
        }
    });
}
// Cerrar modal Usuario
if (closeModalUsuario) {
    closeModalUsuario.addEventListener('click', () => {
        modalUsuario.classList.add('hidden');
        modalUsuario.classList.add('translate-x-full');

    });
}


// Cerrar modal Pedido
if (closeModalCliente) {
    closeModalCliente.addEventListener('click', () => {
        modalCrearCliente.classList.remove("flex");
        modalCrearCliente.classList.add("hidden");

    });
}


//REGISTRAR CLIENTE A TRAVES DEL FORMULARIO DE COMPRA

if (formregistrarclientecompraform) {
    formregistrarclientecompraform.addEventListener('submit', (event) => {
        const submitButton = formregistrarclientecompra.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
        }
        event.preventDefault(); // Evita el comportamiento predeterminado del formulario

        // Crear un objeto FormData para recopilar los datos
        const formData = new FormData(formregistrarclientecompraform);

        // Convertir FormData en un objeto JSON
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        data['dominio'] = dominio.replace("/", "");

        // Obtener la URL de acción del formulario
        const actionURL = formregistrarclientecompraform.getAttribute('action');

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
                if (submitButton) {
                    submitButton.disabled = false;
                }
                formLoginCompra.classList.remove('hidden');
                formregistrarclientecompra.remove('flex');
                formregistrarclientecompra.classList.add('hidden');
            })
            .catch(error => {
                mensajeError(error.message); // Mostrar mensaje de error
                if (submitButton) {
                    submitButton.disabled = false;
                }
            });
    });
}



const dt_telefono = document.getElementById('dt-telefono');
const dt_nombre = document.getElementById('dt-nombre');
const dt_apellido = document.getElementById('dt-apellido');
const dt_direccion = document.getElementById('dt-direccion');
const dt_nota = document.getElementById('dt-nota');
const cliente_panel = document.getElementById('cliente-panel');
const botonesclientepanel = document.getElementById('botonesclientepanel');
const botonesloginclientepanel = document.getElementById('botonesloginclientepanel');
function mostrarDetallesCliente(usuario) {
    cliente_nologin.classList.add('hidden');
    formLoginCompra.classList.add('hidden');
    detalles_cliente.classList.remove('hidden');
    modificarValordetallecompra(usuario);
}
function modificarValordetallecompra(usuario) {
    var nombre = "";
    usuario.forEach(element => {
        dt_telefono.textContent = element.telefono;
        dt_nombre.textContent = element.nombres;
        dt_apellido.textContent = element.apellidos;
        dt_direccion.textContent = element.direccion;
        dt_nota.value = element.nota || "";
        nombre = element.nombres + " " + element.apellidos;
    });
    dt_nota.focus();
    modificarvaloresusuariopanel(nombre)
}
function modificarvaloresusuariopanel(nombre) {
    cliente_panel.textContent = nombre || "No ha Iniciado Sesión.";
    botonesclientepanel.classList.remove('hidden');
    botonesclientepanel.classList.add('flex');
    botonesloginclientepanel.classList.remove('flex');
    botonesloginclientepanel.classList.add('hidden');
}

const btnLogin = document.getElementById('btnLogin');

const btnRegister = document.getElementById('btnRegister');
const compraloginbotonloginclientepanel = document.getElementById('compraloginbotonloginclientepanel');
const btn_micuentapanelcliente = document.getElementById('btn-micuentapanelcliente');
const divpedidosclientepanel = document.getElementById('divpedidosclientepanel');
const btndatosclientepanel = document.getElementById('btndatosclientepanel');
const panelmicuenta = document.getElementById('panelmicuenta');
const btnpedidosclientepanel = document.getElementById('btnpedidosclientepanel');
const divmisdatosclientepanel = document.querySelector('.detallescliente');



//REGISTRAR CLIENTE A TRAVES DEL FORMULARIO DE CLIENTE

if (btn_micuentapanelcliente) {
    btn_micuentapanelcliente.addEventListener('click', () => {
        modalUsuario.classList.remove('md:w-1/2');
        divpedidosclientepanel.classList.remove('hidden');
        panelmicuenta.classList.remove('hidden');
        btndatosclientepanel.classList.remove('bg-blue-600');
        btndatosclientepanel.classList.remove('text-white');
        btndatosclientepanel.classList.remove('border-blue-600');
        botonesclientepanel.classList.add('hidden');

    });
}

if (btndatosclientepanel) {
    btndatosclientepanel.addEventListener('click', () => {
        btndatosclientepanel.classList.add('bg-blue-600');
        btndatosclientepanel.classList.add('text-white');
        btndatosclientepanel.classList.add('border-blue-600');
        btnpedidosclientepanel.classList.remove('bg-blue-600');
        btnpedidosclientepanel.classList.remove('text-white');
        btnpedidosclientepanel.classList.remove('border-blue-600');
        divpedidosclientepanel.classList.add('hidden');
        divmisdatosclientepanel.classList.remove('hidden');
    });
}
if (btnpedidosclientepanel) {
    btnpedidosclientepanel.addEventListener('click', () => {
        btnpedidosclientepanel.classList.add('bg-blue-600');
        btnpedidosclientepanel.classList.add('text-white');
        btnpedidosclientepanel.classList.add('border-blue-600');
        btndatosclientepanel.classList.remove('bg-blue-600');
        btndatosclientepanel.classList.remove('text-white');
        btndatosclientepanel.classList.remove('border-blue-600');
        divpedidosclientepanel.classList.remove('hidden');
        divmisdatosclientepanel.classList.add('hidden');
    });
}


if (compraloginbotonloginclientepanel) {
    compraloginbotonloginclientepanel.addEventListener('click', () => {
        formLogindiv.classList.remove('hidden');
        formregistrarclientepanel.classList.add('hidden');

    });
}

if (btnLogin) {
    btnLogin.addEventListener('click', () => {
        formLogindiv.classList.remove('hidden');
        botonesloginclientepanel.classList.remove('flex')
        botonesloginclientepanel.classList.add('hidden');
    });
}
if (btnRegister) {
    btnRegister.addEventListener('click', () => {
        botonesloginclientepanel.classList.remove('flex')
        botonesloginclientepanel.classList.add('hidden');
        formregistrarclientepanel.classList.remove('hidden');
    });
}




//Mensaje de Error
function mensajeError(texto) {
    Swal.fire({
        title: 'Ocurrio un Error!',
        text: texto,
        icon: 'error',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000,
        customClass: {
            timerProgressBar: 'bg-red-500 h-2 rounded-md'
        }
    })
}
//Mensaje de Exito
function mensajeExito(texto) {
    Swal.fire({
        title: 'Confirmación!',
        text: texto,
        icon: 'success',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 2000,
        customClass: {
            timerProgressBar: 'bg-green-500 h-2 rounded-md'
        }
    })
}

//DISEÑO


