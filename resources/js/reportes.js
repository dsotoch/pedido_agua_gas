import Swal from "sweetalert2";
const tabla_reportes = document.getElementById('tabla_reportes');
const orderId = document.getElementById('orderId');
const ClienteNombre = document.getElementById('clientName');
const ClienteID = document.getElementById('clientId');
const estadoSelect = document.getElementById('deliveryStatus');
const tipoSelect = document.getElementById('paymentMethod');
const radiosPago = document.querySelectorAll('input[name="paymentStatus"]');
const fechaFrom = document.getElementById('fecha_from');
const fechaTo = document.getElementById('fecha_to');
const botonFiltrar = document.getElementById('btn_filtar_por_fecha');
const numeroPedidos = document.getElementById('numero_pedidos');
const totalPedidos = document.getElementById('total_pedidos');
const form_metodo_pago_reporte = document.getElementById('form_metodo_pago_reporte');
const modal_pago_reporte = document.getElementById('modal_pago_reporte');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const contenedor_cantidad_productos = document.getElementById('contenedor_cantidad_productos');
let cantidad_total = 0;

// Verificar si la tabla existe
if (tabla_reportes) {
    cantidad_total = tabla_reportes.querySelectorAll('tbody tr').length;
}
function filtrar() {
    const filtroId = orderId.value.toLowerCase();
    const filtroNombre = ClienteNombre.value.toLowerCase();
    const filtroClienteID = ClienteID.value.toLowerCase();

    const filas = tabla_reportes.querySelectorAll('tbody tr');
    let total = 0;
    let contador = 0;
    filas.forEach(fila => {
        const celdaId = fila.cells[0]?.textContent.toLowerCase();
        const celdaClienteID = fila.cells[1]?.textContent.toLowerCase();
        const celdaClienteNombre = fila.cells[2]?.textContent.toLowerCase();
        const ths = document.querySelectorAll("#tabla_reportes th"); // Selecciona todos los <th> de la tabla

        let thTotal = null;
        ths.forEach((th, index) => {

            if (th.textContent.trim() === 'Total') {
                thTotal = index;
            }
        });
        const celdaTotal = parseFloat(fila.cells[thTotal]?.textContent.trim()) || 0; // Obtener el valor de la columna de total

        // Mostrar u ocultar la fila según los filtros
        if (
            (filtroId === '' || celdaId.includes(filtroId)) &&
            (filtroClienteID === '' || celdaClienteID.includes(filtroClienteID)) &&
            (filtroNombre === '' || celdaClienteNombre.includes(filtroNombre))
        ) {
            fila.style.display = '';
            contador++;
            total += celdaTotal;
        } else {
            fila.style.display = 'none';
        }
    });
    cantidad_total = contador;
    actualizarPedidos(contador, total);

}

// Asignar eventos de escucha a los inputs
[orderId, ClienteNombre, ClienteID].forEach(input => {
    if (input) {
        input.addEventListener('input', filtrar);

    }
});
function filtrarPorSelect() {
    const filtroEstado = estadoSelect.value.toLowerCase();
    const filtroTipo = tipoSelect.value.toLowerCase();

    const filas = tabla_reportes.querySelectorAll('tbody tr');
    let total = 0;
    let contador = 0;
    filas.forEach(fila => {
        const ths = document.querySelectorAll("#tabla_reportes th"); // Selecciona todos los <th> de la tabla
        let thEstado = null;
        ths.forEach((th, index) => {
            if (th.textContent.trim() === "Delivery") {
                thEstado = index;
            }

        });
        const celdaEstado = fila.cells[thEstado]?.textContent.toLowerCase();
        const celdaTipo = fila.cells[3]?.textContent.toLowerCase();
        const celdaTotal = parseFloat(fila.cells[4]?.textContent.trim()) || 0; // Obtener el valor de la columna de total

        // Mostrar u ocultar la fila según los filtros
        if (
            (filtroEstado === '' || celdaEstado.includes(filtroEstado)) &&
            (filtroTipo === '' || celdaTipo.includes(filtroTipo))
        ) {
            fila.style.display = '';
            total += celdaTotal;
            contador++;
        } else {
            fila.style.display = 'none';
        }
    });
    cantidad_total = contador;
    actualizarPedidos(contador, total);

}

// Asignar eventos de cambio a los selectores
[estadoSelect, tipoSelect].forEach(select => {
    if (select) {
        select.addEventListener('change', filtrarPorSelect);

    }
});


function filtrarPorEstadoPago() {
    const estadoSeleccionado = document.querySelector('input[name="paymentStatus"]:checked').value.toLowerCase();

    const filas = tabla_reportes.querySelectorAll('tbody tr');
    let total = 0;
    let contador = 0;
    filas.forEach(fila => {
        const ths = document.querySelectorAll("#tabla_reportes th"); // Selecciona todos los <th> de la tabla
        let thEstado = null;
        let thTotal = null;
        ths.forEach((th, index) => {
            if (th.textContent.trim() === 'Pago') {
                thEstado = index;
            }
            if (th.textContent.trim() === 'Total') {
                thTotal = index;
            }

        });
        let celdaEstadoPago = '';
        let celdaTotal = 0;

        if (estadoSeleccionado === 'deuda pendiente') {
            celdaEstadoPago = fila.cells[3]?.textContent?.toLowerCase() || '';
            const celdaTotalIndex = thTotal; // Asegúrate de que `thTotal` está definido correctamente
            celdaTotal = parseFloat(fila.cells[celdaTotalIndex]?.textContent.trim()) || 0;
        } else {
            celdaEstadoPago = fila.cells[thEstado]?.textContent?.toLowerCase() || '';
            const celdaTotalIndex = thEstado - 2; // Dos celdas antes
            celdaTotal = parseFloat(fila.cells[celdaTotalIndex]?.textContent.trim()) || 0;
        }

        // Mostrar todas las filas si "Todos" está seleccionado o si coincide el estado de pago
        if (estadoSeleccionado === '' || celdaEstadoPago.includes(estadoSeleccionado)) {
            fila.style.display = '';
            contador++;
            total += celdaTotal;
        } else {
            fila.style.display = 'none';
        }

    });
    cantidad_total = contador;
    actualizarPedidos(contador, total);
}


// Asignar eventos de cambio a los radio buttons
radiosPago.forEach(radio => {
    if (radio) {
        radio.addEventListener('change', filtrarPorEstadoPago);

    }
});







function filtrarPorFecha() {
    const fromDate = fechaFrom.value ? fechaFrom.value.split('-').reverse().join('-') : null; // Convertir "2025-01-23" → "23-01-2025"
    const toDate = fechaTo.value ? fechaTo.value.split('-').reverse().join('-') : null; // Convertir "2025-01-23" → "23-01-2025"


    const filas = tabla_reportes.querySelectorAll('tbody tr');
    let total = 0;
    let contador = 0;
    filas.forEach(fila => {
        const ths = document.querySelectorAll("#tabla_reportes th"); // Selecciona todos los <th> de la tabla
        let thFecha = null;
        let thTotal = null;
        ths.forEach((th, index) => {
            if (th.textContent.trim() === 'Fecha') {
                thFecha = index;
            }
            if (th.textContent.trim() === 'Total') {
                thTotal = index;
            }
        });
        const celdaFecha = fila.cells[thFecha]?.textContent.trim(); // Obtener el texto de la celda de fecha
        const celdaTotal = parseFloat(fila.cells[thTotal]?.textContent.trim()) || 0; // Obtener el valor de la columna de total

        if (!celdaFecha) return;

        const fechaFila = celdaFecha.split(' ')[0]; // Extraer solo la fecha "23-01-2025"

        // Validar el rango de fechas
        if (
            (!fromDate || fechaFila >= fromDate) &&
            (!toDate || fechaFila <= toDate)
        ) {
            fila.style.display = '';
            total += celdaTotal;
            contador++;

        } else {
            fila.style.display = 'none';
        }
    });
    cantidad_total = contador;
    actualizarPedidos(contador, total);
}



// Asignar evento al botón de filtro
if (botonFiltrar) {
    botonFiltrar.addEventListener('click', (e) => {
        e.preventDefault(); // Evita recargar la página si el botón está en un formulario
        filtrarPorFecha();
    });
}


function filtrarPorCheckbox() {
    const checkboxes = document.querySelectorAll('input[name="noveno_gratis"]:checked');
    const valoresSeleccionados = Array.from(checkboxes).map(cb => cb.value); // Obtener los valores seleccionados (["NG", "0"])

    const filas = tabla_reportes.querySelectorAll('tbody tr');
    let contador = 0; // Contador de pedidos filtrados
    let total = 0; // Total de los pedidos filtrados

    filas.forEach(fila => {
        const ths = document.querySelectorAll("#tabla_reportes th"); // Selecciona todos los <th> de la tabla
        let thValor = null;
        let thTotal = null;
        ths.forEach((th, index) => {
            if (th.textContent.trim() === 'PG') {
                thValor = index;
            }
            if (th.textContent.trim() === 'Total') {
                thTotal = index;
            }
        });
        const celdaValor = fila.cells[thValor]?.textContent.trim(); // Obtener el valor de la columna
        const celdaTotal = parseFloat(fila.cells[thTotal]?.textContent.trim()) || 0; // Obtener el valor de la columna de total

        // Si no hay checkboxes seleccionados, mostrar todas las filas
        if (valoresSeleccionados.length === 0 || valoresSeleccionados.includes(celdaValor)) {
            fila.style.display = '';
            contador++;
            total += celdaTotal;
        } else {
            fila.style.display = 'none';
        }
    });
    cantidad_total = contador;
    actualizarPedidos(contador, total);
}


// Asignar evento a los checkboxes
document.querySelectorAll('input[name="noveno_gratis"]').forEach(checkbox => {
    if (checkbox) {
        checkbox.addEventListener('change', filtrarPorCheckbox);
    }
});

function actualizarPedidos(numero, total) {
    const ths = document.querySelectorAll("#tabla_reportes th"); // Selecciona todos los <th> de la tabla
    let thMedioIndex = -1;
    let thTotalIndex = -1;
    let thValores = [];

    ths.forEach((th, index) => {
        const texto = th.textContent.trim();
        if (texto === 'Medio') {
            thMedioIndex = index;
        } else if (texto === 'Total') {
            thTotalIndex = index;
        }
    });

    const thsArray = Array.from(ths);

    // Ahora puedes usar el método slice
    if (thMedioIndex !== -1 && thTotalIndex !== -1) {
        thValores = thsArray.slice(thMedioIndex + 1, thTotalIndex).map((th, i) => ({
            descripcion: th.textContent.trim(),
            indice: thMedioIndex + 1 + i,
            cantidad: 0 // 🔹 Inicializamos cantidad
        }));
        // Recorremos SOLO las filas visibles y sumamos los valores de cada columna
        document.querySelectorAll("tbody tr").forEach(fila => {
            if (fila.style.display !== "none") { // 🔹 Solo filas visibles
                thValores.forEach(columna => {
                    let valor = parseFloat(fila.cells[columna.indice]?.textContent.trim()) || 0;
                    columna.cantidad += valor; // Sumamos solo las filas visibles
                });
            }
        });
    }
    if (numeroPedidos && totalPedidos && contenedor_cantidad_productos) {
        numeroPedidos.textContent = numero;
        totalPedidos.textContent = 'S/.' + total;
        contenedor_cantidad_productos.innerHTML = '';

        // Iterar sobre el array y crear los elementos
        thValores.forEach(({ descripcion, cantidad }) => {
            const div = document.createElement("div");
            div.classList.add("flex", "items-center");

            div.innerHTML = `
        <b id="cantidad_producto">${cantidad}</b>
        <span class="ml-1" id="producto">${descripcion}</span>
        <span class="text-naranja font-bold mx-2 text-2xl">></span>
    `;

            contenedor_cantidad_productos.appendChild(div);
        });
    }
}


/* Modal para cancelar la deuda de los clientes    */
let pedido_id;
let columna_para_modificar = null;
let columna_para_modificar_estado = null;
let columna_para_modificar_delivery = null;
const modal_pago_reporte_id = document.getElementById('modal_pago_reporte_id');
const id_pedido_modal_pago = document.getElementById('id_pedido_modal_pago');

if (tabla_reportes) {
    const btn_cliente_pago_deuda = tabla_reportes.querySelectorAll('.btn_cliente_pago_deuda');
    const btn_eliminar_pedido = tabla_reportes.querySelectorAll('.btn_eliminar_pedido');
    if (btn_cliente_pago_deuda) {
        btn_cliente_pago_deuda.forEach(element => {
            element.addEventListener('click', (e) => {
                let disparador = e.target.closest('[data-id]');
                pedido_id = disparador.dataset.id;
                modal_pago_reporte.classList.remove('hidden');
                modal_pago_reporte.classList.add('flex');
                const ths = document.querySelectorAll("#tabla_reportes th"); // Selecciona todos los <th> de la tabla
                let colModificar = null;
                let colModificarEstado = null;
                let colModificarDelivery = null;
                ths.forEach((th, index) => {
                    if (th.textContent.trim() === 'Medio') {
                        colModificar = index;
                    }
                    if (th.textContent.trim() === 'Pago') {
                        colModificarEstado = index;
                    }
                    if (th.textContent.trim() === 'Delivery') {
                        colModificarDelivery = index;
                    }
                });
                columna_para_modificar = e.target.closest('tr').cells[colModificar];
                columna_para_modificar_estado = e.target.closest('tr').cells[colModificarEstado];
                columna_para_modificar_delivery = e.target.closest('tr').cells[colModificarDelivery];
                modal_pago_reporte_id.textContent = "#" + pedido_id;
                id_pedido_modal_pago.value = pedido_id;
            })
        });

    }
    if (btn_eliminar_pedido) {
        btn_eliminar_pedido.forEach(element => {
            element.addEventListener('click', async (e) => {
                let disparador = e.target.closest('[data-id]');
                let tr = disparador.closest('tr');
                let pedido_id = disparador.dataset.id; // Usar `let` en vez de `pedido_id =`

                const response = await fetch(`/eliminarPedido/${pedido_id}`, { // Agregar barra `/`
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json', // Corregido el error tipográfico
                        'X-CSRF-TOKEN': token
                    }
                });

                if (!response.ok) {
                    throw new Error(await response.text());
                }

                const result = await response.json();
                const mensaje = result.mensaje; // No es necesario `JSON.stringify()`

                Swal.fire({
                    title: 'Pedido Eliminado!',
                    text: mensaje,
                    icon: 'success',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 2000,
                    customClass: {
                        timerProgressBar: 'bg-green-500 h-2 rounded-md'
                    }
                });
                tr.remove();
                const ths = document.querySelectorAll("#tabla_reportes th"); // Selecciona todos los <th> de la tabla
                let thTotal = 0;
                ths.forEach((th, index) => {
                    const texto = th.textContent.trim();
                    if (texto === 'Total') {
                        thTotal = index;
                    }
                });
                let total = 0;

                // Seleccionamos todas las filas VISIBLES del tbody
                document.querySelectorAll("tbody tr").forEach(fila => {
                    if (fila.style.display !== "none") { // Solo tomamos las visibles
                        const celdaTotal = parseFloat(fila.cells[thTotal]?.textContent.trim()) || 0;
                        total += celdaTotal;
                    }
                });


                actualizarPedidos((cantidad_total - 1), total);

            });
        });
    }

}
if (form_metodo_pago_reporte) {
    form_metodo_pago_reporte.addEventListener('submit', (e) => {
        e.preventDefault();
        const datosform = new FormData(form_metodo_pago_reporte);
        let data = {};
        datosform.forEach((value, key) => {
            data[key] = value;
        });
        fetch(form_metodo_pago_reporte.action, {
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
                Swal.fire(
                    {
                        title: 'Confirmación!',
                        text: result.mensaje,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        customClass: {
                            timerProgressBar: 'bg-green-500'
                        }
                    }
                )
                modal_pago_reporte.classList.remove('flex');
                modal_pago_reporte.classList.add('hidden');
                columna_para_modificar.textContent = result.nuevo_metodo;
                columna_para_modificar_estado.textContent = 'Pagado';
                columna_para_modificar_delivery = 'Entregado';
            })
            .catch(error => {
                mensajeError("Ocurrio un error al editar el pedido."); // Mostrar mensaje de error

            });


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