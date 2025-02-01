document.addEventListener('DOMContentLoaded', () => {
    const divbars = document.getElementById('audioBars');
    const bars = document.querySelectorAll('#audioBars > div');
    const buscador = document.getElementById('buscador');
    const contenedorResultados = document.getElementById('contenedorResultados');
    const volvercolores = document.getElementById('volvercolores');
    const botonp = document.getElementById('botonp');
    const buttoncolor = document.getElementById('button-color');
    if (volvercolores) {
        volvercolores.addEventListener('click', () => {
            window.location.reload();
        });
    }
    // Ocultar el mensaje después de 5 segundos
    setTimeout(() => {
        const mensaje = document.getElementById('mensaje');
        if (mensaje) {
            mensaje.style.display = 'none';
        }
    }, 10000); // 5000 milisegundos = 5 segundos
    // Duración en milisegundos (5 segundos)
    const duracion = 3000;

    // Intervalo entre cada ejecución (por ejemplo, 1 segundo)
    const intervalo = 1000;

    // Comienza la ejecución repetida

    if (buscador) {
        buscador.addEventListener('keyup', (e) => {
            if (e.target.value.length >= 3) {
                // Detén la ejecución después de la duración especificada
                setTimeout(() => {
                    setInterval(mostrarAnimacionBuscando(), intervalo);
                    clearInterval(setInterval(mostrarAnimacionBuscando(), intervalo));
                }, duracion);
                ocultarAnimacionBuscando();
                buscarEmpresas(e.target.value);

            } else if (e.target.value.length == 0) {
                ocultarAnimacionBuscando();
            }
        });

    }

    async function buscarEmpresas(filtro) {
        try {
            const response = await fetch(`/pidelo/buscar-empresas?filtro=${encodeURIComponent(filtro)}`);
            if (!response.ok) {
                throw new Error("Error al obtener las empresas");
            }
            const empresas = await response.json();
            renderizarEmpresas(empresas);

        } catch (error) {
            console.error(error);
        }
    }
    function renderizarEmpresas(empresas) {
        const contenedor = document.getElementById('contenedorResultados'); // Asegúrate de tener este contenedor en el HTML
        contenedor.innerHTML = ''; // Limpia los resultados anteriores

        if (empresas.length === 0) {
            contenedor.innerHTML = '<p>No se encontraron empresas.</p>';
            return;
        }

        empresas.forEach(empresa => {
            const empresaDiv = document.createElement('div');
            empresaDiv.className = 'empresa-item flex items-start gap-4 p-4 bg-white shadow-md rounded-md';

            // Imagen pequeña
            const img = document.createElement('img');
            img.src = empresa.logo || 'https://via.placeholder.com/50'; // Usa un logo de respaldo si no hay imagen
            img.alt = empresa.nombre;
            img.className = 'w-12 h-12 rounded-full';

            // Información de la empresa
            const infoDiv = document.createElement('div');
            infoDiv.className = 'flex flex-col';

            const nombre = document.createElement('h3');
            nombre.textContent = empresa.nombre;
            nombre.className = 'text-lg font-bold';

            const direccion = document.createElement('p');
            direccion.textContent = empresa.direccion || 'Dirección no disponible';
            direccion.className = 'text-sm text-gray-600';

            const serviciosDiv = document.createElement('div');
            serviciosDiv.className = 'flex flex-wrap gap-2 mt-2';

            (empresa.servicios || []).forEach(servicio => {
                const tag = document.createElement('span');
                tag.textContent = servicio;
                tag.className = 'inline-flex items-center px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded-full';
                const icon = document.createElement('i');
                icon.className = 'fas fa-tag mr-1'; // Icono Font Awesome
                tag.prepend(icon); // Agrega el icono al inicio del tag
                serviciosDiv.appendChild(tag);
            });

            // Agregar todo al contenedor de información
            infoDiv.appendChild(nombre);
            infoDiv.appendChild(direccion);
            infoDiv.appendChild(serviciosDiv);

            // Agregar imagen e información al div de la empresa
            empresaDiv.appendChild(img);
            empresaDiv.appendChild(infoDiv);

            // Agregar la empresa al contenedor principal
            contenedor.appendChild(empresaDiv);
        });
    }

    if (buttoncolor) {
        buttoncolor.addEventListener('input', function () {
            botonp.setAttribute('style', 'background-color: ' + this.value +
                ' !important');
        });
    }

    

    setInterval(animateBars, 300);

    function animateBars() {
        bars.forEach((bar) => {
            const newHeight = Math.random() * 1 + 1; // Altura entre 4 y 20 (rem)
            bar.style.height = `${newHeight}rem`;
        });
    }

    function ocultarAnimacionBuscando() {
        divbars.classList.remove('flex');
        divbars.classList.add('hidden');
    }

    function mostrarAnimacionBuscando() {
        contenedorResultados.classList.remove('hidden');
        divbars.classList.remove('hidden');
        divbars.classList.add('flex');
    }
    
});
