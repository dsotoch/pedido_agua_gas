import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", function () {
    const sortable_list = document.getElementById('sortable-list');
    const guardarOrden = document.getElementById("guardarOrden");
    const empresa_id = document.getElementById('empresa_id');
    const token = document.querySelector('meta[name="token"]').getAttribute('content');

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