import Swal from "sweetalert2";

const form_nuevo_cupon = document.getElementById('form_nuevo_cupon');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const tabla_cupones = document.getElementById('tabla_cupones');
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