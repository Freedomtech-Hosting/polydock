<?php

use App\Http\Controllers\PolydockAppInstanceController;
use App\Http\Controllers\PolydockAppTypeController;
use App\Http\Controllers\PolydockDashboardController;
use App\Models\PolydockAppInstance;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   if(Auth::check()) {
        return response()->redirectToRoute('dashboard');
   } else {
    return response()->redirectToRoute('login');
   }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [PolydockDashboardController::class,'index'])->name('dashboard');
    Route::get('/polydock/poll/dashboard/stats',[PolydockDashboardController::class, 'pollDashboardStats'])->name('polydock.poll.dashboard.stats');


    Route::get('/polydock/apps', [PolydockAppTypeController::class, 'index'])->name('polydock.apps');
    Route::get('/polydock/instances', [PolydockAppInstanceController::class, 'index'])->name('polydock.instances');
    Route::get('/polydock/instance/new/{polydockAppType}', [PolydockAppInstanceController::class, 'new'])->name('polydock.instances.new');
    Route::get('/polydock/instance/view/{polydockAppInstance}', [PolydockAppInstanceController::class, 'view'])->name('polydock.instances.view');

    Route::post('/polydock/instance/create/{polydockAppType}', [PolydockAppInstanceController::class, 'create'])->name('polydock.instances.create');
    Route::post('/polydock/instance/remove/{polydockAppInstance}', [PolydockAppInstanceController::class, 'remove'])->name('polydock.instances.remove');

    Route::get('/polydock/poll/instance/status/{polydockAppInstance}',[PolydockAppInstanceController::class, 'pollAppStatus'])->name('polydock.poll.instance.status');
});
