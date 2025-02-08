import Swal from "sweetalert2";

const formproductoadmincrear = document.getElementById('formproductoadmincrear');
const divdetallespromocionesproductoadmin = document.getElementById('divdetallespromocionesproductoadmin');
const divdetallespromocionesadmin = document.getElementById('divdetallespromocionesadmin');
const btnpromocionadmin = document.getElementById('btnpromocionadmin');
let promociones = [];
const preciopromocion = document.getElementById('preciopromocion');
const unidades = document.getElementById('unidades');
const btnresetearpromociones = document.getElementById('btnresetearpromociones');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const productosadmin = document.querySelectorAll('.productosadmin');
const contenedorproductos = document.getElementById('contenedorproductos');
const mensaje_sin_productos_registrados = document.getElementById('mensaje_sin_productos_registrados');
const input_productosPorCada = document.getElementById('productosPorCada');
const select_productosGratis = document.getElementById('productosGratis');
if (formproductoadmincrear) {
    formproductoadmincrear.addEventListener('submit', (event) => {
        event.preventDefault();
        if (input_productosPorCada.value > 0) {
            if (select_productosGratis.value == "") {
                mensajeError("Por Favor seleccione el producto gratis.");
                return '';
            }
        }
        const datas = new FormData(formproductoadmincrear);
        var data = {};
        datas.forEach((value, key) => {
            data[key] = value;
        });
        data['promociones'] = promociones;
        const actionURL = formproductoadmincrear.getAttribute('action');
        const submitButton = formproductoadmincrear.querySelector('button[type="submit"]');

        submitButton.disabled = true;

        fetch(actionURL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(data),
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
                if (promociones.length > 0) {
                    divdetallespromocionesproductoadmin.classList.remove('flex');
                    divdetallespromocionesproductoadmin.classList.add('hidden');
                }
                submitButton.disabled = false;

                // Extraer los valores necesarios de "data"
                const descripcion = data['nombre'] + " " + data['descripcion'];
                const precio = data['precio'];
                // Llamar a agregarProductoDOM con los valores correctos
                agregarProductoDOM(
                    result.id,
                    descripcion,
                    precio,
                    result.comercializable
                );
                if (!result.comercializable) {
                    agregarProductoASelect(descripcion);
                }
                if (mensaje_sin_productos_registrados && !mensaje_sin_productos_registrados.classList.contains('hidden')) {
                    mensaje_sin_productos_registrados.classList.add('hidden');
                }
                limpiardatos(formproductoadmincrear);
            })
            .catch(error => {
                mensajeError(error.message);
                submitButton.disabled = false;
            });
    });
}
function agregarProductoASelect(nombreProducto) {

    // Crear una nueva opción
    const option = document.createElement("option");
    option.value = nombreProducto; // Asignar el valor
    option.textContent = nombreProducto; // Asignar el texto visible

    // Agregar la opción al select
    select_productosGratis.appendChild(option);
}
function agregarProductoDOM(id, descripcion, precio, comercializable) {
    // Crear el nuevo elemento
    const nuevo = document.createElement('div');
    nuevo.classList.add('pro', 'space-y-2', 'font-base', 'flex', 'items-center', 'justify-between', 'border-b', 'pb-4', 'productosadmin');
    // Definir el contenido HTML
    nuevo.innerHTML = `
      <div class="space-y-2">
          <h3 class="text-lg font-semibold text-color-titulos-entrega">
              <i class="fas fa-box text-color-titulos-entrega"></i> #${id}
          </h3>
          <p class="text-color-titulos-entrega">
              <i class="fas fa-tags text-color-titulos-entrega"></i>  ${descripcion}
          </p>
          <p class="text-color-titulos-entrega font-bold">
              <i class="fas fa-dollar-sign text-color-titulos-entrega"></i> Precio: S/${precio}
          </p>
         <p class="text-color-titulos-entrega">
            <i class="fas fa-tags text-color-titulos-entrega"></i> Promociones:
            ${promociones.map(pro => `S/${pro.preciopromocion} x ${pro.unidades} Un.`).join(' | ')}
        </p>
        <p class="text-color-titulos-entrega"><i class="fa-solid fa-cart-shopping"></i> Disponible para la venta:
                                            ${comercializable ? 'SI' : 'NO'}
        </p>
        
          <form data-id="${id}" action="/eliminar/${id}" method="post" class="flex justify-start">
              <button type="submit"
                  class="m-2 p-3 rounded border-2 text-base border-color-titulos-entrega text-color-titulos-entrega ">
                  <i class="fas fa-trash"></i> Eliminar
              </button>
          </form>
      </div>
  `;
    // Agregar el elemento al contenedor
    contenedorproductos.append(nuevo);

    // Asignar evento al formulario recién agregado
    const formulario = nuevo.querySelector('form');
    formulario.addEventListener('submit', async (event) => {
        event.preventDefault();
        const url = formulario.action;
        const productoElemento = formulario.closest('.productosadmin');
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
            });

            if (response.ok) {
                const result = await response.json();
                mensajeExito(result.mensaje);
                productoElemento.remove();
            } else {
                const error = await response.json();
                mensajeError(error.message);
            }
        } catch (error) {
            // Mostrar el error exacto en la consola
            console.error('Error en el catch:', error);

            // Incluir el error en el mensaje
            mensajeError(`Ocurrió un error al intentar eliminar el producto. Detalles: ${error.message || error}`);
        }

    });

}

if (btnresetearpromociones) {
    btnresetearpromociones.addEventListener('click', () => {
        promociones.length = 0;
        divdetallespromocionesproductoadmin.classList.remove('flex');
        divdetallespromocionesproductoadmin.classList.add('hidden');
        divdetallespromocionesadmin.innerHTML = '';
    });
}

function limpiardatos(form) {
    divdetallespromocionesadmin.innerHTML = '';
    promociones.length = 0;
    form.reset();
}
if (btnpromocionadmin) {
    btnpromocionadmin.addEventListener('click', () => {
        if (unidades.value.trim() == '') {
            mensajeError("Por favor seleccione una Cantidad!");
            return;
        }
        if (preciopromocion.value.trim() == '') {
            mensajeError("Por favor Ingrese un Precio Valido!");
            return;
        }

        var newDato = { "unidades": unidades.value.trim(), "preciopromocion": preciopromocion.value.trim() };
        promociones.push(newDato);
        divdetallespromocionesproductoadmin.classList.remove('hidden');
        divdetallespromocionesproductoadmin.classList.add('flex');
        var nuevoElemento = document.createElement('div');
        nuevoElemento.className = "grid space-y-2";
        nuevoElemento.innerHTML = `
            <p class="text-sm font-medium text-gray-700">Nro Unidades:  ${unidades.value.trim()}</p>
            <p class="text-sm font-medium text-gray-700">Precio Promocional por Unidad:  S/${preciopromocion.value.trim()}</p>
            <hr>
        `;
        divdetallespromocionesadmin.appendChild(nuevoElemento);
    });

}
function eliminarProductos() {
    if (productosadmin) {
        productosadmin.forEach((producto) => {
            const formulario = producto.querySelector('form');

            formulario.addEventListener('submit', async (event) => {
                event.preventDefault();
                // Obtener el ID del producto desde un campo oculto o atributo
                const disparador = event.target;
                const url = disparador.action;

                // Seleccionar el contenedor que corresponde al producto
                const productoElemento = disparador.closest('.productosadmin');
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json', // Define el contenido como JSON
                        'X-CSRF-TOKEN': token,
                    },
                }).then(response => {
                    // Verificar el código de estado
                    if (response.status !== 201) {
                        // Obtener el mensaje de error del servidor
                        return response.text().then((text) => {
                            throw new Error(text); // Lanza un error con el mensaje del servidor
                        });
                    }
                    return response.json(); // Convertir la respuesta exitosa a JSON
                })
                    .then(result => {
                        mensajeExito(result.mensaje); // Manejo del resultado exitoso
                        productoElemento.remove();

                    })
                    .catch(error => {
                        mensajeError(error.message); // Mostrar mensaje de error

                    });

            });
        });
    }

}
eliminarProductos();

//Mensaje de Error
function mensajeError(texto) {
    Swal.fire({
        title: 'Ocurrio un Error!',
        text: texto,
        icon: 'error',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
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
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            timerProgressBar: 'bg-green-500 h-2 rounded-md'
        }
    })
}