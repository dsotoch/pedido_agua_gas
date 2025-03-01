<?php

use App\Http\Controllers\ControllerCliente;
use App\Http\Controllers\ControllerEmpresa;
use App\Http\Controllers\ControllerMensajes;
use App\Http\Controllers\ControllerPedido;
use App\Http\Controllers\ControllerProducto;
use App\Http\Controllers\ControllerSalidas;
use App\Http\Controllers\ControllerUsuario;
use App\Http\Controllers\CuponController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $usuario = Auth::user();
    return view('principal', compact('usuario'));
})->name('index');

Route::controller(ControllerCliente::class)->group(function () {
    Route::get('/clientes', 'index')->name('empresa.clientes2');
    Route::post('/crear-cliente', 'store')->name('empresa.crearCliente');
    Route::get('/clientesporempresa', 'clientesporempresa')->name('empresa.clienteEmpresa');
    Route::get('/dom', 'verificardominio')->name('empresa.index');
});

Route::controller(ControllerUsuario::class)->group(function () {
    Route::post('/crearusuario', 'store')->name('crear.usuario');
    Route::put('/modificarestado/{id}', 'cambiarestado')->name('estado.usuario');
    Route::get('/repartidores', 'getRepartidores')->name('usuario.repartidores');
    Route::get('/mi-cuenta', 'index')->name('usuario.index');
    Route::post('/login', 'login')->name('usuario.login');
    Route::post('/login-no-aut', 'login_no_aut')->name('usuario.login-no-aut');
    Route::get('/logout', 'logout')->name('usuario.logout');
    Route::get('/buscar-usuario/{telefono}', 'buscarUsuario')->name('usuario.buscar');
    Route::put('/update/{id}', 'update')->name('usuario.update');
    Route::post('/validar-datos', 'validateUser')->name('password.validate');
    Route::put('/restablecer-password', 'resetPassword')->name('password.reset');
    Route::put('/predeterminada', 'datosPredeterminada')->name('usuario.predeterminada');
    Route::get('/getpredeterminada', 'getPredeterminada')->name('usuario.getpredeterminada');
    Route::delete('/eliminarpredeterminada', 'eliminarPredeterminada')->name('usuario.eliminarpredeterminada');
    Route::put('/guardarFavorito', 'guardarFavorito')->name('usuario.guardarFavorito');
    Route::get('/getFavoritos', 'getFavoritos')->name('usuario.getFavoritos');
    Route::delete('/eliminarFavorito', 'eliminarFavorito')->name('usuario.eliminarFavorito');
    Route::delete('/eliminarUsuario', 'eliminarUsuario')->name('usuario.eliminar');

    Route::post('/obtenerReferencia', 'obtenerReferencia')->name('usuario.obtenerReferencia');


});

Route::controller(ControllerProducto::class)->group(function () {
    Route::post('crear', 'store')->name('crear.producto');
    Route::delete('eliminar/{id}', 'destroy')->name('eliminar.producto');
    Route::put('modificarProducto', 'update')->name('editar.producto');
});
Route::prefix('{slug}')->controller(ControllerPedido::class)->group(function () {
    Route::post('/crearpedido', 'store')->name('pedido.crear');
    Route::put('/cancelarpedido', 'cancelarPedido')->name('pedido.cancelarPedido');
    Route::get('/pedido_confirmado/{id}', 'vista_pedido_confirmado')->name("pedido.confirmacion");
    Route::post('/crearpedidorapido', 'pedido_rapido')->name('pedido.pedidorapido');

});
Route::controller(ControllerPedido::class)->group(function () {
    Route::put('cambiarestadopago/{id}', 'pedidorecibidorepartidor')->name('pedido.recibididorepartidor');
    Route::post('asignarRepartidor', 'asignar')->name('pedido.asignarrepartidor');
    Route::put('cambiarestadopago', 'cambiarestadopago')->name('pedido.cambiarestadopago');
    Route::put('anular', 'anular')->name('pedido.anular');
    Route::put('editar', 'editar')->name('pedido.editar');
    Route::delete('eliminarPedido/{id}', 'destroy')->name("pedido.eliminar");
    Route::get('pedido/{id}', 'buscar_pedido')->name('pedido.buscar');
});

Route::controller(ControllerMensajes::class)->group(function () {
    Route::get('mensajes', 'mensajes')->name('mensaje.all');
    Route::get('mensajes/{id}', 'mensajeAsignado')->name('mensaje.asignado');
    Route::put('actualizar/{id}', 'actualizarEstado')->name('mensaje.estado');
});

Route::prefix('mi-cuenta')->controller(ControllerEmpresa::class)->group(function () {
    Route::get('pagos-de-hoy', 'index_pagos_del_dia')->name('empresa.index_pagos');
    Route::get('clientes', 'index_clientes')->name('empresa.clientes');
    Route::get('productos', 'index_productos')->name('empresa.productos');
    Route::get('datos', 'index_mis_datos')->name('empresa.datos');
    Route::get('usuarios', 'index_usuarios')->name('empresa.usuarios');
    Route::get('reportes', 'index_reportes')->name('empresa.reportes');
    Route::get('empresa', 'index_empresa')->name('empresa.empresa');
    Route::put('modificar', 'modificar_empresa')->name('empresa.editar');
    Route::put('modificarDiseño', 'modificar_empresa_diseño')->name('empresa.editar_diseño');
    Route::get('cupones', 'index_cupones')->name('empresa.cupones');
    Route::get('favoritas', 'index_favoritas')->name('empresa.favoritas');
    Route::get('salidas', 'index_salidas')->name('empresa.salidas');

});

Route::controller(ControllerSalidas::class)->group(function () {
    Route::get('salidas/{id}', 'show')->name('salidas.ver');
    Route::post('crearSalida', 'store')->name('salidas.crear');
    Route::post('procesarStock', 'procesarStockJson')->name('salidas.procesarStock');
    Route::delete('eliminarSalida', 'delete')->name('salidas.eliminar');
    Route::post('modificarSalida', 'update')->name('salidas.editar');

});
Route::controller(CuponController::class)->group(function () {
    Route::post('/aplicar-cupon', 'aplicarCupon')->name('cupones.aplicar');
    Route::post('/calcular-total', 'calcularTotal')->name('cupones.total');
    Route::post('/crear-cupon', 'crearCupon')->name('cupones.crear');
    Route::delete('/eliminar-cupon/{id}', 'eliminar')->name('cupones.eliminar');
});


Route::prefix('distribuidora')->controller(ControllerEmpresa::class)->group(function () {
    Route::get('/', function () {
        return view('registroadmin');
    });
    Route::post('/crearEmpresa', 'store')->name('empresa.crear');
    Route::get('/crearAdminView', 'crearAdminView')->name('empresa.adminview');
    Route::post('/crearAdmin', 'crearAdmin')->name('empresa.admin');
    Route::get('/configView/{id}', 'conf')->name('empresa.configView');
    Route::post('/config/{id}', 'configurarColores')->name('empresa.config');
    Route::get('/buscar-empresas/{filtro}', 'buscarEmpresas');
});
Route::get('/{slug}', [ControllerEmpresa::class, 'index_distribuidora'])->name('index.negocio');
