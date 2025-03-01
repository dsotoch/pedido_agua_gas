@extends('layout-cuenta')
@section('logica')
    <div class="container mx-auto p-5 min-h-screen text-color-titulos-entrega " id="divusuariosadmin">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-cabin font-semibold">Lista de Usuarios de la Distribuidora {{ $empresa->nombre }}</h2>
            <!-- Botón para agregar un nuevo usuario -->
            <button id="btnnuevousuario"
                class="flex items-center px-4 py-3 text-base bg-white border  text-color-titulos-entrega font-medium rounded shadow-md transform  hover:scale-125">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Usuario
            </button>
        </div>
        @if (session('mensaje'))
            <div  id="mensaje"
                class="mensaje border-2  border-green-500 font-semibold flex justify-center text-center p-2 bg-green-100 text-green-700">
                <p>{{ session('mensaje') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div id="mensaje"
                class="mensaje border-2 border-red-500 font-semibold flex flex-col items-center text-center p-2 bg-red-100 text-red-700">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Lista de usuarios -->
        <div class="bg-white text-color-text shadow-md rounded-[20px] font-sans text-base">
            <ul class="divide-y divide-gray-200" id="listausuarios">
                @if ($usuarios)
                    @forelse ($usuarios as $item)
                        <!-- Usuario -->
                        <li class="flex justify-between items-center p-4 usuariosadmin" data-id="{{ $item->id }}">
                            <div class="space-y-2">


                                <h3 class="font-medium  text-base">
                                    {{ $item->persona?->nombres ?? 'Sin nombre' }}
                                </h3>
                                <p class="text-base hidden">
                                    <i class="fas fa-id-card mr-1"></i> {{ $item->persona?->dni }}
                                </p>
                                <p class="text-base "><i class="fas fa-phone mr-1 "></i>
                                    {{ $item->usuario }}</p>
                                <p class="text-base ">
                                    <i
                                        class="{{ $item->persona?->estado ? 'fas fa-check-circle ' : 'fas fa-times-circle' }} mr-1"></i>
                                    <span class="usuario_estado">
                                        {{ $item->persona?->estado ? 'Activo' : 'Inactivo' }}</span>
                                </p>

                            </div>

                            @if ($item->tipo != 'admin')
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <form class="formcambiarestado"
                                        action="{{ route('estado.usuario', ['id' => optional($item->persona)->id ?? 0]) }}"
                                        method="POST">
                                        @method('PUT')
                                        @if ($item->persona?->estado)
                                            <button data-id="{{ $item->persona->id }}" type="submit"
                                                class="btnestadosusuarios px-4 py-2 bg-green-500 text-white font-medium rounded-lg shadow-md hover:bg-green-400">
                                                <i class="fas fa-ban"></i> Inhabilitar
                                            </button>
                                        @else
                                            <button data-id="{{ $item->persona?->id }}" type="submit"
                                                class=" btnestadosusuarios px-4 py-2 bg-red-500 text-white font-medium rounded-lg shadow-md hover:bg-red-400">
                                                <i class="fas fa-ban"></i> Habilitar
                                            </button>
                                        @endif
                                    </form>
                                    <div class="flex space-x-2">
                                        <button><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('usuario.eliminar') }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <input type="text" value="{{ $item->id }}" name="persona_id"
                                                class="hidden">
                                            <button type="submit"><i class="fas fa-trash"></i></button>

                                        </form>
                                    </div>
                                </div>
                            @endif

                        </li>
                    @empty
                        <p class="m-2 p-2 font-medium">Sin Usuarios en la Empresa.</p>
                    @endforelse
                @endif


            </ul>
        </div>
        <!---MODAL NUEVO USUARIO-->
        <div id="modalnuevousuario"
            class="fixed inset-0 z-50  overflow-y-auto  items-center justify-center bg-gray-900 bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md max-h-[95vh] overflow-y-auto">

                <form action="{{ route('crear.usuario') }}" method="POST"
                    class="bg-white rounded-lg shadow-lg w-full max-w-md" id="nuevousuarioadmin">
                    <!-- Encabezado del modal -->
                    <div class="flex justify-between items-center border-b p-4">
                        <h2 class="text-xl font-cabin font-semibold text-color-titulos-entrega">Crear Nuevo Usuario</h2>
                        <button type="button" id="closemodalusuario" class="text-red-500 text-xl hover:text-gray-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Contenido del modal -->
                    <div class="p-2 space-y-4">
                        <div>
                            <label for="nombress"
                                class="block text-sm font-medium text-color-titulos-entrega">Nombres</label>
                            <input type="text" name="nombres"
                                class="p-2 w-full border  rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1"
                                placeholder="Nombre del usuario">
                        </div>
                        <input type="text" name='empresa' hidden value="{{ $empresa->id }}">
                        <div>
                            <label for="apellidos"
                                class="block text-sm font-medium text-color-titulos-entrega">Apellidos</label>
                            <input type="text" name="apellidos"
                                class="p-2 w-full border  rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1"
                                placeholder="Apellidos del usuario">
                        </div>
                        <div class="hidden">
                            <label for="dni" class="block text-sm font-medium text-color-titulos-entrega">DNI</label>
                            <input type="text" name="dni" 
                                class="p-2 w-full border  rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1"
                                placeholder="DNI del usuario">
                        </div>
                        <div>
                            <label for="direccion"
                                class="block text-sm font-medium text-color-titulos-entrega">Dirección</label>
                            <input type="text" name="direccion"
                                class="p-2 w-full border  rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1"
                                placeholder="Dirreción del usuario">
                        </div>
                        <div>
                            <label for="correo" class="block text-sm font-medium text-color-titulos-entrega">Correo
                                Electronico</label>
                            <input type="email" name="correo"
                                class="p-2 w-full border  rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1"
                                placeholder="Email del usuario">
                        </div>
                        <div>
                            <label for="telefono"
                                class="block text-sm font-medium text-color-titulos-entrega">Teléfono</label>
                            <input type="text" name="telefono"
                                class="p-2 w-full border   rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1"
                                placeholder="Número de teléfono">
                        </div>
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-color-titulos-entrega">Contraseña</label>
                            <input type="password" name="password"
                                class="p-2 w-full border   rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1"
                                placeholder="Contraseña">
                        </div>

                        <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6">
                            <!-- Título -->
                            <h2 class="text-lg font-bold text-color-titulos-entrega mb-4">Tipo de Usuario</h2>

                            <!-- Select -->
                            <div class="mb-4">
                                <label for="repartidor"
                                    class="block text-sm font-medium text-color-titulos-entrega">Rol:</label>
                                <select name="rol"
                                    class="w-full mt-2  rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="repartidor" selected>Repartidor</option>

                                </select>
                            </div>

                        </div>


                    </div>

                    <!-- Acciones del modal -->
                    <div class="flex justify-end border-t p-4">
                        <button type="reset"
                            class="px-4 py-3 border text-base border-color-titulos-entrega  text-color-titulos-entrega rounded hover:scale-125 transform  mr-2">
                            Nuevo
                        </button>
                        <button type="submit"
                            class="px-4 py-3 bg-naranja text-base hover:scale-125 transform text-white rounded hover:bgprincipalhover">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
