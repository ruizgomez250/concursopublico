<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutocompleteController;
use App\Http\Controllers\DetalleProcesoController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkPublicoController;
use App\Http\Controllers\PostulacionController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\RolController;

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


Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth', 'verified');


Auth::routes(['verify' => true]);


Route::group(['middleware' => ['isAdmin']], function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::resource('roles', App\Http\Controllers\RolesController::class);
    Route::get('roles/{role}/give-permissions', [App\Http\Controllers\RolesController::class, 'addPermissionToRole'])->name('roles.addpermissionrole');
    Route::put('roles/{role}/give-permissions', [App\Http\Controllers\RolesController::class, 'givePermissionToRole'])->name('roles.updatepermissionrole');
    Route::get('/profiles', [App\Http\Controllers\ProfilesController::class, 'index'])->name('profiles');
});


Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');


Route::get('/googlef8526bb5f305abee.html', function () {
    return response()->file(public_path('googlef8526bb5f305abee.html'));
});
//acceden los autenticados
Route::middleware('ratelimit:10,1')->group(function () {
    // Aquí tus rutas protegidas
    Route::resource('/enprocesodepostulacion', LinkPublicoController::class);

});
Route::get('/detalleprocesopdf/{id}', [ReportesController::class, 'detalleproceso'])->name('detalleprocesopdf');
Route::middleware('auth')->group(function () {
    Route::resource('/link', LinkController::class);
    Route::resource('/detalleproceso', DetalleProcesoController::class); 
    
    Route::resource('/postulacion', PostulacionController::class);
    Route::get('/getroles/{id}', [RolController::class, 'getRoles']);
    Route::resource('/rol', RolController::class);

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');


    //
});
Route::get('/sinpermiso', function () {
    return view('sinpermiso.index');
})->name('sinpermiso');



Route::get('/autocomplete',  [AutocompleteController::class, 'autocomplete'])->name('autocomplete');
Route::get('/autocomplete/proveedor',  [AutocompleteController::class, 'proveedor'])->name('obtenerproveedor');
Route::get('/autocomplete/producto',  [AutocompleteController::class, 'getproducto'])->name('obtenerproducto');
//Route::post('/guardar-categoria', 'CategoriaController@storeCat')->name('guardar-categoria');
Route::get('/create', function () {
    return view('create');
});
//Route::post('/guardar-categoria', [CrearCategoriaComponent::class, 'store'])->name('guardar-categoria');



//Route::get('/mascota', 'MascotaController@getRaza');
