import Swal from "sweetalert2";
const btn_agregar_vehiculo = document.getElementById('btn_agregar_vehiculo');
const btn_guardar_vehiculo = document.getElementById('btn_guardar_vehiculo');
const vehiculo_select = document.getElementById('vehiculo-select');
const repartidor_select = document.getElementById('repartidor-select');
const btn_editar_vehiculo = document.getElementById("btn_editar_vehiculo");
const modal = document.getElementById("modal_vehiculos");
const modalVehiculo = document.getElementById("modal-vehiculo");
const modalRepartidor = document.getElementById("modal-repartidor");
const nuevoRepartidor = document.getElementById("nuevo-repartidor");
const btnCerrar = document.getElementById("cerrar-modal");
const btnGuardar = document.getElementById("guardar-cambio");
let vehiculoActual = "";

if (btn_editar_vehiculo) {
    btn_editar_vehiculo.addEventListener("click", function () {
        if (vehiculo_select.value == '') {
            errorMensaje("Selecciona un vehiculo para editar", 'Requerimiento faltante.');
            return;
        }
        const datos = (vehiculo_select.options[vehiculo_select.selectedIndex].textContent).split('-');

        vehiculoActual = datos[0];
        const repartidorId = datos[1];

        modalVehiculo.textContent = vehiculoActual;
        modalRepartidor.textContent = repartidorId;

        // Mostrar modal
        modal.classList.remove("hidden");
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
        vehiculo_select.options[vehiculo_select.selectedIndex].remove();
        let option = document.createElement('option');
        option.value = vehiculoActual + '-' + repartidorSeleccionado_id; // Temporalmente, sin ID real
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
        if (valor === placa) {
            return true; // La placa ya existe
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