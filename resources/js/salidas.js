import Swal from "sweetalert2";
const btn_agregar_vehiculo = document.getElementById('btn_agregar_vehiculo');
const btn_guardar_vehiculo = document.getElementById('btn_guardar_vehiculo');
const vehiculo_select = document.getElementById('vehiculo-select');
const repartidor_select = document.getElementById('repartidor-select');
const btn_editar_vehiculo = document.getElementById("btn_editar_vehiculo");
const btn_eliminar_vehiculo = document.getElementById("btn_eliminar_vehiculo");
const token = document.querySelector('meta[name="token"]').getAttribute('content');

const modal = document.getElementById("modal_vehiculos");
const modalVehiculo = document.getElementById("modal-vehiculo");
const modalVehiculoInput = document.getElementById("modal-vehiculo_input");

const modalRepartidor = document.getElementById("modal-repartidor");
const nuevoRepartidor = document.getElementById("nuevo-repartidor");
const btnCerrar = document.getElementById("cerrar-modal");
const btnGuardar = document.getElementById("guardar-cambio");
const modalVerSalida = document.getElementById('modal_ver_salida');
const cerrarModalBtns = document.querySelectorAll('#cerrar_modal, #cerrar_modal_footer');
const tablaProductos = document.getElementById('tabla_productos');
const btnVerSalida = document.querySelectorAll('#btn_ver_salida'); // Asegúrate de tener este botón en tu HTML
if (btnVerSalida) {
    btnVerSalida.forEach((element) => {
        element.addEventListener('click', (e) => {
            const btn = e.target.closest('#btn_ver_salida'); // Busca el botón más cercano
            let salidaId;
            if (btn) {
                salidaId = btn.dataset.id;
            }

            fetch(`/salidas/${salidaId}`)
                .then(response => response.json())
                .then(data => {
                    tablaProductos.innerHTML = ''; // Limpiar la tabla antes de insertar nuevos datos

                    if (data.error) {
                        console.error("Error:", data.error);
                        return;
                    }

                    // `data.productos` ya es un array de objetos, NO necesitas hacer JSON.parse()
                    data.productos.forEach(producto => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                        <td class="border p-2 text-center">${producto.nombre}</td>
                       
                        <td class="border p-2 text-center">${producto.cantidad}</td>
                    `;
                        tablaProductos.appendChild(fila);
                    });

                    modalVerSalida.classList.remove('hidden'); // Mostrar el modal
                    modalVerSalida.classList.add('flex'); // Mostrar el modal

                })
                .catch(error => console.error('Error al obtener los productos:', error));
        });
    })

}


// Cerrar el modal cuando se presione el botón de cerrar
if (cerrarModalBtns) {
    cerrarModalBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modalVerSalida.classList.remove('flex'); // Mostrar el modal
            modalVerSalida.classList.add('hidden');
        });
    });
}
if (vehiculo_select) {
    vehiculo_select.addEventListener('change', () => {
        document.getElementById('placa_salida').value = '';

    });
}

let vehiculoActual = "";

if (btn_editar_vehiculo) {
    btn_editar_vehiculo.addEventListener("click", function () {
        if (vehiculo_select.value == '') {
            errorMensaje("Selecciona un vehiculo para editar", 'Requerimiento faltante.');
            return;
        }
        const datos = (vehiculo_select.options[vehiculo_select.selectedIndex].textContent).split('-');

        vehiculoActual = datos[0].trim();
        const repartidorId = datos[1];

        modalVehiculo.textContent = vehiculoActual;
        modalRepartidor.textContent = repartidorId;
        modalVehiculoInput.value = vehiculoActual;
        // Mostrar modal
        modal.classList.remove("hidden");
    });
}
if (btn_eliminar_vehiculo) {
    btn_eliminar_vehiculo.addEventListener("click", function () {

        if (vehiculo_select.value == '') {
            errorMensaje("Selecciona un vehiculo para Eliminar", 'Requerimiento faltante.');
            return;
        }
        const datos = (vehiculo_select.options[vehiculo_select.selectedIndex].textContent).split('-');
        const empresaID = document.getElementById('empresaId_salida').value.trim();
        vehiculoActual = datos[0].trim();

        if (!confirm("¿Estás seguro de eliminar este vehículo?")) return;

        fetch("/eliminarVehiculo", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({ id: vehiculoActual, empresa_id: empresaID }) // Enviar el ID del vehículo
        })
            .then(response => {
                if (!response.ok) { // Verifica el código HTTP
                    return response.json().then(err => { throw new Error(err.mensaje); });
                }
                return response.json();
            })
            .then(data => {
                exitoMensaje(data.mensaje, "Confirmación");

                // Elimina la opción seleccionada en el select
                const opcionSeleccionada = vehiculo_select.querySelector(`option[value="${vehiculo_select.value}"]`);
                if (opcionSeleccionada) {
                    opcionSeleccionada.remove();
                }
            })
            .catch(error => errorMensaje(error.message, 'Error al Procesar'));



    });
}

if (btnCerrar) {
    btnCerrar.addEventListener("click", function () {
        modal.classList.add("hidden");
    });
}

if (btnGuardar) {
    btnGuardar.addEventListener("click", function () {
        const repartidorSeleccionado_id = nuevoRepartidor.value;
        const repartidorSeleccionado = nuevoRepartidor.options[nuevoRepartidor.selectedIndex].textContent;
        let vehiculo = modalVehiculo.textContent.trim();
        // Si el vehículo ha cambiado, actualizar vehiculoActual
        vehiculoActual = modalVehiculoInput.value.trim();


        // Validar si la placa ya está registrada antes de eliminar
        if (placaExiste(vehiculoActual)) {
            errorMensaje('La placa ingresada ya está registrada.', 'Conflicto de datos');
            return;
        }

        document.getElementById('placa_salida').value = vehiculo;
        // Eliminar la opción seleccionada solo si existe
        if (vehiculo_select.selectedIndex !== -1) {
            vehiculo_select.options[vehiculo_select.selectedIndex].remove();
        }

        // Verificar si la opción ya existe en el select
        const existeOpcion = Array.from(vehiculo_select.options).some(option => option.value.startsWith(vehiculoActual));
        if (existeOpcion) {
            errorMensaje('La placa ingresada ya está registrada en el listado.', 'Conflicto de datos');
            return;
        }

        // Crear y agregar nueva opción
        let option = document.createElement('option');
        option.value = vehiculoActual + '-' + repartidorSeleccionado_id;
        option.textContent = `${vehiculoActual} - ${repartidorSeleccionado}`;
        vehiculo_select.appendChild(option);

        // Seleccionar la nueva opción
        vehiculo_select.value = option.value;



        // Cerrar modal
        modal.classList.add("hidden");
    });
}





if (btn_guardar_vehiculo) {
    btn_guardar_vehiculo.addEventListener('click', () => {
        let placa = document.getElementById('placa').value.trim();

        if (placa === '' || repartidor_select.value.trim() === '') {
            errorMensaje('Por favor, ingrese la placa y seleccione  el repartidor.', 'Requerimientos Faltantes');
            return;
        }
        if (placaExiste(placa)) {
            errorMensaje('La placa ingresada ya esta registrada.', 'Conflicto de datos');

            return '';
        }
        let select = document.getElementById('vehiculo-select');

        // Crear nueva opción
        let option = document.createElement('option');
        option.value = placa + '-' + repartidor_select.value.trim(); // Temporalmente, sin ID real
        option.textContent = `${placa} - ${repartidor_select.options[repartidor_select.selectedIndex].text.trim()}`;
        select.appendChild(option);

        // Seleccionar la nueva opción
        select.value = option.value;

        // Ocultar formulario
        document.getElementById('nuevo-vehiculo-form').classList.add('hidden');
        btn_agregar_vehiculo.disabled = true;

    })
}

function placaExiste(placa) {
    let select = document.getElementById('vehiculo-select');

    for (let i = 0; i < select.options.length; i++) {
        let valor = select.options[i].value.split("-")[0]; // Obtener la placa (antes del guion)
        if (valor.trim() === placa.trim()) {
            return true;
        }
    }
    return false; // La placa no existe
}
if (btn_agregar_vehiculo) {
    btn_agregar_vehiculo.addEventListener('click', () => {
        document.getElementById('nuevo-vehiculo-form').classList.remove('hidden');
    });
}

function errorMensaje(texto, titulo) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'error',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            timerProgressBar: 'bg-red-500 h-2 rounded'
        }
    })
}
function exitoMensaje(texto, titulo) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'success',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            timerProgressBar: 'bg-green-500 h-2 rounded'
        }
    })
}




document.addEventListener("DOMContentLoaded", function () {
    const modal_editar_salida = document.getElementById('modal_editar_salida');
    document.querySelectorAll("button[data-productos]").forEach((boton) => {
        boton.addEventListener("click", function () {
            modal_editar_salida.classList.remove('hidden');
            modal_editar_salida.classList.add('flex');
            const productosData = this.getAttribute("data-productos");
            const salidaId = this.getAttribute("data-id"); // Obtener salida_id
            let productos2 = [];

            try {
                productos2 = JSON.parse(productosData);
            } catch (error) {
                console.error("Error al parsear JSON de productos:", error);
                return;
            }

            const formContainer = document.getElementById("formulario-edicion-productos");
            formContainer.innerHTML = ""; // Limpiar contenido previo
            formContainer.cla

            // Input oculto con salida_id
            let inputSalidaId = document.createElement("input");
            inputSalidaId.type = "text";
            inputSalidaId.name = "salida_id";
            inputSalidaId.value = salidaId;
            inputSalidaId.hidden = true;
            formContainer.appendChild(inputSalidaId);

            productos2.forEach((item) => {

                let contenedor = document.createElement("div");
                contenedor.classList.add('border', 'p-2', 'contenedor_producto');

                // Crear un select para producto
                let selectProducto = document.createElement("select");
                selectProducto.name = "productos[]";
                selectProducto.classList.add("border", "p-2", "rounded", "w-full", "mt-4");

                // Opción predeterminada con el producto seleccionado
                let optionSelected = document.createElement("option");
                optionSelected.value = item.id;
                optionSelected.textContent = item.nombre;
                optionSelected.selected = true;
                selectProducto.appendChild(optionSelected);

                let divContainer = document.createElement("div");
                divContainer.classList.add("flex", "flex-col", "space-y-2", 'border', 'p-2');

                // Crear el texto descriptivo
                let labelText = document.createElement("span");
                labelText.textContent = "Cantidad a agregar:";
                labelText.classList.add("text-gray-700", "text-base");
                // Crear input para cantidad
                let bntEliminar = document.createElement("button");
                bntEliminar.textContent = "Eliminar";
                bntEliminar.type = 'button';
                bntEliminar.classList.add("border", "p-2", "rounded", 'font-semibold', 'border-color-titulos-entrega', 'text-color-titulos-entrega', 'hover:scale-x-105');
                bntEliminar.addEventListener('click', () => {
                    bntEliminar.closest('.contenedor_producto').remove();
                    productos2 = productos2.filter(producto => producto.id !== item.id);
                })
                // Crear input para cantidad
                let inputCantidad = document.createElement("input");
                inputCantidad.type = "number";
                inputCantidad.name = "cantidades[]";
                inputCantidad.value = '0';
                inputCantidad.classList.add("border", "p-2", "rounded", "w-full");
                divContainer.appendChild(bntEliminar);

                // Agregar el texto y el input al contenedor
                divContainer.appendChild(labelText);
                divContainer.appendChild(inputCantidad);

                // Contenedor de inputs
                let div = document.createElement("div");
                div.classList.add("flex", "space-x-2", "mb-2", "items-center");


                // Agregar los elementos al div contenedor
                div.appendChild(selectProducto);
                div.appendChild(divContainer);
                contenedor.appendChild(div);
                formContainer.appendChild(contenedor);
            });

        });
    });
});


