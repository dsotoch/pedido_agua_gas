import Swal from "sweetalert2";

const form_nuevo_cupon = document.getElementById('form_nuevo_cupon');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const tabla_cupones = document.getElementById('tabla_cupones');
const toggleCupon = document.getElementById('toggleCupon');
const btnAplicarCupon = document.getElementById('btnAplicarCupon');
let total = document.querySelector(".total");
const contenedor_cupones = document.getElementById('contenedor_cupones');
const span_cupon = document.getElementById('span_cupon');
const descuento = document.getElementById('descuento');
let usos_restantes_cliente = 1;
let usos_restantes_global = 1;
function mostrar_contenedor_cupones() {
    contenedor_cupones.classList.remove('hidden');
    contenedor_cupones.classList.add('flex');
}
function ocultar_contenedor_cupones() {
    contenedor_cupones.classList.remove('flex');
    contenedor_cupones.classList.add('hidden');
}

if (toggleCupon) {
    toggleCupon.addEventListener('click', function () {
        let cuponForm = document.getElementById('cuponForm');
        let iconoFlecha = document.getElementById('iconoFlecha');

        cuponForm.classList.toggle('hidden');
        iconoFlecha.classList.toggle('rotate-180'); // Gira la flecha
    });
}
if (btnAplicarCupon) {
    btnAplicarCupon.addEventListener('click', async function () {
        let codigo = document.getElementById('codigoCupon').value.trim();
        let total_valor = total.value.match(/S\/\s*(\d+(\.\d+)?)/); // Captura el número después de "S/"

        if (total) {
            total_valor = total_valor[1].trim();
            if (usos_restantes_cliente <= 0 || usos_restantes_global <= 0) {
                Swal.fire(
                    {
                        title: 'Ocurrió un error',
                        text: "Este Cupón ya no se puede usar.",
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        customClass: {
                            timerProgressBar: 'bg-red-500 h2 rounded-md'
                        }

                    }
                )
            } else {
                aplicar_Cupon(codigo, total_valor);
            }
        } else {
            Swal.fire(
                {
                    title: 'Ocurrió un error',
                    text: "Por favor, ingresa un código de cupón válido.",
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        timerProgressBar: 'bg-red-500 h2 rounded-md'
                    }

                }
            )
        }
    });
}

async function aplicar_Cupon(cupon, total_valor) {
    try {
        let datos = { cupon: cupon, total: total_valor }
        const response = await fetch('/calcular-total', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(datos)
        })

        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.error);
        }
        mostrar_contenedor_cupones();
        total.value = "Total S/" + parseFloat(result.nuevo_total).toFixed(2);
        span_cupon.value = " " + "#" + cupon;
        descuento.value = " " + parseFloat(result.descuento).toFixed(2);
        cuponForm.classList.toggle('hidden');
        iconoFlecha.classList.toggle('rotate-180');
        usos_restantes_global = result.usos_restantes_global;
        usos_restantes_cliente = usos_restantes_cliente;

        Swal.fire(
            {
                title: 'Confirmación',
                text: result.mensaje,
                icon: 'success',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    timerProgressBar: 'bg-green-500 h2 rounded-md'
                }

            }
        )

    } catch (error) {
        ocultar_contenedor_cupones();
        Swal.fire(
            {
                title: 'Ocurrió un error',
                text: error,
                icon: 'error',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    timerProgressBar: 'bg-red-500 h2 rounded-md'
                }

            }
        )
    }
}


if (form_nuevo_cupon) {
    form_nuevo_cupon.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form_nuevo_cupon);
        let datos = {};

        formData.forEach((element, key) => {
            datos[key] = element;
        });

        try {
            const response = await fetch(form_nuevo_cupon.getAttribute('action'), {
                method: form_nuevo_cupon.getAttribute('method'),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify(datos)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.mensaje || "Ocurrió un error al procesar la solicitud");
            }

            Swal.fire({
                title: '¡Cupón creado!',
                icon: 'success',
                text: result.mensaje,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    timerProgressBar: 'bg-green-500 h-2 rounded-md'
                }
            });

            setTimeout(() => {
                window.location.reload();
            }, 3000);

        } catch (error) {
            console.error("Error:", error); // Para depuración en la consola

            Swal.fire({
                title: 'Ocurrió un error',
                icon: 'error',
                text: error.message || "Error desconocido",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    timerProgressBar: 'bg-red-500 h-2 rounded-md'
                }
            });
        }
    });
}

if (tabla_cupones) {
    tabla_cupones.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (e.target.classList.contains('form_eliminar_cupon')) {
            const formData = new FormData(e.target);
            let datos = {};

            formData.forEach((element, key) => {
                datos[key] = element;
            });

            try {
                const response = await fetch(e.target.getAttribute('action'), {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify(datos)
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.mensaje || "Ocurrió un error al procesar la solicitud");
                }

                Swal.fire({
                    title: '¡Cupón eliminado!',
                    icon: 'success',
                    text: result.mensaje,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        timerProgressBar: 'bg-green-500 h-2 rounded-md'
                    }
                });

                setTimeout(() => {
                    window.location.reload();
                }, 3000);

            } catch (error) {
                console.error("Error:", error); // Para depuración en la consola

                Swal.fire({
                    title: 'Ocurrió un error',
                    icon: 'error',
                    text: error.message || "Error desconocido",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        timerProgressBar: 'bg-red-500 h-2 rounded-md'
                    }
                });
            }
        }

    });
}