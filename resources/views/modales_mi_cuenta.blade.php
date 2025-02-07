 <!---Modal para Asignar el repartidor-->
 <div id="modalasignarrepartidor" class="fixed z-50 inset-0 bg-gray-800 bg-opacity-50 items-center justify-center hidden">
     <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
         <!-- Modal Header -->
         <div class="flex justify-between items-center border-b pb-3">
             <h3 class="text-lg font-semibold text-color-titulos-entrega">Asignar Pedido a Repartidor</h3>
             <button onclick="document.getElementById('modalasignarrepartidor').classList.add('hidden');document.getElementById('modalasignarrepartidor').classList.remove('flex');" class="text-red-500 hover:scale-150 transform" id="btncerrarmodalrepartidor">
                 <i class="fas fa-times"></i>
             </button>
         </div>

         <!-- Modal Body -->
         <form id="formAsignarRepartidor" action="{{ route('pedido.asignarrepartidor') }}" method="POST" class="mt-4">
             @csrf
             <input type="text" name="pedido_id" id="pedido_id" hidden>
             <div class="mb-4">
                 <label for="repartidor" class="block text-sm font-medium text-gray-600 mb-2">
                     Seleccionar Repartidor
                 </label>
                 <select name="repartidor_id" id="repartidor" required
                     class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none">
                     <option value="" disabled selected>-- Seleccionar --</option>

                 </select>
             </div>
             <!-- Modal Footer -->
             <div class="flex justify-end">
                 <button type="submit"
                     class="border-2  bg-naranja text-white font-semibold text-base py-2 px-4 rounded hover:scale-125">
                     Asignar
                 </button>
             </div>
         </form>
     </div>
 </div>
 <!-- Modal Pago Pedido Repartidor-->
 <div id="paymentModal" class="hidden fixed flex-col inset-0 bg-black bg-opacity-70  items-center justify-center z-50">
     <div class="bg-white rounded text-color-text text-base font-sans  shadow-md w-full mx-auto max-w-md p-6">
         <h2 class="text-xl font-semibold text-center mb-4 font-cabin">Finalizar Pedido <span
                 id="modal_pago_pedido_id"></span></h2>
         <form id="form_metodo_pago_repartidor" method="post" action="{{ route('pedido.cambiarestadopago') }}">
             <input type="text" name="id_pedido" id="id_pedido_modal_pago" hidden>
             <!-- Opciones de pago -->
             <div class="mb-4">
                 <label class="flex items-center space-x-2">
                     <input type="radio" name="paymentMethod" value="yape"
                         class="text-blue-500 focus:ring-blue-500">
                     <span>Pagó con Yape</span>
                 </label>
             </div>
             <div class="mb-4">
                 <label class="flex items-center space-x-2">
                     <input type="radio" name="paymentMethod" value="efectivo"
                         class="text-blue-500 focus:ring-blue-500" checked>
                     <span>Pagó en Efectivo</span>
                 </label>
             </div>
             <div class="mb-4">
                 <label class="flex items-center space-x-2">
                     <input type="radio" name="paymentMethod" value="account"
                         class="text-blue-500 focus:ring-blue-500">
                     <span>Deuda Pendiente</span>
                 </label>
             </div>
             <p class="text-color-titulo-entrega font-semibold m-2">Notas internas sobre este pedido
             </p>
             <div class="mb-4 w-full">
                 <textarea class="p-4 border w-full"
                     placeholder="Agrega notas internas sobre este pedido. Ejemplo: Le debo un vuelto de x soles, pagó con x cantidad."
                     name="notas" ></textarea>
             </div>
             <!-- Botones -->
             <div class="flex justify-end mt-6 space-x-1">
                 <button type="submit"
                     class="px-4 py-2 bg-naranja text-white rounded hover:bg-border-red-500 hover:scale-105 transition">
                     Aceptar
                 </button>
                 <button type="button"
                     class="px-4 py-2 border border-color-titulos-entrega text-color-titulos-entrega rounded hover:scale-105 transition"
                     onclick="document.getElementById('paymentModal').classList.remove('flex');;document.getElementById('paymentModal').classList.add('hidden')">
                     Cancelar
                 </button>
             </div>
         </form>
     </div>
 </div>

 <!-- Modal anular Pedido Repartidor-->
 <div id="modal_anular_pedido"
     class="hidden fixed flex-col inset-0 bg-black bg-opacity-70  items-center justify-center z-50">
     <div class="bg-white rounded text-color-text text-base font-sans  shadow-md w-full mx-auto max-w-md p-6">
         <h2 class="text-xl font-semibold text-center mb-4 font-cabin">Anular Pedido <span
                 id="modal_anular_pedido_id"></span></h2>
         <form id="form_anular_pedido_repartidor" method="post" action="{{ route('pedido.anular') }}">
             <input type="text" name="id_pedido" id="id_pedido_modal_anular" hidden>

             <p class="text-color-titulo-entrega font-semibold m-2">Notas internas sobre este pedido (Opcional)
             </p>
             <div class="mb-4 w-full">
                 <textarea class="p-4 border w-full min-h-44"
                     placeholder="Agrega notas internas sobre la anulación de este pedido. Ejemplo: El cliente canceló el pedido, referencia no encontrada, error en el monto cobrado."
                     name="notas" id=""></textarea>
             </div>
             <!-- Botones -->
             <div class="flex justify-end mt-6 space-x-1">
                 <button type="submit"
                     class="px-4 py-2 bg-naranja text-white rounded hover:bg-border-red-500 hover:scale-105 transition">
                     Aceptar
                 </button>
                 <button type="button"
                     class="px-4 py-2 border border-color-titulos-entrega text-color-titulos-entrega rounded hover:scale-105 transition"
                     onclick="document.getElementById('modal_anular_pedido').classList.remove('flex');;document.getElementById('modal_anular_pedido').classList.add('hidden')">
                     Cancelar
                 </button>
             </div>
         </form>
     </div>
 </div>
 <!-- Modal editar Pedido Admin-->
 <div id="modal_editar_pedido"
     class="hidden fixed flex-col inset-0 bg-black bg-opacity-70  items-center justify-center z-50">
     <div
         class="bg-white rounded text-color-text text-base font-sans  shadow-md w-full mx-auto  max-w-5xl p-6 h-[95vh] overflow-y-auto">
         <div class="relative">
             <!-- Título del modal -->
             <h2 class="text-xl font-semibold text-center mb-4 font-cabin">
                 Modificar Pedido #<span id="modal_editar_pedido_id"></span>
             </h2>

             <!-- Botón de cerrar -->
             <button type="button"
                 class="absolute top-0 right-0 mt-2 mr-2 p-2 text-red-500 hover:text-red-400 focus:outline-none"
                 onclick="document.getElementById('modal_editar_pedido').classList.add('hidden');" aria-label="Cerrar">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>

             <hr class="my-4">
         </div>

         <form id="form_editar_pedido_repartidor" method="post" action="{{ route('pedido.editar') }}"
             class="space-y-6">
             <input type="hidden" name="id_pedido" id="id_pedido_modal_editar">

             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                 <!-- Cliente -->
                 <div>
                     <label for="field_cliente" class="block text-sm font-medium text-gray-700">
                         Nombres y Apellidos <span class="text-red-500">*</span>
                     </label>
                     <input value="" required name="field_cliente" id="field_cliente" type="text"
                         class="mt-1 p-2 border rounded-md w-full">
                 </div>

                 <!-- Celular -->
                 <div>
                     <label for="field_Celular" class="block text-sm font-medium text-gray-700">
                         Celular <span class="text-red-500">*</span>
                     </label>
                     <input value="" required name="field_Celular" id="field_Celular" type="tel"
                         class="mt-1 p-2 border rounded-md w-full">
                 </div>
             </div>

             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                 <!-- Dirección -->
                 <div>
                     <label for="field_direccion" class="block text-sm font-medium text-gray-700">
                         Dirección <span class="text-red-500">*</span>
                     </label>
                     <input value="" placeholder="Ejemplo: Jr. Independencia 250" required
                         name="field_direccion" id="field_direccion" type="text"
                         class="mt-1 p-2 border rounded-md w-full">
                 </div>

                 <!-- Referencia -->
                 <div>
                     <label for="field_referencia" class="block text-sm font-medium text-gray-700">
                         Referencia y notas para la entrega <span class="text-red-500">*</span>
                     </label>
                     <textarea class="mt-1 p-2 border rounded-md w-full"
                         placeholder="Ejemplo: A espaldas de la plaza San José de Sisa | Casa color celeste" name="field_referencia"
                         id="field_referencia"></textarea>
                 </div>
             </div>

             <!-- Estado del pedido -->
             <div>
                 <label for="estado_pedido" class="block text-sm font-medium text-gray-700">
                     Estado del pedido <span class="text-red-500">*</span>
                 </label>
                 <select class="mt-1 p-2 rounded-md border w-full" required name="estado_pedido" id="estado_pedido">
                     <option value="Pendiente" selected>Pendiente</option>
                     <option value="En Camino">En Camino</option>
                     <option value="Entregado">Entregado ✅</option>
                     <option value="Anulado">Anulado</option>
                 </select>
             </div>

             <!-- Estado del pago -->
             <div>
                 <label for="estado_pago" class="block text-sm font-medium text-gray-700">
                     Estado del pago <span class="text-red-500">*</span>
                 </label>
                 <select class="mt-1 p-2 rounded-md border w-full" required name="estado_pago" id="estado_pago">
                     <option value="Pendiente de pago" selected>Pendiente de pago</option>
                     <option value="Pagado">Pagado ✅</option>
                     <option value="Deuda pendiente">Deuda pendiente</option>
                 </select>
             </div>

             <!-- Medio de Pago -->
             <div>
                 <label for="medio_pago" class="block text-sm font-medium text-gray-700">
                     Medio de Pago <span class="text-red-500">*</span>
                 </label>
                 <select class="mt-1 p-2 rounded-md border w-full" name="medio_pago" id="medio_pago">
                     <option value="efectivo">Efectivo</option>
                     <option value="yape">Yape</option>
                     <option value="account">Pago Pendiente</option>
                 </select>
             </div>

             <!-- Notas internas -->
             <div>
                 <label for="notas" class="block text-sm font-medium text-gray-700">
                     Notas internas sobre este pedido (Opcional)
                 </label>
                 <textarea class="mt-1 p-4 border rounded-md w-full min-h-24"
                     placeholder="Agrega notas internas sobre la anulación de este pedido. Ejemplo: El cliente canceló el pedido, referencia no encontrada, error en el monto cobrado."
                     name="notas" id="notas"></textarea>
             </div>

             <!-- Botón -->
             <div class="text-right">
                 <button
                     class="bg-secundario text-white font-medium text-base px-4 py-2 rounded-md hover:bg-secundariohover"
                     type="submit">Actualizar Pedido</button>
             </div>
         </form>

     </div>
 </div>

 </form>
 </div>
 </div>
