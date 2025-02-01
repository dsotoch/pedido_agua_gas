<div class="mt-10 bg-white w-full">
    <div class="grid mx-auto md:w-[600px] w-full md:max-w-[600px]" id="principal">
        <p class="text-tarjetas text-center leading-[45px] text-[35px] font-medium font-cabin">{{ $empresa->nombre }}</p>
        <br>
        <div class=" w-full grid text-justify text-color-text font-medium p-2">
            <p class="text-center text-[16px] mb-8 font-bold ">
                ¡Gracias a todos nuestros clientes por confiar en nosotros!
            </p>
            <p class="font-normal text[16px] leading-[35px] text-color-text"><span
                    class="text-color-elegido font-bold">{{ $empresa->nombre }}</span> siempre llevando un producto de
                calidad a la puerta de tu hogar. Ahora
                podrás realizar tus pedidos con solo dos clics. <span
                    class="text-color-elegido font-bold">{{ $empresa->nombre }}</span> , siempre pensando en mejorar la
                experiencia de
                nuestros clientes.
            </p>
            <div class="relative z-40 w-full  mx-auto  overflow-hidden mb-[40px] mt-16">
                <!-- Contenedor de las diapositivas -->
                <div id="slider" class="flex transition-transform duration-500">
                    @foreach ($imagenes as $item)
                        <div class="w-full flex-shrink-0">
                            <img src="{{ asset('storage/' . $item) }}" class="object-contain"
                                alt="Producto de la empresa">
                        </div>
                    @endforeach

                </div>
                <!-- Controles -->
                <button id="prev"
                    class="border hover:border-red-500 absolute left-0 top-1/2 transform -translate-y-1/2 bg-[#111111] text-white w-[50px] h-[50px]">
                    <i class="fas fa-angle-left text-[30px]"></i>

                </button>
                <button id="next"
                    class="border hover:border-red-500 absolute right-0 top-1/2 transform -translate-y-1/2 bg-[#111111] text-white   w-[50px] h-[50px]">
                    <i class="fas fa-angle-right text-[30px]"></i>

                </button>
            </div>
        </div>
    </div>


    {{-- --- 
    <-- Modal lateral -->
    <div id="modal" role="dialog" aria-modal="true"
        class="overflow-auto z-50 fixed top-0 right-0 h-full w-full md:w-1/2 bg-white shadow-lg transform translate-x-full transition-transform duration-300">
        <div class="p-4 flex flex-col h-full ">
            <!-- Botón para cerrar -->
            <button id="closeModal" class="ml-auto text-red-500 font-bold text-2xl">&times;</button>

            <!-- Título -->
            <h2 class="text-xl font-semibold mb-4">Detalles del Pedido</h2>

            <!-- Productos del pedido -->
            <div class="mb-4">
                <h3 class="text-md font-bold text-gray-700">Productos</h3>
                <br>
                <div class="space-y-2" id="divdetallespedido">
                    <table id="tablaDetallesPedido" class="w-full table-fixed border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-principal text-white text-center text-sm font-medium">
                                <th class="border border-gray-300 p-2">Descripción</th>
                                <th class="border border-gray-300 p-2">Cantidad</th>
                                <th class="border border-gray-300 p-2">Total</th>
                                <th class="border border-gray-300 p-2">ID</th>
                                <th class="border border-gray-300 p-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se insertarán dinámicamente las filas -->
                        </tbody>
                    </table>

                    <p class="font-semibold m-2 p-2 text-md " id="pedidovacio"> No hay Productos Agregados al
                        Pedido.
                    </p>
                </div>
            </div>
            <!-- Total del pedido -->
            <div class="mb-4 border-t pt-4 w-full flex justify-end items-center">
                <h3 class="text-md font-bold text-gray-700 m-2">Total</h3>
                <p class="text-xl font-bold text-blue-600 text-right mr-8" id="totalpedidomodal">S/0</p>
            </div>
            <hr>
            <br>

            <!-- Botones de acción -->
            <div class="flex justify-center ">
                <button id="btn-finalizar"
                    class="p-3 bg-principal text-white rounded w-1/2 hover:bg-principalhover text-xl transition duration-200">Realizar
                    Pedido</button>

            </div>

        </div>

    </div>
    <!-- Modal lateral Usuario-->
    <div id="modalUsuario" role="dialog" aria-modal="true"
        class="overflow-auto z-50 fixed top-0 right-0 h-full w-full md:w-1/2  bg-white shadow-lg transform translate-x-full transition-transform duration-300">
        <div class="p-4 flex flex-col h-full ">
            <!-- Botón para cerrar -->
            <button id="closeModalUsuario" class="ml-auto  text-red-500 font-bold text-3xl">&times;</button>

            <!-- Título -->
            <h2 class="text-2xl font-semibold mb-4">Panel del Usuario</h2>
            <hr>
            <br>
            <!----Detalles Usuario-->
            <div class="grid place-items-center">
                <div class="flex  justify-center items-center">
                    <img src="{{ asset('imagenes/logos.png') }}" alt="" class="w-[100px] h-[100px]">

                    @auth
                        @if ($cliente->tipo != 'repartidor' && $cliente->tipo != 'admin')
                            <p class="text-semibold text-xl text-blue-950" id="cliente-panel">
                                {{ $cliente->Cliente->nombres . ' ' . $cliente->Cliente->apellidos ?? 'Nombre no disponible' }}
                            </p>
                        @else
                            <p class="text-semibold text-xl text-blue-950" id="cliente-panel">
                                @forelse ($cliente->persona as $per)
                                    {{ $per->nombres }}
                                @empty
                                    Nombre no Disponible.
                                @endforelse

                            </p>
                        @endif
                    @else
                        <p class="text-semibold text-xl text-blue-950" id="cliente-panel">No ha Iniciado Sesión.</p>
                    @endauth


                </div>
                <br>
                @auth
                    @if ($cliente->tipo == 'admin')
                        <div class="w-full grid place-items-center" id="botondashboardclientepanel">
                            <form action="{{ route('empresa.logout', ['nombre_empresa' => $empresa->dominio]) }}"
                                method="post" class="w-1/2">
                                @csrf
                                <button type="submit"
                                    class="text-center p-3 rounded text-white w-full text-xl bg-gray-600 transition hover:bg-slate-900"><i
                                        class="fa-solid fa-right-from-bracket mr-2"></i>Cerrar Sesión</button>
                            </form>
                            <button
                                class="bg-principal mt-1 hover:bg-principalhover text-xl text-white rounded p-3 transition w-1/2"><i
                                    class="fa-solid fa-toolbox mr-2"></i>Dashboard</button>
                        </div>
                    @else
                        @if ($cliente->tipo == 'cliente')
                            <div class="content-between w-full space-x-2 {{ $cliente ? 'flex' : 'hidden' }}"
                                id="botonesclientepanel">
                                <form action="{{ route('empresa.logout', ['nombre_empresa' => $empresa->dominio]) }}"
                                    method="post" class="w-1/2">
                                    @csrf
                                    <button type="submit"
                                        class="text-center p-3 rounded  text-white w-full text-xl bg-secundario  hover:bg-secundariohover"><i
                                            class="fa-solid fa-right-from-bracket mr-2"></i>Cerrar
                                        Sesión</button>
                                </form>
                                <button id="btn-micuentapanelcliente"
                                    class=" bg-principal text-white text-xl p-3 w-1/2 rounded-md hover:bg-principalhover"><i
                                        class="fa-solid fa-house mr-2"></i>Mi
                                    Cuenta</button>
                            </div>
                        @else
                            <!---EL USUARIO ES REPARTIDOR--->
                            <i id="imgmoto" class="fas fa-motorcycle text-8xl m-2 text-red-500"></i>
                            <div class="justify-center w-full space-x-1 {{ $cliente ? 'flex' : 'hidden' }} text-white"
                                id="botonesclientepanelrepartidor">
                                <form action="{{ route('empresa.logout', ['nombre_empresa' => $empresa->dominio]) }}"
                                    method="post" class="w-1/2">
                                    @csrf
                                    <button type="submit"
                                        class="text-center p-3 rounded-md text-white w-full text-xl bg-secundario  hover:bg-secundariohover"><i
                                            class="fa-solid fa-right-from-bracket mr-2"></i>Cerrar Sesión</button>
                                </form>
                                <button id="btndashboardrepartidor"
                                    class="text-center p-3 rounded-md text-xl w-1/2 hover:bg-principalhover bg-principal"><i
                                        class="fa-solid fa-toolbox mr-2"></i>Dashboard</button>

                            </div>
                        @endif

                    @endauth

            </div>
            @endif
            <br>
            <hr>
            <!-- Botones de acción -->
            @guest
                <div class="flex w-full justify-center mb-4" id="botonesloginclientepanel">
                    <button id="btnLogin"
                        class="p-3 text-xl  custom-bg-button  text-white rounded-lg w-1/2 hover:bg-principalhover transition duration-200">Iniciar
                        Sesión</button>
                    <button id="btnRegister"
                        class="p-3 text-xl border border-principal text-principal rounded-lg w-1/2 hover:text-white hover:bg-principalhover transition duration-200 ml-2">Registrarse</button>
                </div>
            @endguest

          

           
            <br>
            <!-----Panel de mi cuenta -->
            <div class="container w-full hidden" id="panelmicuenta">
                <button id="btnregresar"
                    class="border border-principal m-2 hover:bg-principalhover hover:text-white text-principal  text-xl py-3 px-4 rounded">
                    <i class="fa fa-arrow-left m-2"></i> Atrás
                </button>
                <br>
                <hr>
                <div class="flex p-4 space-x-2 shadow-xl  justify-start ">
                    <button id="btndatosclientepanel"
                        class=" outline-2 border  hover:bg-principal hover:text-white text-black p-2 "><i
                            class="fa-solid fa-user p-2"></i>Mis
                        Datos</button>
                    <button id="btnpedidosclientepanel"
                        class="outline-2 outline-blue-500 border bg-blue-600 text-white border-blue-600 hover:bg-principal  p-2 transition duration-200"
                        aria-label="Ver Mis Pedidos">
                        <i class="fa-solid fa-cart-shopping p-2"></i>Mis Pedidos
                    </button>
                </div>
                @if ($cliente && $cliente->tipo != 'repartidor' && $cliente->tipo != 'admin')
                    <!--Mis datos-->
                    <div class="detallescliente hidden container p-2 shadow-lg">
                        <h2 class="text-lg font-medium text-principal mb-4"><i
                                class="fa-solid fa-id-card mr-2"></i>Mis
                            Datos
                        </h2>
                        @auth
                            <div class="space-y-1 w-full">
                                <!-- Teléfono -->
                                <p class="text-gray-700 flex items-center">
                                    <i class="fa-solid fa-phone text-green-500 mr-2"></i>
                                    <span class="font-semibold  m-2">Teléfono: </span> {{ $cliente->usuario }}
                                </p>
                                <!-- Nombres -->
                                <p class="text-gray-700 flex items-center">
                                    <i class="fa-solid fa-user text-blue-500 mr-2"></i>
                                    <span class="font-semibold m-2">Nombres: </span> {{ $cliente->Cliente->nombres }}
                                </p>
                                <!-- Apellidos -->
                                <p class="text-gray-700 flex items-center">
                                    <i class="fa-solid fa-user text-blue-500 mr-2"></i>
                                    <span class="font-semibold m-2">Apellidos: </span>
                                    {{ $cliente->Cliente->apellidos }}
                                </p>
                                <!-- Dirección -->
                                <p class="text-gray-700 flex items-center">
                                    <i class="fa-solid fa-location-dot text-red-500 mr-2"></i>
                                    <span class="font-semibold m-2">Dirección: </span>
                                    {{ $cliente->Cliente->direccion }}
                                </p>
                                <!-- Referencia -->
                                <p class="text-gray-700 flex items-center">
                                    <i class="fa-solid fa-map-pin text-yellow-500 mr-2"></i>
                                    <span class="font-semibold m-2">Referencia: </span> {{ $cliente->Cliente->nota }}
                                </p>
                            </div>
                        @else
                            <p class="m-2 p-2 font-medium"> Cliente No Autenticado.</p>
                        @endauth
                    </div>
                @endif
                <div class="hidden" id="divpedidosclientepanel">
                    @if (!is_null($pedidos) && $pedidos->isNotEmpty())
                        <div class="container mx-auto p-4">
                            <p class="text-xl font-semibold mb-4">
                                Mostrando
                                <span class="font-bold text-blue-600">{{ $pedidos->count() }}</span> Pedidos
                            </p>
                            <div id="content-wrapper"
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach ($pedidos as $pedido)
                                    <!-- Card de Pedido -->
                                    <div
                                        class="bg-tarjetas text-white shadow-md rounded-lg p-6 border border-gray-200">
                                        <!-- Encabezado -->
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-bold text-white">
                                                Pedido #{{ $pedido->id }}
                                            </h3>
                                            <span class="text-sm text-gray-500">{{ $pedido->fecha }}</span>
                                        </div>

                                        <!-- Cliente -->
                                        <div class="mb-4">
                                            <p class="text-sm"><i class="fa-solid fa-user text-blue-500 mr-2"></i>
                                                {{ $pedido->cliente ? $pedido->cliente->nombres : 'Null' }}{{ $pedido->cliente ? $pedido->cliente->apellidos : 'Null' }}
                                            </p>
                                            <p class="text-sm"><i class="fa-solid fa-phone text-green-500 mr-2"></i>
                                                {{ $pedido->cliente ? $pedido->cliente->telefono : 'Null' }}
                                            </p>
                                            <p class="text-sm"><i
                                                    class="fa-solid fa-location-dot text-red-500 mr-2"></i>
                                                {{ $pedido->cliente ? $pedido->cliente->direccion : 'Null' }}
                                            </p>
                                        </div>

                                        <!-- Detalles del Pedido -->
                                        <div class="mb-2">
                                            @foreach ($pedido->detalles as $item)
                                                <p class="text-sm">
                                                    <i class="fa-solid fa-box text-yellow-500 mr-2"></i>
                                                    {{ $item->producto?->descripcion ?? 'Producto no disponible' }}
                                                    x
                                                    {{ $item->cantidad }}
                                                </p>
                                            @endforeach
                                        </div>

                                        <!-- Total -->
                                        <div class="mb-4">
                                            <p class="font-semibold text-lg">
                                                Total: <span class="text-green-600 font-bold">S/
                                                    {{ $pedido->total }}</span>
                                            </p>
                                        </div>

                                        <!-- Estado -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium">
                                                ✅ Estado:
                                                <span class="text-green-600 font-bold">{{ $pedido->estado }}</span>
                                            </span>
                                        </div>

                                        <!-- Notas -->
                                        @if ($pedido->nota)
                                            <div class="mt-4">
                                                <p class="text-sm"><i
                                                        class="fa-solid fa-sticky-note text-gray-500 mr-2"></i>
                                                    <span class="font-medium">Notas:</span> {{ $pedido->nota }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                <div id="pagination-wrapper" class="m-2 p-4 flex justify-center">
                                    {{ $pedidos->links() }}
                                </div>

                            </div>
                        </div>
                    @else
                        <p class="m-4 text-center text-gray-600">
                            No hay pedidos registrados para este usuario.
                        </p>
                    @endif

                </div>
            </div>
            <!--Panel mi cuenta Administrador-->
            <div class="container w-full hidden" id="paneladministrador">
                <button id="btnregresaradmin"
                    class="border border-principal  m-2 hover:bg-principalhover hover:text-white transition text-principal text-xl font-bold py-3 px-4 rounded">
                    <i class="fa fa-arrow-left m-2"></i> Atrás
                </button>
                <br>
                <hr>
                <div class="grid grid-cols-2 space-x-2 lg:grid-cols-5 md:grid-cols-5 xl:grid-cols-5">
                    <button id="btnpedidosadmin" class="p-2 rounded-md  bg-blue-600 text-white hover:bg-blue-500"><i
                            class="fas fa-motorcycle text-2xl m-2"></i>Pedidos</button>
                    <button id="btnclientesadmin"
                        class="p-2 rounded-md   hover:bg-blue-500  hover:text-white border"><i
                            class="fas fa-users text-2xl m-2"></i>Clientes</button>
                    <form action="{{ route('empresa.clienteEmpresa', ['nombre_empresa' => $empresa->dominio]) }}"
                        method="get" class="hidden" id="formclientes">

                    </form>
                    <button id="btnreportesadmin"
                        class="p-2 rounded-md    hover:bg-blue-500 hover:text-white border"><i
                            class="fas fa-file-alt text-2xl m-2"></i>Reportes</button>
                    <button id="btnproductosadmin"
                        class="p-2 rounded-md    hover:bg-blue-500 hover:text-white border"><i
                            class="fas fa-box text-2xl m-2"></i>Productos</button>
                    <button id="btnusuariosadmin"
                        class="p-2 rounded-md    hover:bg-blue-500 hover:text-white border"><i
                            class="fas fa-user text-2xl m-2"></i>Usuarios</button>
                </div>
                <hr>
                <div class="container" id="divpedidosadmin">
                    <p class="text-xl font-semibold m-2">Tienes un Total de <span class="font-bold text-blue-600 m-2">
                            {{ $empresa ? count($pedidos) : 0 }}
                        </span>Pedidos</p>
                    <div id="contenedorpedidosadmin"
                        class=" container mx-auto p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @if ($empresa)
                            @foreach ($pedidos as $pe)
                                <div
                                    class="pedidosadministrador bg-tarjetas text-white shadow-md rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-bold text-lg">Pedido #{{ $pe->id }}</h3>
                                        <span class="text-sm text-gray-500">{{ $pe->fecha }}</span>
                                    </div>
                                    <p class="text-sm mt-2">
                                        <i class="fa-solid fa-user text-blue-500"></i>
                                        {{ $pe->cliente?->nombres ?? 'Sin nombre' }}
                                        {{ $pe->cliente?->apellidos ?? '' }}
                                    </p>
                                    <p class="text-sm mt-1">
                                        <i class="fa-solid fa-phone text-green-500"></i>
                                        {{ $pe->cliente?->telefono ?? 'Sin teléfono' }}
                                    </p>
                                    <p class="text-sm mt-1">
                                        <i class="fa-solid fa-location-dot text-red-500"></i>
                                        {{ $pe->cliente?->direccion ?? 'Sin dirección' }}
                                    </p>

                                    <p class="text-sm mt-1"><i class="fa-solid fa-box text-yellow-500"></i>
                                        @foreach ($pe->detalles as $item)
                                            {{ $item->producto ? $item->producto->descripcion : 'Nulo' }} x
                                            {{ $item->cantidad }}
                                        @endforeach

                                    </p>
                                    <div class="mt-2">
                                        <p class="font-semibold">Total: <span class="text-green-600">S/
                                                {{ $pe->total }}</span></p>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-sm text-white">Delivery: <span
                                                class="{{ $pe->estado == 'RECIBIDO' ? 'text-red-600' : 'text-green-600' }} font-bold">
                                                @switch($pe->estado)
                                                    @case('RECIBIDO')
                                                        Pendiente ❌
                                                    @break

                                                    @case('ENVIADO')
                                                        En camino 🚚
                                                    @break

                                                    @case('ENTREGADO')
                                                        Entregado ✅
                                                    @break

                                                    @default
                                                        Estado desconocido ⚠️
                                                @endswitch
                                            </span>
                                            <span class="text-sm text-white">Pagado: <span
                                                    class="{{ $pe->pago ? 'text-green-600' : 'text-red-600' }}  font-bold">
                                                    {{ $pe->pago ? '✅' : '❌' }}
                                                </span></span>
                                    </div>
                                    <p class="text-sm mt-1">
                                        <i class="fa-solid fa-wallet text-purple-500"></i>
                                        {{ $pe->pago ? 'Pagado con ' . $pe->metodo : 'Pendiente de pago' }}
                                    </p>

                                    <p class="text-sm mt-1"><i class="fa-solid fa-sticky-note text-gray-500"></i>
                                        Notas
                                        del
                                        pedido: {{ $pe->nota }}

                                    </p>
                                    <p class="text-sm mt-1"><i class="fas fa-motorcycle text-red-500"></i>
                                        Repartidor
                                        : <span class="spanrepartidor">
                                            {{ $pe->repartidor ? $pe->repartidor->persona->first()->nombres : 'Sin Asignación' }}

                                        </span>
                                    </p>
                                    <br>
                                    <div
                                        class="flex space-x-4 p-4 bg-tarjetas shadow-md rounded-lg items-center justify-center">
                                        @if ($pe->estado == 'RECIBIDO')
                                            <!-- Icono para asignar repartidor -->
                                            <button data-id="{{ $pe->id }}"
                                                class=" btnasignarrepartidor p-3 bg-principal hover:bg-principalhover text-xl text-white rounded">
                                                Asignar Repartidor

                                            </button>
                                        @endif


                                    </div>



                                </div>
                            @endforeach

                           
                            <div id="pagination-wrapper-admin" class="m-2 p-4 flex justify-center">
                                {{ $pedidos->links() }}
                            </div>
                        @endif

                    </div>
                </div>
               
                <div class="container mx-auto p-5 hidden" id="divclientesadminfull">
                    <p class="text-xl font-semibold m-2">Tienes un Total de <span class="font-bold text-blue-600 m-1"
                            id="cantidadclientesadmin"> 0
                        </span>Clientes.</p>
                    <div class=" grid-cols-1 grid sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6 bg-white"
                        id="divclientesadmin">

                    </div>
                </div>
               
                <div class="hidden container mx-auto p-5" id="divreportesadmin">
                    <div class=" shadow-xl bg-white border border-principal p-2 ">
                        <h1 class="text-2xl font-normal text-center my-8">Pedidos del dia</h1>
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full text-wrap bg-white shadow-lg rounded-lg">
                                <thead class="text-center">
                                    <tr class="bg-principal text-white text-md text-center">
                                        <th class="px-4 py-2 ">#</th>
                                        <th class="px-4 py-2 ">Fecha</th>
                                        <th class="px-4 py-2 ">Total</th>
                                        <th class="px-4 py-2 ">Estado</th>
                                        <th class="px-4 py-2 ">Repartidor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pedidosdeldia as $pedido)
                                        <tr class="border-b text-center">
                                            <td class="px-4 py-2">{{ $pedido->id }}</td>
                                            <td class="px-4 py-2">{{ $pedido->fecha }}</td>
                                            <td class="px-4 py-2">S/{{ $pedido->total }}</td>
                                            <td class="px-4 py-2">{{ $pedido->estado }}</td>
                                            <td class="px-4 py-2">
                                                @if ($pedido->repartidor)
                                                    @foreach ($pedido->repartidor->persona as $item)
                                                        {{ $item->nombres ?? 'Repartidor no Asignado.' }}
                                                    @endforeach
                                                @else
                                                    Pendiente de Asignación.
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center px-4 py-2">No hay datos
                                                disponibles</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right px-4 py-2 font-bold">
                                            Total: S/ {{ number_format($total_diario, 2) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="md:flex grid w-full">
                        <div class="overflow-x-auto shadow-xl bg-white border md:w-1/2 w-full border-principal p-2 ">
                            <h1 class="text-2xl font-normal text-center my-8">Desglose de Pagos del Día</h1>

                            <div class=" w-full grid space-y-2">
                                @if ($desglosepagosdeldia && count($desglosepagosdeldia) > 0)
                                    @foreach ($desglosepagosdeldia as $item)
                                        <div
                                            class="p-4 bg-tarjetas border text-white rounded-md shadow-xl hover:shadow-lg transition-shadow duration-300">
                                            <h3 class="text-lg font-normal text-principal mb-2">
                                                {{ $item['metodo'] !== null && $item['metodo'] !== ''
                                                    ? 'Pagos Realizados con ' . ($item['metodo'] === 'account' ? 'a Cuenta' : $item['metodo'])
                                                    : 'Sin método de pago asignado' }}
                                            </h3>
                                            <p class="">
                                                Total: <span class="font-semibold text-lg">S/
                                                    {{ number_format($item['total'], 2) }}</span>
                                            </p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-4 bg-tarjetas text-white rounded-lg shadow-xl border">
                                        <h3 class="text-lg font-normal text-center text-principal">
                                            No hay datos de pagos disponibles.
                                        </h3>
                                    </div>
                                @endif
                            </div>


                        </div>
                        <div class="overflow-x-auto shadow-xl bg-white border md:w-1/2 w-full border-principal p-2">
                            <h1 class="text-2xl font-normal text-center my-8">Repartidores y sus Pedidos Asignados
                                del Día
                            </h1>
                            <table class="table-auto w-full  text-wrap bg-white shadow-lg rounded-lg">
                                <thead class="text-center">
                                    <tr class="bg-principal text-white text-md text-center">
                                        <th class="px-4 py-2">Repartidor</th>
                                        <th class="px-4 py-2">Cantidad de Pedidos</th>
                                        <th class="px-4 py-2">Pedido ID</th>
                                        <th class="px-4 py-2">Fecha</th>
                                        <th class="px-4 py-2">Total</th>
                                        <th class="px-4 py-2">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($repartidoresConPedidos as $reporte)
                                        <tr class="border-b text-center">
                                            <td class="px-4 py-2" rowspan="{{ $reporte['cantidad_asignados'] }}">
                                                {{ $reporte['repartidor'] }}
                                            </td>
                                            <td class="px-4 py-2" rowspan="{{ $reporte['cantidad_asignados'] }}">
                                                {{ $reporte['cantidad_asignados'] }}
                                            </td>
                                            @foreach ($reporte['pedidos'] as $index => $pedido)
                                                @if ($index > 0)
                                        <tr class="border-b text-center">
                                    @endif
                                    <td class="px-4 py-2">{{ $pedido->id }}</td>
                                    <td class="px-4 py-2">{{ $pedido->fecha }}</td>
                                    <td class="px-4 py-2">S/ {{ $pedido->total }}</td>
                                    <td class="px-4 py-2">{{ $pedido->estado }}</td>
                                    </tr>
                                    @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center px-4 py-2">No hay datos
                                            disponibles</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class=" shadow-xl bg-white border border-principal p-2 ">
                        <h1 class="text-2xl font-normal text-center my-8">Pedidos a Cuenta con Pago Pendiente</h1>
                        <div class="overflow-x-auto">
                            <table
                                class="tablapagospendientes table-auto w-full text-wrap bg-white shadow-lg rounded-lg">
                                <thead class="text-center">
                                    <tr class="bg-tarjetas text-white text-md text-center">
                                        <th class="px-4 py-2 ">#</th>
                                        <th class="px-4 py-2 ">Fecha</th>
                                        <th class="px-4 py-2 ">Total</th>
                                        <th class="px-4 py-2 ">Cliente</th>
                                        <th class="px-4 py-2 ">Operación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pedidospendientedepago as $pedido)
                                        <tr class="border-b text-center">
                                            <td class="px-4 py-2">{{ $pedido->id }}</td>
                                            <td class="px-4 py-2">{{ $pedido->fecha }}</td>
                                            <td class="px-4 py-2">S/{{ $pedido->total }}</td>
                                            <td class="px-4 py-2">{{ $pedido->cliente->nombres }}</td>
                                            <td class="px-4 py-2"><button data-id="{{ $pedido->id }}"
                                                    class="btnpagarreporte p-2 m-2 rounded text-white bg-principal hover:bg-principalhover">Pagar</button>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center px-4 py-2">No hay datos
                                                disponibles</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>


            </div>
            <!---Panel Repartidor--->
            <div id="panelrepartidor" class="container   hidden">
                <div class="flex w-full justify-between">
                    <button id="btnregresarrepartidor"
                        class=" border-2 border-principal m-2 text-principal  hover:bg-principalhover text-xl hover:text-white md:w-1/5 w-1/2  py-2 px-4 rounded-md">
                        <i class="fa fa-arrow-left m-2 w-1/5"></i> Atrás
                    </button>
                    <i id="imgmotomini" class="fas fa-motorcycle text-5xl m-2 text-red-500"></i>
                </div>
                <hr>
                <div class="grid md:grid-cols-4 grid-cols-1 p-2 md:space-x-2 space-y-2 md:space-y-0">
                    @if ($pedidosrepartidor->isNotEmpty())
                        @foreach ($pedidosrepartidor as $pe)
                            <div id="pagination-wrapper-repartidor"
                                class="pedidosrepartidor bg-tarjetas text-white shadow-md rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-lg">Pedido #{{ $pe->id }}</h3>
                                    <span class="text-sm text-gray-500">{{ $pe->fecha }}</span>
                                </div>
                                <p class="text-sm mt-2">
                                    <i class="fa-solid fa-user text-blue-500"></i>
                                    {{ $pe->cliente?->nombres ?? 'Sin nombre' }}
                                    {{ $pe->cliente?->apellidos ?? '' }}
                                </p>
                                <p class="text-sm mt-1">
                                    <i class="fa-solid fa-phone text-green-500"></i>
                                    {{ $pe->cliente?->telefono ?? 'Sin teléfono' }}
                                </p>
                                <p class="text-sm mt-1">
                                    <i class="fa-solid fa-location-dot text-red-500"></i>
                                    {{ $pe->cliente?->direccion ?? 'Sin dirección' }}
                                </p>

                                <p class="text-sm mt-1"><i class="fa-solid fa-box text-yellow-500"></i>
                                    @foreach ($pe->detalles as $item)
                                        {{ $item->producto ? $item->producto->descripcion : 'Nulo' }} x
                                        {{ $item->cantidad }}
                                    @endforeach

                                </p>
                                <div class="mt-2">
                                    <p class="font-semibold">Total: <span class="text-green-600">S/
                                            {{ $pe->total }}</span></p>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-white">Delivery: <span
                                            class="{{ $pe->estado == 'RECIBIDO' ? 'text-red-600' : 'text-green-600' }} font-bold spanestadorepartidor">
                                            @switch($pe->estado)
                                                @case('RECIBIDO')
                                                    Pendiente ❌
                                                @break

                                                @case('ENVIADO')
                                                    En camino 🚚
                                                @break

                                                @case('ENTREGADO')
                                                    Entregado ✅
                                                @break

                                                @default
                                                    Estado desconocido ⚠️
                                            @endswitch </span>
                                        <span class="text-sm text-white">Pagado: <span
                                                class="{{ $pe->pago ? 'text-green-600' : 'text-red-600' }}  font-bold spanpagado">
                                                {{ $pe->pago ? '✅' : '❌' }}
                                            </span></span>
                                </div>
                                <p class="text-sm mt-1 metodopedido">
                                    <i class="fa-solid fa-wallet text-purple-500"></i>
                                    {{ $pe->pago ? 'Pagado con ' . $pe->metodo : 'Pendiente de pago' }}
                                </p>

                                <p class="text-sm mt-1"><i class="fa-solid fa-sticky-note text-gray-500"></i>
                                    Notas
                                    del
                                    pedido: {{ $pe->nota }}

                                </p>

                                <div class="flex space-x-2 p-4  w-full shadow-md rounded items-center justify-center">
                                    @if ($pe->estado == 'RECIBIDO')
                                        <form class="formaceptarrepartidor"
                                            action="{{ route('pedido.recibididorepartidor', ['id' => $pe->id, 'nombre_empresa' => $empresa->dominio]) }}"
                                            method="post">
                                            <button title="Tomar Pedido." data-id="{{ $pe->id }}"
                                                class="btnaceptarrepartidor transition border-2 bg-principal text-white disabled:bg-secundario  hover:bg-principal  hover:border-principal  rounded   p-3 text-xl"><i
                                                    class="fa-solid fa-hands-holding-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($pe->estado == 'ENVIADO')
                                        <button title="Finalizar Pedido." type="button"
                                            data-id="{{ $pe->id }}"
                                            class="btnpagorepartidor transition border-2  bg-principal text-white rounded  hover:bg-principal  hover:border-principal   p-3 text-xl"><i
                                                class="fas fa-money-bill-wave "></i>
                                        </button>
                                    @endif


                                </div>


                            </div>
                        @endforeach
                        
                        <div id="pagination-wrapper-repartidor" class="mt-2 p-4 flex justify-center">
                            {{ $pedidosrepartidor->links() }}
                        </div>
                    @else
                        <p class="p-2 m-2 font-medium">Sin Pedidos Asignados.</p>
                    @endif

                </div>
            </div>
        </div>



    </div>

--- --}}
</div>
