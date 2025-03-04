@extends('layout-cuenta')
@section('logica')
    <div class="w-full h-full pb-[40px]">
        <div class="md:pl-[10px] pl-4 flex">

            <div class="md:max-w-[450px] w-3/4 md:w-[450px] mr-2">
                <input id="cliente_input_buscar"
                    class="placeholder-gray-500 rounded-3xl w-full bg-transparent border-color-text border p-3" type="search"
                    autocomplete="off" name="first_name" value="" placeholder="Buscar por nombres..."
                    aria-label="Buscar por nombre">
            </div>
            <button type="button" class="bg-naranja text-white w-[48px] h-[50px] rounded-xl" disabled>
                <i class="fa fa-search text-base font-bold"></i>
            </button>


        </div>
        <div id="cliente_contenedor_cliente" class="mt-8 md:ml-8 ml-0 grid md:grid-cols-3 grid-cols-1 md:space-y-0 space-y-4 md:items-stretch  items-center">
            @if ($clientes && $clientes->isNotEmpty())
                @foreach ($clientes as $user)
                    @if ($user->persona)
                        <div
                            class=" flex-1  p-[10px] cliente_clientes md:w-[368px] md:min-w-[368px] md:max-w-[368px] w-full flex flex-grow mx-auto  text-base text-color-titulos-entrega">
                            <div
                                class="space-y-2 pb-[27px] grid   w-full   rounded-[20px] bg-white p-3 pt-[27px]">
                                <div class="a a-ab79122 elementor-widget elementor-widget-jet-listing-dynamic-field"
                                    data-id="ab79122" data-element_type="widget"
                                    data-widget_type="jet-listing-dynamic-field.default">
                                    <div class="flex space-x-2">
                                        <div class="w-[20px] h-[20px] opacity-80"><svg class="w-full h-full"
                                                aria-hidden="true" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M294.75 188.19h-45.92V342h47.47c67.62 0 83.12-51.34 83.12-76.91 0-41.64-26.54-76.9-84.67-76.9zM256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm-80.79 360.76h-29.84v-207.5h29.84zm-14.92-231.14a19.57 19.57 0 1 1 19.57-19.57 19.64 19.64 0 0 1-19.57 19.57zM300 369h-81V161.26h80.6c76.73 0 110.44 54.83 110.44 103.85C410 318.39 368.38 369 300 369z">
                                                </path>
                                            </svg></div>
                                        <div class="jet-listing-dynamic-field__content">{{ $user->id }}</div>
                                    </div>
                                </div>
                                <div class="a a-97a7958 elementor-widget elementor-widget-jet-listing-dynamic-field"
                                    data-id="97a7958" data-element_type="widget"
                                    data-widget_type="jet-listing-dynamic-field.default">
                                    <div class="flex space-x-2">
                                        <div class="w-[16px] h-[16px] opacity-80"><svg class="w-full h-full"
                                                aria-hidden="true" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z">
                                                </path>
                                            </svg></div>
                                        <div class="">{{ $user->usuario }}</div>
                                    </div>
                                </div>
                                <div class="a a-3d2b4ea elementor-widget elementor-widget-jet-listing-dynamic-field"
                                    data-id="3d2b4ea" data-element_type="widget"
                                    data-widget_type="jet-listing-dynamic-field.default">
                                    <div class="flex space-x-2">
                                        <div class="w-[16px] h-[16px] opacity-80"><svg class="w-full h-full"
                                                aria-hidden="true" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M313.6 304c-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 304 0 364.2 0 438.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-25.6c0-74.2-60.2-134.4-134.4-134.4zM400 464H48v-25.6c0-47.6 38.8-86.4 86.4-86.4 14.6 0 38.3 16 89.6 16 51.7 0 74.9-16 89.6-16 47.6 0 86.4 38.8 86.4 86.4V464zM224 288c79.5 0 144-64.5 144-144S303.5 0 224 0 80 64.5 80 144s64.5 144 144 144zm0-240c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96z">
                                                </path>
                                            </svg></div>
                                        <div class="cliente_nombres">{{ $user->persona->nombres }}
                                        </div>
                                    </div>
                                </div>
                                <div class="a a-2b41b82 elementor-widget elementor-widget-jet-listing-dynamic-field"
                                    data-id="2b41b82" data-element_type="widget"
                                    data-widget_type="jet-listing-dynamic-field.default">
                                    <div class="flex space-x-2">
                                        <div class="w-[16px] h-[16px] opacity-80"><svg class="w-full h-full"
                                                aria-hidden="true" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M256 8C118.941 8 8 118.919 8 256c0 137.059 110.919 248 248 248 48.154 0 95.342-14.14 135.408-40.223 12.005-7.815 14.625-24.288 5.552-35.372l-10.177-12.433c-7.671-9.371-21.179-11.667-31.373-5.129C325.92 429.757 291.314 440 256 440c-101.458 0-184-82.542-184-184S154.542 72 256 72c100.139 0 184 57.619 184 160 0 38.786-21.093 79.742-58.17 83.693-17.349-.454-16.91-12.857-13.476-30.024l23.433-121.11C394.653 149.75 383.308 136 368.225 136h-44.981a13.518 13.518 0 0 0-13.432 11.993l-.01.092c-14.697-17.901-40.448-21.775-59.971-21.775-74.58 0-137.831 62.234-137.831 151.46 0 65.303 36.785 105.87 96 105.87 26.984 0 57.369-15.637 74.991-38.333 9.522 34.104 40.613 34.103 70.71 34.103C462.609 379.41 504 307.798 504 232 504 95.653 394.023 8 256 8zm-21.68 304.43c-22.249 0-36.07-15.623-36.07-40.771 0-44.993 30.779-72.729 58.63-72.729 22.292 0 35.601 15.241 35.601 40.77 0 45.061-33.875 72.73-58.161 72.73z">
                                                </path>
                                            </svg></div>
                                        <div class="jet-listing-dynamic-field__content">{{ $user->persona->correo??'Correo no Registrado' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="a a-2bc5557 elementor-widget elementor-widget-jet-listing-dynamic-field"
                                    data-id="2bc5557" data-element_type="widget"
                                    data-widget_type="jet-listing-dynamic-field.default">
                                    <div class="flex space-x-2">
                                        <div class="w-[16px] h-[16px] opacity-80"><svg class="w-full h-full"
                                                aria-hidden="true" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z">
                                                </path>
                                            </svg></div>
                                        <div class="jet-listing-dynamic-field__content">{{ $user->usuario }}</div>
                                    </div>
                                </div>
                                <div class="a a-72b48cc elementor-widget elementor-widget-jet-listing-dynamic-field"
                                    data-id="72b48cc" data-element_type="widget"
                                    data-widget_type="jet-listing-dynamic-field.default">
                                    <div class="flex space-x-2">
                                        <div class="w-[16px] h-[16px] opacity-80"><svg class="w-full h-full"
                                                aria-hidden="true" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M400 64h-48V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H160V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zm-6 400H54c-3.3 0-6-2.7-6-6V160h352v298c0 3.3-2.7 6-6 6z">
                                                </path>
                                            </svg></div>
                                        <div class="jet-listing-dynamic-field__content">
                                            {{ $user->created_at->format('d-m-Y g:i a') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="a a-6aafa94 elementor-widget elementor-widget-jet-listing-dynamic-field"
                                    data-id="6aafa94" data-element_type="widget"
                                    data-widget_type="jet-listing-dynamic-field.default">
                                    <div class="flex space-x-2">
                                        <div class="w-[16px] h-[16px] opacity-80"><svg class="w-full h-full"
                                                aria-hidden="true" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z">
                                                </path>
                                            </svg></div>
                                        <div class="jet-listing-dynamic-field__content">{{ $user->persona->direccion?? 'Sin Dirección' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="a a-ce0c3b1 elementor-widget elementor-widget-jet-listing-dynamic-field"
                                    data-id="ce0c3b1" data-element_type="widget"
                                    data-widget_type="jet-listing-dynamic-field.default">
                                    <div class="flex space-x-2">
                                        <div class="w-[16px] h-[16px] opacity-80"><svg class="w-full h-full"
                                                aria-hidden="true" viewBox="0 0 288 512"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M112 316.94v156.69l22.02 33.02c4.75 7.12 15.22 7.12 19.97 0L176 473.63V316.94c-10.39 1.92-21.06 3.06-32 3.06s-21.61-1.14-32-3.06zM144 0C64.47 0 0 64.47 0 144s64.47 144 144 144 144-64.47 144-144S223.53 0 144 0zm0 76c-37.5 0-68 30.5-68 68 0 6.62-5.38 12-12 12s-12-5.38-12-12c0-50.73 41.28-92 92-92 6.62 0 12 5.38 12 12s-5.38 12-12 12z">
                                                </path>
                                            </svg></div>
                                        <div class="jet-listing-dynamic-field__content">{{ $user->persona->nota?? 'Sin nota' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p>El user {{ $user->id }} no tiene una persona asociada.</p>
                    @endif
                @endforeach
            @else
                <p class="text-base flex justify-center">No se encontraron clientes en la Distribuidora.
                </p>
            @endif
        </div>
        <div class="w-full  justify-center mt-8 ml-8 hidden" id="cliente_mensaje_no_resultados">
            <p class="text-base text-color-titulos-entrega">No Se Encontraron Resultados.</p>
        </div>


    </div>
@endsection
