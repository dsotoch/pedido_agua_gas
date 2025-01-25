import Swal from "sweetalert2";

const btndashboardrepartidor = document.getElementById('btndashboardrepartidor');
const panelrepartidor = document.getElementById('panelrepartidor');
const modalUsuario = document.getElementById('modalUsuario');
const botonesclientepanelrepartidor = document.getElementById('botonesclientepanelrepartidor');
const btnregresarrepartidor = document.getElementById('btnregresarrepartidor');
const imgmoto = document.getElementById("imgmoto");
const imgmotomini = document.getElementById('imgmotomini');
const pedidosrepartidor = document.querySelectorAll('.pedidosrepartidor');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const paymentModal = document.getElementById('paymentModal');
const form_metodo_pago_repartidor = document.getElementById('form_metodo_pago_repartidor');
let spanpagado;
let spanestadopedido;
let metodo;
let pedido;
if (form_metodo_pago_repartidor) {
    form_metodo_pago_repartidor.addEventListener('submit', (e) => {
        e.preventDefault();
        const datosform = new FormData(form_metodo_pago_repartidor);
        let data = {};
        datosform.forEach((value, key) => {
            data[key] = value;
        });
        data['id_pedido'] = pedido;
        fetch(form_metodo_pago_repartidor.action, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json', // Define el contenido como JSON
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(data)
        }).then(response => {
            // Verificar el código de estado
            if (response.status !== 200) {
                // Obtener el mensaje de error del servidor
                return response.text().then((text) => {
                    throw new Error(text); // Lanza un error con el mensaje del servidor
                });
            }
            return response.json(); // Convertir la respuesta exitosa a JSON
        })
            .then(result => {

                mensajeExito(result.mensaje); // Manejo del resultado exitoso
                pedido = 0;
                paymentModal.classList.remove('hidden');
                paymentModal.classList.add('hidden');
                if (result.detalles.pago) {
                    spanpagado.classList.remove('text-red-600');
                    spanpagado.classList.add('text-green-600');
                    spanpagado.innerHTML = '✅';
                }
                switch (result.detalles.metodo) {
                    case 'yape':
                        metodo.innerHTML = 'Pagado con Yape';
                        break;
                    case 'account':
                        metodo.innerHTML = 'Pendiente de pago a Cuenta';
                        break;

                    default:
                        metodo.innerHTML = 'Pagado en Efectivo';

                        break;
                }
                spanestadopedido.innerHTML = 'Entregado';

            })
            .catch(error => {
                mensajeError(error.message); // Mostrar mensaje de error

            });


    });
}
if (pedidosrepartidor) {
    pedidosrepartidor.forEach(element => {
        const btnpagorepartidor = element.querySelector('.btnpagorepartidor');

        if (btnpagorepartidor) {
            btnpagorepartidor.addEventListener('click', () => {
                paymentModal.classList.remove('hidden');
                paymentModal.classList.add('flex');
                pedido = btnpagorepartidor.dataset.id;
                modalUsuario.scrollTo({
                    top: 0,
                    behavior: 'smooth' // Opcional: desplazamiento suave
                });
                const divprincipal = btnpagorepartidor.closest('.pedidosrepartidor');
                const spanestadorepartidor = divprincipal.querySelector('.spanestadorepartidor');
                spanpagado = divprincipal.querySelector('.spanpagado');
                spanestadopedido = spanestadorepartidor;
                metodo = divprincipal.querySelector('.metodopedido');
            });

        }
        const formulario_repartidor = element.querySelector('.formaceptarrepartidor');
        if (formulario_repartidor) {
            const boton_aceptar_pedido_repartidor = formulario_repartidor.querySelector('.btnaceptarrepartidor');

            const spanestadorepartidor = element.querySelector('.spanestadorepartidor');

            boton_aceptar_pedido_repartidor.addEventListener('click', () => {
                boton_aceptar_pedido_repartidor.disabled = true;
                fetch(formulario_repartidor.action, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json', // Define el contenido como JSON
                        'X-CSRF-TOKEN': token,
                    },
                }).then(response => {
                    // Verificar el código de estado
                    if (response.status !== 200) {
                        // Obtener el mensaje de error del servidor
                        return response.text().then((text) => {
                            throw new Error(text); // Lanza un error con el mensaje del servidor
                        });
                    }
                    return response.json(); // Convertir la respuesta exitosa a JSON
                })
                    .then(result => {
                        spanestadorepartidor.classList.remove('text-red-600');
                        spanestadorepartidor.classList.add('text-green-600');
                        spanestadorepartidor.innerHTML = 'En camino 🚚';
                        mensajeExito(result.mensaje); // Manejo del resultado exitoso
                        boton_aceptar_pedido_repartidor.classList.add('hidden');
                    })
                    .catch(error => {
                        mensajeError(error.message); // Mostrar mensaje de error
                        boton_aceptar_pedido_repartidor.disabled = false;

                    });
            });

        }
    });
}
if (btndashboardrepartidor) {
    btndashboardrepartidor.addEventListener('click', () => {
        botonesclientepanelrepartidor.classList.remove('flex');
        botonesclientepanelrepartidor.classList.add('hidden');
        panelrepartidor.classList.remove('hidden');
        panelrepartidor.classList.add('grid');

        modalUsuario.classList.remove('md:w-1/2');
        imgmoto.classList.add('hidden');
        imgmotomini.classList.remove('hidden');
    });
}
if (btnregresarrepartidor) {
    btnregresarrepartidor.addEventListener('click', () => {
        botonesclientepanelrepartidor.classList.remove('hidden');
        botonesclientepanelrepartidor.classList.add('flex');
        modalUsuario.classList.add('md:w-1/2');
        panelrepartidor.classList.remove('grid');
        panelrepartidor.classList.add('hidden');
        imgmoto.classList.remove('hidden');
        imgmotomini.classList.add('hidden');


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