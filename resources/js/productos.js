import Swal from "sweetalert2";

const formproductoadmincrear = document.getElementById('formproductoadmincrear');
const divdetallespromocionesproductoadmin = document.getElementById('divdetallespromocionesproductoadmin');
const divdetallespromocionesadmin = document.getElementById('divdetallespromocionesadmin');
const btnpromocionadmin = document.getElementById('btnpromocionadmin');
let promociones = [];
let edit_promociones_precios = [];
const preciopromocion = document.getElementById('preciopromocion');
const unidades = document.getElementById('unidades');
const btnresetearpromociones = document.getElementById('btnresetearpromociones');
const token = document.querySelector('meta[name="token"]').getAttribute('content');
const productosadmin = document.querySelectorAll('.productosadmin');
const contenedorproductos = document.getElementById('contenedorproductos');
const mensaje_sin_productos_registrados = document.getElementById('mensaje_sin_productos_registrados');
const input_productosPorCada = document.getElementById('productosPorCada');
const select_productosGratis = document.getElementById('productosGratis');
const btn_cerrar_modal_editar_producto = document.querySelector("#cerrarModalEditar");
const formEditarProducto = document.getElementById('formEditarProducto');
const btnEliminarPromociones = document.getElementById('edit-btnEliminarPromociones');
const btnPromocion = document.getElementById('edit-btnPromocion');
if (formEditarProducto) {
    formEditarProducto.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = new FormData(formEditarProducto);
        let body = {};

        data.forEach((value, key) => {
            body[key] = value;
        });

        body['promociones'] = edit_promociones_precios;
        body['_method'] = 'PUT'; // Laravel espera esto cuando usamos POST en lugar de PUT

        const response = await fetch(formEditarProducto.getAttribute('action'), {
            method: 'POST', // Laravel requiere POST con _method=PUT en formularios
            headers: {
                'Content-Type': 'application/json', // Corregido el header
                'X-CSRF-TOKEN': token // Corregido el nombre del token
            },
            body: JSON.stringify(body)
        });

        const resp = await response.json();

        if (response.ok) {
            mensajeExito(resp.mensaje);
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            console.log(resp.error);
            mensajeError(resp.mensaje);
        }
    });

}

if (btn_cerrar_modal_editar_producto) {
    // Cerrar el modal
    btn_cerrar_modal_editar_producto.addEventListener("click", function () {
        document.getElementById("modalEditarProducto").classList.remove("flex");
        document.getElementById("modalEditarProducto").classList.add("hidden");
        document.getElementById("edit-DetallesPromocionesPrecios").innerHTML = '';

        edit_promociones_precios.length = 0;
    });
}
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
            const btn_editar = producto.querySelectorAll('.editar-producto');

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
            if (btn_editar) {
                btn_editar.forEach(button => {
                    button.addEventListener("click", function () {
                        document.getElementById("edit-id").value = this.dataset.id;
                        document.getElementById("edit-descripcion").value = this.dataset.descripcion;
                        document.getElementById("edit-precio").value = this.dataset.precio;
                        document.getElementById("edit-comercializable").checked = this.dataset.comercializable == "1";
                        const promociones_unitarias = JSON.parse(this.dataset.unitarios || '[]');
                        const por_cada = promociones_unitarias.cantidad;
                        const producto_gratis = promociones_unitarias.producto_gratis == this.dataset.descripcion ? 'mismo' : promociones_unitarias.producto_gratis;
                        const promociones_precios = JSON.parse(this.dataset.promociones || "[]");
                        document.getElementById('edit-productosPorCada').value = por_cada;
                        document.getElementById('edit-productosGratis').value = producto_gratis;
                       
                        let nueva_promocion = null
                        promociones_precios.forEach(element => {
                            nueva_promocion = document.createElement('p');
                            nueva_promocion.innerHTML = `${element.cantidad} Un.  => S/${element.precio_promocional
                                }`;
                            document.getElementById('edit-DetallesPromocionesPrecios').appendChild(nueva_promocion);
                            let promocion_actual = { 'producto': element.producto_id, 'cantidad': element.cantidad, 'precio_promocional': element.precio_promocional }
                            edit_promociones_precios.push(promocion_actual);

                        });

                        if (promociones_precios.length <= 0) {
                            btnEliminarPromociones.classList.add('hidden');
                        } else {
                            btnEliminarPromociones.classList.remove('hidden');

                        }
                        document.getElementById("modalEditarProducto").classList.remove("hidden");
                        document.getElementById("modalEditarProducto").classList.add("flex");

                    });
                });
            }

        });
    }

}
eliminarProductos();

if (btnEliminarPromociones) {
    btnEliminarPromociones.addEventListener('click', (e) => {
        e.target.classList.add('hidden');
        edit_promociones_precios.length = 0;
        document.getElementById('edit-DetallesPromocionesPrecios').innerHTML = '';
    })
}
if (btnPromocion) {
    btnPromocion.addEventListener('click', () => {
        const edit_unidades = document.getElementById('edit-unidades');
        const edit_preciopromocion = document.getElementById('edit-preciopromocion');
        if (edit_unidades.value >= 2 && edit_preciopromocion.value == '') {
            mensajeError("Por favor ingrese el precio promocional.");
            return;
        }
        if (edit_unidades.value == '' && edit_preciopromocion.value != '') {
            mensajeError("Por favor ingrese el numero de unidades para la promocion.");
            return;
        }
        const nuevoProducto = { 'cantidad': edit_unidades.value, 'precio_promocional': edit_preciopromocion.value };
        edit_promociones_precios.push(nuevoProducto);
        const nueva_promocion = document.createElement('p');
        nueva_promocion.innerHTML = `${edit_unidades.value} Un.  => S/${parseFloat(edit_preciopromocion.value).toFixed(2)}`;
        document.getElementById('edit-DetallesPromocionesPrecios').appendChild(nueva_promocion);
    })
}
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