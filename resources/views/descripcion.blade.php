<div class="mt-10 bg-white w-full">
    <div class="grid mx-auto md:w-[600px] w-full md:max-w-[600px]" id="principal">
        <p class="text-tarjetas text-center leading-[45px] text-[35px] font-medium font-cabin">{{ $empresa->nombre }}</p>
        <br>
        <div class=" w-full grid text-justify text-color-text font-medium p-2">



            {{-- -  <p class="font-normal text[16px] leading-[35px] text-color-text"><span
                    class="text-color-elegido font-bold">{{ $empresa->nombre }}</span> siempre llevando un producto de
                calidad a la puerta de tu hogar. Ahora
                podrás realizar tus pedidos con solo dos clics. <span
                    class="text-color-elegido font-bold">{{ $empresa->nombre }}</span> , siempre pensando en mejorar la
                experiencia de
                nuestros clientes.
            </p>
            - --}}
            <p class="text-wrap font-normal mt-2 text-base leading-[35px] text-color-text">{!! nl2br(e($empresa->descripcion)) !!}</p>
            <div class="mt-[40px]">
                <label class="text-[20px] font-semibold ">Días y horarios de atención:
                </label>
                <table class="w-full mt-5 border border-gray-300" id="tabla_horario_conf">
                    <thead class="bg-tarjetas text-white ">
                        <th class="p-[15px] text-center text-[14.4px]">Dia</th>
                        <th class="p-[15px] text-center text-[14.4px]">Hora Inicio</th>
                        <th class="p-[15px] text-center text-[14.4px]">Hora Cierre</th>
                    </thead>
                    <tbody>
                        @forelse ($horarios as $item)
                            <tr class="border border-gray-300 odd:bg-gray-100 even:bg-white">
                                <td class="text-center p-[15px] text-[14.4px] border border-gray-300">
                                    {{ $item->dia }}
                                </td>
                                <td class="text-center p-[15px] text-[14.4px] border border-gray-300">

                                    {{ \Carbon\Carbon::parse($item->hora_inicio)->format('h:i A') }}

                                </td>
                                <td class="text-center p-[15px] text-[14.4px] border border-gray-300">
                                    {{ \Carbon\Carbon::parse($item->hora_fin)->format('h:i A') }}
                                </td>

                            </tr>
                        @empty
                            <tr class="border border-gray-300 odd:bg-gray-100 even:bg-white">
                                <td class="text-center p-[15px] text-[14.4px] border border-gray-300" colspan="3">
                                    Aun no se ha establecido el horario de Atención de la Distribuidora.
                                </td>


                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>
            <div class="relative z-40 w-full mx-auto overflow-hidden mb-[40px] mt-16">
                <!-- Contenedor de las diapositivas -->
                <div id="slider" class="flex transition-transform duration-500">
                    @foreach ($imagenes as $item)
                        <div class="w-full md:w-1/2 flex-shrink-0 p-2">
                            <img src="{{ asset('storage/' . $item) }}"
                                class="object-cover w-full h-auto rounded-lg shadow-md" alt="Producto de la empresa">
                        </div>
                    @endforeach
                </div>
                <!-- Controles -->
                <button id="prev"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-[#111111] text-white w-[50px] h-[50px] flex items-center justify-center rounded-full">
                    <i class="fas fa-angle-left text-[30px]"></i>
                </button>
                <button id="next"
                    class=" absolute right-0 top-1/2 transform -translate-y-1/2 bg-[#111111] text-white w-[50px] h-[50px] flex items-center justify-center rounded-full">
                    <i class="fas fa-angle-right text-[30px]"></i>
                </button>
            </div>
        </div>
    </div>
    @auth
        @if ($usuario->tipo == 'cliente' && $fueraHorario)
            <div id="anuncio"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 font-sans">
                <div class="bg-white p-6 rounded-lg shadow-lg w-3/4 md:w-1/2 text-center">
                    <h2 class="text-xl font-semibold text-red-600">📢 Aviso Importante</h2>

                    <p class="text-color-titulos-entrega mt-2">
                        Actualmente estamos fuera de nuestro horario de atención.

                        @if (!$nulo && $antes && $horaApertura && $horaCierre)
                            Los pedidos realizados en este momento serán procesados el día de hoy
                            ({{ ucfirst($dia) }}) desde las {{ \Carbon\Carbon::parse($horaApertura)->format('h:i A') }}
                            hasta las {{ \Carbon\Carbon::parse($horaCierre)->format('h:i A') }}.
                        @else
                            @php
                                // Definir el orden de los días de la semana
                                $diasSemana = [
                                    'Lunes',
                                    'Martes',
                                    'Miércoles',
                                    'Jueves',
                                    'Viernes',
                                    'Sabado',
                                    'Domingo',
                                ];

                                // Obtener el índice del día actual
                                $indiceDiaActual = array_search($dia, $diasSemana);

                                // Ordenar los horarios por el orden de la semana
                                $horariosOrdenados = collect($empresa->horarios)
                                    ->sortBy(function ($horario) use ($diasSemana) {
                                        return array_search($horario->dia, $diasSemana);
                                    })
                                    ->values();

                                // Buscar el siguiente día hábil
                                $siguienteHorario = null;
                                foreach ($horariosOrdenados as $horario) {
                                    $indiceHorario = array_search($horario->dia, $diasSemana);
                                    if ($indiceHorario > $indiceDiaActual) {
                                        $siguienteHorario = $horario;
                                        break;
                                    }
                                }

                                // Si no encontró un día después del actual, tomar el primer día hábil de la lista
                                if (!$siguienteHorario) {
                                    $siguienteHorario = $horariosOrdenados->first();
                                }

                                // Si hay un horario disponible, obtener sus datos
                                if ($siguienteHorario) {
                                    $siguienteDia = ucfirst($siguienteHorario->dia);
                                    $siguienteHoraApertura = \Carbon\Carbon::parse(
                                        $siguienteHorario->hora_inicio,
                                    )->format('h:i A');
                                    $siguienteHoraCierre = \Carbon\Carbon::parse($siguienteHorario->hora_fin)->format(
                                        'h:i A',
                                    );
                                } else {
                                    // Si no hay horarios configurados
                                    $siguienteDia = 'No disponible';
                                    $siguienteHoraApertura = 'No disponible';
                                    $siguienteHoraCierre = 'No disponible';
                                }
                            @endphp

                            Actualmente estamos fuera de nuestro horario de atención. Los pedidos realizados en este momento
                            serán procesados el día
                            <strong>{{ $siguienteDia }}</strong> desde las
                            <strong>{{ $siguienteHoraApertura }}</strong> hasta las
                            <strong>{{ $siguienteHoraCierre }}</strong>.
                        @endif
                    </p>

                    <button
                        onclick="document.getElementById('anuncio').classList.remove('flex');document.getElementById('anuncio').classList.add('hidden');"
                        class="mt-4 px-4 py-3 border shadow-xl border-red-600 text-red-600 rounded text-base font-semibold transform hover:scale-105">
                        Entendido
                    </button>
                </div>
            </div>
        @endif
    @endauth




</div>
