
const divclientesadmin = document.getElementById('divclientesadmin');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const paneladministrador = document.getElementById('paneladministrador');
const btnregresaradmin = document.getElementById('btnregresaradmin');
const botondashboardclientepanel = document.getElementById('botondashboardclientepanel');
const btnproductosadmin = document.getElementById('btnproductosadmin');
const btnreportesadmin = document.getElementById('btnreportesadmin');
const btnclientesadmin = document.getElementById('btnclientesadmin');
const btnpedidosadmin = document.getElementById('btnpedidosadmin');
const divpedidosadmin = document.getElementById('divpedidosadmin');
var botonseleccionado = btnpedidosadmin;
const divproductosadmin = document.getElementById('divproductosadmin');
const cantidadclientesadmin = document.getElementById('cantidadclientesadmin');
const divclientesadminfull = document.getElementById('divclientesadminfull');
const btnusuariosadmin = document.getElementById('btnusuariosadmin');
const modalnuevousuario = document.getElementById('modalnuevousuario');
const btnnuevousuario = document.getElementById('btnnuevousuario');
const closemodalusuario = document.getElementById('closemodalusuario');
const nuevousuarioadmin = document.getElementById('nuevousuarioadmin');
const divusuariosadmin = document.getElementById('divusuariosadmin');
const modalasignarrepartidor = document.getElementById('modalasignarrepartidor');
const dominio = window.location.pathname;
const divpedidos = document.querySelectorAll('.pedidosadministrador');
const btncerrarmodalrepartidor = document.getElementById('btncerrarmodalrepartidor');
const formAsignarRepartidor = document.getElementById('formAsignarRepartidor');
const pedido_id = document.getElementById('pedido_id');
const divreportesadmin = document.getElementById('divreportesadmin');

let spanrepartidor;
if (formAsignarRepartidor) {
    formAsignarRepartidor.addEventListener('submit', (e) => {
        e.preventDefault();
        const dataform = new FormData(formAsignarRepartidor);
        let data = {};
        dataform.forEach((value, key) => {
            data[key] = value;
        });
        fetch(formAsignarRepartidor.action, {
            method: formAsignarRepartidor.method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(data),
        })
            .then(response => {
                if (response.status !== 201) {
                    return response.text().then((text) => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then(result => {
                mensajeExito(result.mensaje);
                formAsignarRepartidor.reset();
                spanrepartidor.innerHTML = result.repartidor;

            })
            .catch(error => {
                mensajeError(error.message);
            });
    });
}
if (btncerrarmodalrepartidor) {
    btncerrarmodalrepartidor.addEventListener('click', () => {
        modalasignarrepartidor.classList.remove('flex');
        modalasignarrepartidor.classList.add('hidden');
        paneladministrador.classList.remove('bg-black', 'bg-opacity-30');
        divpedidos.forEach(element => {
            element.classList.remove('bg-black', 'bg-opacity-30');
            element.querySelectorAll('button').forEach(element => {
                element.disabled = false;
            });
        });


    });
}
if (divpedidos) {
    divpedidos.forEach(element => {
        const botonasignar = element.querySelector('.btnasignarrepartidor');
        if (botonasignar) {
            botonasignar.addEventListener('click', () => {
                const idpedido = botonasignar.dataset.id;
                modalasignarrepartidor.classList.remove('hidden');
                modalasignarrepartidor.classList.add('flex');
                paneladministrador.classList.add('bg-black', 'bg-opacity-30');
                divpedidos.forEach(element => {
                    element.classList.add('bg-black', 'bg-opacity-30');
                    element.querySelectorAll('button').forEach(element => {
                        element.disabled = true;
                    });

                });
                pedido_id.value = idpedido;
                spanrepartidor = element.querySelector('.spanrepartidor');
                modalUsuario.scrollTo({
                    top: 0,
                    behavior: 'smooth' // Opcional: desplazamiento suave
                });
            });
        }
    });
}

if (nuevousuarioadmin) {
    nuevousuarioadmin.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = nuevousuarioadmin.action;
        const method = nuevousuarioadmin.method;
        const data = new FormData(nuevousuarioadmin);
        // Convertir FormData a un objeto plano
        const plainData = {};
        data.forEach((value, key) => {
            plainData[key] = value;
        });

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(plainData),
        })
            .then(response => {
                if (response.status !== 201) {
                    return response.text().then((text) => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then(result => {
                mensajeExito(result.mensaje);
                nuevousuarioadmin.reset();

            })
            .catch(error => {
                mensajeError(error.message);
            });
    })
}
// Obtén todos los botones de estado
const btnsEstadoUsuarios = document.querySelectorAll('.btnestadosusuarios');
const formcambiarestado = document.querySelectorAll('.formcambiarestado');
// Itera sobre todos los botones y agrega el evento de clic
formcambiarestado.forEach(btn => {
    btn.addEventListener('submit', (event) => {
        event.preventDefault();
        const boton = event.target;
        const disparador = boton.querySelector("button[type='submit']");
        fetch(boton.action, {
            method: "PUT",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token, // Asegúrate de que `token` esté definido
            },
        })
            .then(response => {
                if (response.status !== 200) {  // Cambié 201 por 200, que es el estado más común para una respuesta exitosa
                    return response.text().then((text) => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then(result => {
                mensajeExito(result.mensaje);

                // Cambiar el estado del botón en función del estado actualizado
                if (result.nuevo_estado) {
                    disparador.classList.remove('bg-red-500');
                    disparador.classList.add('bg-green-500');
                    disparador.innerHTML = '<i class="fas fa-ban"></i> Inhabilitar';
                } else {
                    disparador.classList.remove('bg-green-500');
                    disparador.classList.add('bg-red-500');
                    disparador.innerHTML = '<i class="fas fa-check-circle "></i> Habilitar';
                }
            })
            .catch(error => {
                mensajeError(error.message);
            });
    });
});

if (btnnuevousuario) {
    btnnuevousuario.addEventListener('click', () => {
        modalnuevousuario.classList.remove('hidden');
        modalnuevousuario.classList.add('flex');
    });
}
if (closemodalusuario) {
    closemodalusuario.addEventListener('click', () => {
        cerrarModalusuario();
    });
}
function cerrarModalusuario() {
    modalnuevousuario.classList.remove('flex');
    modalnuevousuario.classList.add('hidden');
}
if (btnregresaradmin) {
    btnregresaradmin.addEventListener('click', () => {
        modalUsuario.classList.add('md:w-1/2');
        paneladministrador.classList.add('hidden');
        botondashboardclientepanel.classList.remove('hidden');
        divusuariosadmin.classList.add('hidden');

    });
}
if (btnpedidosadmin) {
    btnpedidosadmin.addEventListener('click', () => {
        botonseleccionado = btnpedidosadmin;
        cambiarcolorbotones();
        divpedidosadmin.classList.remove('hidden');
        divproductosadmin.classList.add('hidden');
        divclientesadminfull.classList.add('hidden');
        divusuariosadmin.classList.add('hidden');
        divreportesadmin.classList.add('hidden');

    });
}
if (btnproductosadmin) {
    btnproductosadmin.addEventListener('click', () => {
        botonseleccionado = btnproductosadmin;
        cambiarcolorbotones();
        divpedidosadmin.classList.add('hidden');
        divproductosadmin.classList.remove('hidden');
        divclientesadminfull.classList.add('hidden');
        divusuariosadmin.classList.add('hidden');
        divreportesadmin.classList.add('hidden');

    });
}
if (btnclientesadmin) {
    btnclientesadmin.addEventListener('click', () => {
        botonseleccionado = btnclientesadmin;
        cambiarcolorbotones();
        divpedidosadmin.classList.add('hidden');
        divproductosadmin.classList.add('hidden');
        divclientesadminfull.classList.remove('hidden');
        divusuariosadmin.classList.add('hidden');
        divreportesadmin.classList.add('hidden');

        traerClientesporempresa();
    });
}
if (btnusuariosadmin) {
    btnusuariosadmin.addEventListener('click', () => {
        botonseleccionado = btnusuariosadmin;
        cambiarcolorbotones();
        divpedidosadmin.classList.add('hidden');
        divproductosadmin.classList.add('hidden');
        divclientesadminfull.classList.add('hidden');
        divusuariosadmin.classList.remove('hidden');
        divreportesadmin.classList.add('hidden');

    });
}
if (btnreportesadmin) {
    btnreportesadmin.addEventListener('click', () => {
        botonseleccionado = btnreportesadmin;
        cambiarcolorbotones();
        divpedidosadmin.classList.add('hidden');
        divproductosadmin.classList.add('hidden');
        divclientesadminfull.classList.add('hidden');
        divusuariosadmin.classList.add('hidden');
        divreportesadmin.classList.remove('hidden');
    });
}
const form_clientes = document.getElementById('formclientes');

function traerClientesporempresa() {
    fetch(form_clientes.action, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => {
            if (response.status !== 200) {
                return response.text().then((text) => {
                    throw new Error(text);
                });
            }
            return response.json();
        })
        .then(result => {
            divclientesadmin.innerHTML = '';
            let cnt = 0;

            result.mensaje.forEach(element => {
                if (element.cliente) {
                    // Si el cliente existe, accede a sus propiedades
                    const nombres = element.cliente.nombres || 'Sin nombre';
                    const apellidos = element.cliente.apellidos || 'Sin apellidos';
                    const telefono = element.cliente.telefono || 'Sin teléfono';
                    const direccion = element.cliente.direccion || 'Sin dirección';

                    // Llamar a la función para agregar datos al DOM
                    agregarClienteDOM(`${nombres} ${apellidos}`, telefono, direccion);
                    cnt++;
                } else {
                    // Manejar casos donde el cliente es null
                    console.log('Cliente no asociado:', element.usuario);
                }
            });

            cantidadclientesadmin.innerHTML = cnt;
        })
        .catch(error => {
            mensajeError(error.mensaje);
        });
}
function agregarClienteDOM(nombre, telefono, ubicacion) {
    // Crear un nuevo elemento div
    const newelemento = document.createElement('div');

    // Agregar clases necesarias
    newelemento.classList.add('bg-tarjetas', 'text-white', 'shadow-md', 'rounded-lg', 'p-6', 'flex', 'flex-col', 'items-start', 'clientesadmin');

    // Definir el contenido HTML
    newelemento.innerHTML = `
        <!-- Icono y Nombre -->
        <div class="flex items-center space-x-4 mb-4 ">
            <i class="fas fa-user-circle text-3xl text-principal "></i>
            <h2 class=" font-medium ">${nombre}</h2>
        </div>
        <!-- Detalles del cliente -->
        <div class="text-sm space-y-2 mb-4">
            <p><i class="fas fa-phone text-yellow-500 m-2"></i> ${telefono}</p>
            <p><i class="fas fa-map-marker-alt text-red-500 m-2"></i> ${ubicacion}</p>
        </div>
    `;


    // Agregar el nuevo elemento al contenedor
    divclientesadmin.append(newelemento);
}


function cambiarcolorbotones() {
    switch (botonseleccionado) {
        case btnproductosadmin:
            btnproductosadmin.classList.add('bg-blue-600', 'text-white');
            btnpedidosadmin.classList.remove('bg-blue-600', 'text-white');
            btnreportesadmin.classList.remove('bg-blue-600', 'text-white');
            btnclientesadmin.classList.remove('bg-blue-600', 'text-white');
            btnusuariosadmin.classList.remove('bg-blue-600', 'text-white');

            break;
        case btnclientesadmin:
            btnclientesadmin.classList.add('bg-blue-600', 'text-white');
            btnpedidosadmin.classList.remove('bg-blue-600', 'text-white');
            btnreportesadmin.classList.remove('bg-blue-600', 'text-white');
            btnproductosadmin.classList.remove('bg-blue-600', 'text-white');
            btnusuariosadmin.classList.remove('bg-blue-600', 'text-white');

            break;
        case btnreportesadmin:
            btnreportesadmin.classList.add('bg-blue-600', 'text-white');
            btnpedidosadmin.classList.remove('bg-blue-600', 'text-white');
            btnproductosadmin.classList.remove('bg-blue-600', 'text-white');
            btnclientesadmin.classList.remove('bg-blue-600', 'text-white');
            btnusuariosadmin.classList.remove('bg-blue-600', 'text-white');

            break;
        case btnusuariosadmin:
            btnusuariosadmin.classList.add('bg-blue-600', 'text-white');
            btnpedidosadmin.classList.remove('bg-blue-600', 'text-white');
            btnproductosadmin.classList.remove('bg-blue-600', 'text-white');
            btnclientesadmin.classList.remove('bg-blue-600', 'text-white');
            btnreportesadmin.classList.remove('bg-blue-600', 'text-white');
            break;
        default:
            btnpedidosadmin.classList.add('bg-blue-600', 'text-white');
            btnproductosadmin.classList.remove('bg-blue-600', 'text-white');
            btnreportesadmin.classList.remove('bg-blue-600', 'text-white');
            btnclientesadmin.classList.remove('bg-blue-600', 'text-white');
            btnusuariosadmin.classList.remove('bg-blue-600', 'text-white');

            break;
    }
}
if (botondashboardclientepanel) {
    botondashboardclientepanel.addEventListener('click', () => {
        botondashboardclientepanel.classList.add('hidden');
        modalUsuario.classList.remove('md:w-1/2');
        paneladministrador.classList.remove('hidden');
    });
}







const tablapagospendientes = document.getElementsByClassName('tablapagospendientes');
const modalformadepago = document.getElementById('modalformadepago');
const confirmarBtn = document.getElementById('confirmarBtn');
const cerrarModalBtn = document.getElementById('cerrarModalBtn');
let pedio_id_pago;
let tr;
const metodoPago = document.querySelector('#metodoPago');
confirmarBtn.addEventListener('click', () => {
    const paymentMethod = metodoPago.value
    pagarCuenta(paymentMethod);

});

cerrarModalBtn.addEventListener('click', () => {
    modalformadepago.classList.remove('flex');
    modalformadepago.classList.add('hidden');
});

function pagarCuenta(paymentMethod) {
    const data = {
        pedido_id: pedio_id_pago, paymentMethod: paymentMethod
    };
    fetch(dominio + '/cancelarpedido', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            if (response.status !== 200) {
                return response.text().then((text) => {
                    throw new Error(text);
                });
            }
            return response.json();
        })
        .then(result => {
            mensajeExito(result.mensaje);
            pedio_id_pago = null;
            tr.remove(); // Eliminar la fila
            modalformadepago.classList.remove('flex');
            modalformadepago.classList.add('hidden');
        })
        .catch(error => {
            mensajeError(error.message);
        });
}
if (tablapagospendientes.length > 0) {
    // Iteramos sobre los elementos que tienen la clase 'tablapagospendientes'
    Array.from(tablapagospendientes).forEach(table => {
        table.addEventListener('click', (e) => {
            // Verifica si el elemento clicado es un botón con la clase 'btnpagarreporte'
            if (e.target.classList.contains('btnpagarreporte')) {
                pedio_id_pago = e.target.dataset.id; // Obtener el ID del pedido
                const td = e.target.closest('td'); // Obtener la celda que contiene el botón
                tr = td.closest('tr'); // Obtener la fila completa
                modalformadepago.classList.remove('hidden');
                modalformadepago.classList.add('flex');

            }
        });
    });
}

//SLIDER PRODUCTOS 
const slider = document.getElementById('slider');
const slides = document.querySelectorAll('#slider > div');
const prev = document.getElementById('prev');
const next = document.getElementById('next');

let currentIndex = 0;

const updateSlider = () => {
    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
};

if (next) {
    next.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlider();
    });
}

if (prev) {
    prev.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateSlider();
    });
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