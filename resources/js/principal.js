import Swal from "sweetalert2";
import { eliminarFavorito, esFavoritoPrincipal, esPaginaPredeterminada_Principal, guardarFavorito, guardarPaginaPredeterminada, obtenerFavoritos } from "./cookies";

document.addEventListener('DOMContentLoaded', () => {
    const buscador = document.getElementById('buscador');
    const contenedorResultados = document.getElementById('contenedorResultados');
    const volvercolores = document.getElementById('volvercolores');
    const botonp = document.getElementById('botonp');
    const buttoncolor = document.getElementById('button-color');
    const contenedor_empresas = document.getElementById('empresas');
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


    const duracion = 1000;

    // Comienza la ejecución repetida

    if (buscador) {
        buscador.addEventListener('input', (e) => {
            if (e.target.value.length >= 3) {
                mostrarAnimacionBuscando();
                setTimeout(() => {
                    ocultarAnimacionBuscando();
                    buscarEmpresas(e.target.value);
                }, duracion);


            } else if (!e.target.value) {
                contenedor_empresas.classList.add('hidden');
                contenedor_empresas.innerHTML = `
                    <div class="absolute top-0 shadow-2xl w-full bg-white rounded-md p-2 " id="contenedor_bars">
                        <!-- Aseguramos que el div con audioBars se posicione dentro del contexto relativo -->
                        <div id="audioBars"
                            class="absolute flex top-0 left-0   items-center space-x-1 rounded-md p-3 justify-center  w-full bg-white">
                            <div class="w-2 bg-naranja h-8"></div>
                            <div class="w-2 bg-naranja h-12"></div>
                            <div class="w-2 bg-naranja h-6"></div>
                            <div class="w-2 bg-naranja h-10"></div>
                            <div class="w-2 bg-naranja h-7"></div>
                        </div>

                    </div>
                `;

            } else {
                if (e.target.value.length < 3) {
                    contenedor_empresas.classList.add('hidden');
                    contenedor_empresas.innerHTML = `
                        <div class="absolute top-0 shadow-2xl w-full bg-white rounded-md p-2 " id="contenedor_bars">
                            <!-- Aseguramos que el div con audioBars se posicione dentro del contexto relativo -->
                            <div id="audioBars"
                                class="absolute flex top-0 left-0   items-center space-x-1 rounded-md p-3 justify-center  w-full bg-white">
                                <div class="w-2 bg-naranja h-8"></div>
                                <div class="w-2 bg-naranja h-12"></div>
                                <div class="w-2 bg-naranja h-6"></div>
                                <div class="w-2 bg-naranja h-10"></div>
                                <div class="w-2 bg-naranja h-7"></div>
                            </div>
    
                        </div>
                    `;
                }
            }
        });

    }

    async function buscarEmpresas(filtro) {
        if (filtro != '') {
            try {
                const response = await fetch(`/distribuidora/buscar-empresas/${encodeURIComponent(filtro)}`);
                if (!response.ok) {
                    throw new Error("Error al obtener las empresas");
                }
                const empresas = await response.json();
                renderizarEmpresas(empresas);

            } catch (error) {
            }
        }
    }
    async function renderizarEmpresas(empresas) {
        const contenedor = contenedor_empresas;
        contenedor.innerHTML = ''; // Limpia los resultados anteriores
        contenedor.classList.remove('hidden');

        // Crear el párrafo de resultados
        const pResultados = document.createElement('p');
        pResultados.id = 'ct_resultados';
        pResultados.className = 'text-color-titulos-entrega text-[15px] p-2 hidden';

        // Crear el span dentro del párrafo
        const spanCantidad = document.createElement('span');
        spanCantidad.id = 'cantidad-resultados';
        spanCantidad.textContent = '0'; // Valor inicial

        // Agregar el texto después del span
        pResultados.appendChild(spanCantidad);
        pResultados.appendChild(document.createTextNode(' Resultado(s)'));
        contenedor.appendChild(pResultados); // Agregar el contador al contenedor

        if (empresas.length === 0) {
            // Crear el mensaje de "No se encontraron resultados"
            const mensaje = document.createElement('p');
            mensaje.className = 'text-base text-color-titulos-entrega p-3 ';
            mensaje.textContent = 'No encontramos distribuidores para tu búsqueda.';
            contenedor.appendChild(mensaje);
            return;
        }

        // Mostrar el contador y actualizar la cantidad de resultados
        pResultados.classList.remove('hidden');
        spanCantidad.textContent = empresas.length;

        for (const empresa of empresas) {

            const empresaDiv = document.createElement('div');
            empresaDiv.className = 'empresa-item  flex items-center justify-between gap-4 p-4 bg-white shadow-md rounded-md cursor-pointer';

            // Construir la URL de destino
            const urlDestino = window.location.origin + '/' + empresa.dominio;

            // Agregar evento de clic para redireccionar
            empresaDiv.addEventListener('click', () => {
                window.location.href = urlDestino;
            });

            // Contenedor de información
            const infoDiv = document.createElement('div');
            infoDiv.className = 'flex items-start gap-4';

            // Imagen de la empresa
            const img = document.createElement('img');
            img.src = 'storage/' + empresa.logo_vertical;
            img.alt = empresa.nombre;
            img.className = 'w-12 h-12 rounded-full';

            // Información de la empresa
            const textDiv = document.createElement('div');
            textDiv.className = 'flex flex-col';

            const nombre = document.createElement('h3');
            nombre.textContent = empresa.nombre;
            nombre.className = 'text-lg font-bold text-color-titulos-entrega';

            const direccion = document.createElement('p');
            direccion.textContent = empresa.direccion || 'Dirección no disponible';
            direccion.className = 'text-[12px] text-color-titulos-entrega';

            // Contenedor de servicios
            const serviciosDiv = document.createElement('div');
            serviciosDiv.className = 'flex flex-wrap gap-2 mt-2';

            // Convertir `empresa.servicios` en un array válido
            const servicios = JSON.parse(empresa.servicios || '[]');

            servicios.forEach(servicio => {
                const tag = document.createElement('span');
                tag.textContent = servicio;
                tag.className = 'inline-flex items-center px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded-full';

                const icon = document.createElement('i');
                icon.className = 'fas fa-tag mr-1';
                tag.prepend(icon);

                serviciosDiv.appendChild(tag);
            });

            // Agregar información al contenedor de texto
            textDiv.appendChild(nombre);
            textDiv.appendChild(direccion);
            textDiv.appendChild(serviciosDiv);

            // Agregar imagen e información al div de empresa
            infoDiv.appendChild(img);
            infoDiv.appendChild(textDiv);

            // 🔹 Contenedor de botones (lado derecho)
            const botonesDiv = document.createElement('div');
            botonesDiv.className = 'flex gap-2';

            // ⭐ Botón de Favoritos
            const btnFavorito = document.createElement('button');
            btnFavorito.className = `hover:text-yellow-500 ${await esFavoritoPrincipal(empresa.dominio) ? 'text-yellow-500' : 'text-gray-400'}`;
            btnFavorito.innerHTML = '<i class="fas fa-star"></i>';
            btnFavorito.addEventListener('click', async (e) => {
                e.stopPropagation(); // Evita redirección
                const empresaDominio = empresa.dominio; // Asegurar que empresa.dominio está disponible

                const favorito = await esFavoritoPrincipal(empresaDominio);

                if (!favorito) {
                    if (guardarFavorito(id_usuario_autenticado.textContent.trim(), empresaDominio)) {
                        btnFavorito.classList.add('text-yellow-500'); // Cambia color al activar
                        await obtenerFavoritos();

                    } else {
                        btnFavorito.classList.remove('text-yellow-500'); // Cambia color al activar
                        btnFavorito.classList.add('text-gray-400'); // Cambia color al activar

                    }
                } else {
                    await eliminarFavorito(empresa.dominio);
                    btnFavorito.classList.remove('text-yellow-500'); // Cambia color al activar
                    btnFavorito.classList.add('text-gray-400'); // Cambia color al activar
                    await obtenerFavoritos();
                }
            });

            // ✅ Botón de Predeterminado
            const btnPredeterminado = document.createElement('button');
            btnPredeterminado.className = `hover:text-green-500 ${esPaginaPredeterminada_Principal(empresa.dominio) ? 'text-green-500' : 'text-gray-400'} btn_pred`;
            btnPredeterminado.dataset.dominio = empresa.dominio;
            btnPredeterminado.innerHTML = '<i class="fas fa-check-circle "></i>';
            btnPredeterminado.title = 'Elegir como Predeterminado';
            btnPredeterminado.addEventListener('click', (e) => {
                e.stopPropagation(); // Evita redirección
                document.querySelectorAll('.btn_pred').forEach(icono => {
                    if (icono !== btnPredeterminado) {  // Evita afectar el botón actual
                        icono.classList.remove('text-green-500');
                    }
                });
                let baseUrl = window.location.origin;
                let url = `${baseUrl}/${empresa.dominio}`; // Construcción correcta de la URL
                if (!guardarPaginaPredeterminada(url, empresa.dominio, 'principal')) {
                    btnPredeterminado.classList.remove('text-green-500');
                } else {
                    btnPredeterminado.classList.add('text-green-500'); // Activa este

                }
                console.log('funcionando predeterminado');
            });

            // Agregar botones al contenedor de botones
            botonesDiv.appendChild(btnFavorito);
            botonesDiv.appendChild(btnPredeterminado);

            // Agregar elementos al div principal
            empresaDiv.appendChild(infoDiv);
            empresaDiv.appendChild(botonesDiv); // Botones a la derecha

            contenedor.appendChild(empresaDiv);
        };
    }



    if (buttoncolor) {
        buttoncolor.addEventListener('input', function () {
            botonp.setAttribute('style', 'background-color: ' + this.value +
                ' !important');
        });
    }



    setInterval(animateBars, 300);

    function animateBars() {
        const bars = document.querySelectorAll('#audioBars div'); // Seleccionar todas las barras dentro de #audioBars
        bars.forEach((bar) => {
            const newHeight = Math.random() * 1.5 + 0.5; // Altura entre 0.5 y 2 rem
            bar.style.height = `${newHeight}rem`;
        });
    }

    function ocultarAnimacionBuscando() {

        contenedor_empresas.classList.add('hidden');
    }

    function mostrarAnimacionBuscando() {
        contenedor_empresas.classList.remove('hidden');
    }

});
