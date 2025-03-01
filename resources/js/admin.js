import Swal from "sweetalert2";
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const modalnuevousuario = document.getElementById('modalnuevousuario');
const btnnuevousuario = document.getElementById('btnnuevousuario');
const closemodalusuario = document.getElementById('closemodalusuario');
const nuevousuarioadmin = document.getElementById('nuevousuarioadmin');
const dominio = window.location.pathname;




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
                Swal.fire({
                    title: 'Confirmación',
                    text: result.mensaje,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    customClass: {
                        timerProgressBar: 'bg-green-500',

                    }

                })

            })
            .catch(error => {
                mensajeError(error.message);
            });
    })
}
// Obtén todos los botones de estado
const formcambiarestado = document.querySelectorAll('.formcambiarestado');
// Itera sobre todos los botones y agrega el evento de clic
formcambiarestado.forEach(btn => {
    btn.addEventListener('submit', (event) => {
        event.preventDefault();
        const boton = event.target;
        const disparador = boton.querySelector("button[type='submit']");
        const estado = disparador.closest('.usuariosadmin').querySelector('.usuario_estado');
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
                Swal.fire({
                    title: 'Confirmación',
                    text: result.mensaje,
                    icon: 'success',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 2000,
                    customClass: {
                        timerProgressBar: 'bg-green-500'
                    }
                })
                // Cambiar el estado del botón en función del estado actualizado
                if (result.nuevo_estado) {
                    estado.textContent = 'Activo';
                    disparador.classList.remove('bg-red-500');
                    disparador.classList.add('bg-green-500');
                    disparador.innerHTML = '<i class="fas fa-ban"></i> Inhabilitar';
                } else {
                    estado.textContent = 'Inactivo';
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

const slider = document.getElementById("slider");
const slides = document.querySelectorAll("#slider > div");
const prev = document.getElementById("prev");
const next = document.getElementById("next");

let currentIndex = 0;
let itemsPerView = window.innerWidth >= 768 ? 2 : 1;

const updateSlider = () => {
    let percentage = (currentIndex * 100) / itemsPerView;
    slider.style.transform = `translateX(-${percentage}%)`;
};

if (next) {
    next.addEventListener("click", () => {
        if (currentIndex < slides.length - itemsPerView) {
            currentIndex++;
            updateSlider();
        }
    });
}

if (prev) {
    prev.addEventListener("click", () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });
}

window.addEventListener("resize", () => {
    itemsPerView = window.innerWidth >= 768 ? 2 : 1;
    updateSlider();
});



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