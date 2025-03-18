import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", function () {
    const sortable_list = document.getElementById('sortable-list');
    const guardarOrden = document.getElementById("guardarOrden");
    const empresa_id = document.getElementById('empresa_id');
    const token = document.querySelector('meta[name="token"]').getAttribute('content');
    const btn_guardar_horario_conf = document.getElementById('btn_guardar_horario_conf');
    const select_dia_conf = document.getElementById('select_dia_conf');
    const hora_inicio_conf = document.getElementById('hora_inicio_conf');
    const hora_fin_conf = document.getElementById('hora_fin_conf');
    const tabla_horario_conf = document.getElementById('tabla_horario_conf');

    if (tabla_horario_conf) {
        tabla_horario_conf.addEventListener('click', (e) => {
            if (e.target.classList.contains("btn-modificar")) {
                const columna = e.target.closest("td");
                const input_estado = columna.querySelector('.input_estado');
                let estado_actual = input_estado.value;
                if (estado_actual == 1) {
                    input_estado.value = 0;
                    e.target.classList.add('bg-red-500');
                    e.target.classList.remove('bg-green-500'); // Asegura que solo tenga el color rojo
                    e.target.innerHTML = `<i class="fas fa-check-circle"></i> Habilitar`;

                } else {
                    input_estado.value = 1;
                    e.target.classList.add('bg-green-500');
                    e.target.classList.remove('bg-red-500'); // Asegura que solo tenga el color verde
                    e.target.innerHTML = `<i class="fas fa-ban"></i> Inhabilitar`;

                }

            }
        })
    }
    if (btn_guardar_horario_conf) {
        btn_guardar_horario_conf.addEventListener('click', () => {
            if (hora_inicio_conf.value == '' || hora_fin_conf.value == '') {
                mensaje_error('Datos Faltantes', "Por favor completa todos los campos.");
                return;
            }

            const tbody = tabla_horario_conf.querySelector('tbody');

            // Crear una nueva fila (tr)
            let fila = document.createElement("tr");
            fila.classList.add('border-b-2');

            // Crear las celdas con inputs
            let tdDia = document.createElement("td");
            tdDia.classList.add('p-2', 'text-center');
            let inputDia = document.createElement("input");
            inputDia.type = "text";
            inputDia.name = "dia[]";
            inputDia.value = select_dia_conf.value;
            inputDia.classList.add('border', 'p-2', 'text-center', 'w-full');
            inputDia.readOnly = true; // Para evitar edición manual
            tdDia.appendChild(inputDia);

            let tdHoraInicio = document.createElement("td");
            tdHoraInicio.classList.add('p-2', 'text-center');
            let inputHoraInicio = document.createElement("input");
            inputHoraInicio.type = "time";
            inputHoraInicio.name = "hora_inicio[]";
            inputHoraInicio.value = hora_inicio_conf.value;
            inputHoraInicio.classList.add('border', 'p-2', 'text-center', 'w-full');
            tdHoraInicio.appendChild(inputHoraInicio);

            let tdHoraFin = document.createElement("td");
            tdHoraFin.classList.add('p-2', 'text-center');
            let inputHoraFin = document.createElement("input");
            inputHoraFin.type = "time";
            inputHoraFin.name = "hora_fin[]";
            inputHoraFin.value = hora_fin_conf.value;
            inputHoraFin.classList.add('border', 'p-2', 'text-center', 'w-full');
            tdHoraFin.appendChild(inputHoraFin);

            let tdAcciones = document.createElement("td");
            tdAcciones.classList.add('p-2', 'text-center');

            // Botón de eliminar
            let btnEliminar = document.createElement("button");
            btnEliminar.classList.add('text-white', 'bg-red-500', 'p-2', 'rounded');
            btnEliminar.textContent = "Eliminar";
            btnEliminar.onclick = function () {
                fila.remove();
            };

            // Agregar botón a la celda de acciones
            tdAcciones.appendChild(btnEliminar);

            // Agregar celdas a la fila
            fila.appendChild(tdDia);
            fila.appendChild(tdHoraInicio);
            fila.appendChild(tdHoraFin);
            fila.appendChild(tdAcciones);

            // Agregar la fila al tbody
            tbody.appendChild(fila);
        });

    }
    if (sortable_list) {
        let sortable = new Sortable(sortable_list, {
            animation: 150,
        });
    }


    if (guardarOrden) {
        guardarOrden.addEventListener("click", function () {
            let orden = [];
            document.querySelectorAll("#sortable-list li").forEach((el, index) => {
                orden.push({ id: el.dataset.id, orden: index + 1 });
            });

            fetch("/mi-cuenta/modificarDiseño", {
                method: "PUT",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": token },
                body: JSON.stringify({ orden: orden, empresa: empresa_id.value })
            }).then(response => response.json())
                .then(data => mensaje_exito('Operación Exitosa', data.mensaje));
        });
    }
});

function mensaje_exito(titulo, texto) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'success',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false,
        customClass: {
            timerProgressBar: 'bg-green-500 h-2 rounded'
        }
    })
}
function mensaje_error(titulo, texto) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'error',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false,
        customClass: {
            timerProgressBar: 'bg-red-500 h-2 rounded'
        }
    })
}