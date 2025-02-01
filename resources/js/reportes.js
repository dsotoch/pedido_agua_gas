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

// Filtrar filas de la tabla
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
        const celdaTotal = parseFloat(fila.cells[4]?.textContent.trim()) || 0; // Obtener el valor de la columna de total

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
        const celdaEstado = fila.cells[7]?.textContent.toLowerCase();
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
        const celdaEstadoPago = fila.cells[6]?.textContent.toLowerCase();
        const celdaTotal = parseFloat(fila.cells[4]?.textContent.trim()) || 0; // Obtener el valor de la columna de total

        // Mostrar todas las filas si "Todos" está seleccionado o si coincide el estado de pago
        if (estadoSeleccionado === '' || celdaEstadoPago.includes(estadoSeleccionado)) {
            fila.style.display = '';
            contador++;
            total += celdaTotal;
        } else {
            fila.style.display = 'none';
        }
    });
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
        const celdaFecha = fila.cells[8]?.textContent.trim(); // Obtener el texto de la celda de fecha
        const celdaTotal = parseFloat(fila.cells[4]?.textContent.trim()) || 0; // Obtener el valor de la columna de total

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
    actualizarPedidos(contador, total);
}

[fechaFrom, fechaTo].forEach(fecha => {
    if (fecha) {
        fecha.addEventListener('change', () => {
            filtrarPorFecha();
        })
    }
})

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
        const celdaValor = fila.cells[5]?.textContent.trim(); // Obtener el valor de la columna
        const celdaTotal = parseFloat(fila.cells[4]?.textContent.trim()) || 0; // Obtener el valor de la columna de total

        // Si no hay checkboxes seleccionados, mostrar todas las filas
        if (valoresSeleccionados.length === 0 || valoresSeleccionados.includes(celdaValor)) {
            fila.style.display = '';
            contador++;
            total += celdaTotal;
        } else {
            fila.style.display = 'none';
        }
    });

    actualizarPedidos(contador, total);
}


// Asignar evento a los checkboxes
document.querySelectorAll('input[name="noveno_gratis"]').forEach(checkbox => {
    if (checkbox) {
        checkbox.addEventListener('change', filtrarPorCheckbox);
    }
});

function actualizarPedidos(numero, total) {
    if (numeroPedidos && totalPedidos) {
        numeroPedidos.textContent = numero;
        totalPedidos.textContent = 'S/.' + total;
    }
}


/* Modal para cancelar la deuda de los clientes    */
let pedido_id;
let columna_para_modificar;
let columna_para_modificar_estado;
const modal_pago_reporte_id = document.getElementById('modal_pago_reporte_id');
const id_pedido_modal_pago = document.getElementById('id_pedido_modal_pago');

if (tabla_reportes) {
    const btn_cliente_pago_deuda = tabla_reportes.querySelectorAll('.btn_cliente_pago_deuda');
    if (btn_cliente_pago_deuda) {
        btn_cliente_pago_deuda.forEach(element => {
            element.addEventListener('click', (e) => {
                let disparador = e.target.closest('[data-id]');
                pedido_id = disparador.dataset.id;
                modal_pago_reporte.classList.remove('hidden');
                modal_pago_reporte.classList.add('flex');
                columna_para_modificar = e.target.closest('tr').cells[3];
                columna_para_modificar_estado = e.target.closest('tr').cells[6];
                modal_pago_reporte_id.textContent = "#" + pedido_id;
                id_pedido_modal_pago.value = pedido_id;
            })
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
            })
            .catch(error => {
                mensajeError(error.message); // Mostrar mensaje de error

            });


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