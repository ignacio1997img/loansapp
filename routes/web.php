<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\VaultController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RouteController;
use App\Models\Loan;
use Illuminate\Support\Facades\Route;

// use PeopleController

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
Route::get('login', function () {
    return redirect('admin/login');
})->name('login');

Route::get('/', function () {
    // return view('welcome');
    return redirect('admin');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    // Route::resources('people', PeopleController::class);
    Route::get('people', [PeopleController::class, 'index'])->name('voyager.people.index');
    Route::get('people/ajax/list/{search?}', [PeopleController::class, 'list']);
    Route::get('people/{id?}/sponsor', [PeopleController::class, 'indexSponsor'])->name('people-sponsor.index');
    Route::post('people/{id?}/sponsor/store', [PeopleController::class, 'storeSponsor'])->name('people-sponsor.store');
    Route::delete('people/{people?}/sponsor/{sponsor?}/delete', [PeopleController::class, 'destroySponsor'])->name('people-sponsor.delete');
    Route::get('people/{people?}/sponsor/{sponsor?}/inhabilitar', [PeopleController::class, 'inhabilitarSponsor'])->name('people-sponsor.inhabilitar');
    Route::get('people/{people?}/sponsor/{sponsor?}/habilitar', [PeopleController::class, 'habilitarSponsor'])->name('people-sponsor.habilitar');


    Route::resource('loans', LoanController::class);
    Route::get('loans/ajax/list/{search?}', [LoanController::class, 'list']);
    Route::get('loans/ajax/notPeople/{id?}', [LoanController::class, 'ajaxNotPeople'])->name('loans-ajax.notpeople');
    Route::get('loans/{loan?}/print/calendar', [LoanController::class, 'printCalendar'])->name('loans-print.calendar');
    Route::get('loans/{loan?}/requirement/daily/create', [LoanController::class, 'createDaily'])->name('loans-requirement-daily.create');
    Route::post('loans/{loan?}/requirement/daily/store', [LoanController::class, 'storeRequirement'])->name('loans-requirement-daily.store');
    Route::get('loans/daily/{loan?}/requirement/delete/{col?}', [LoanController::class, 'deleteRequirement'])->name('loans-daily-requirement.delete');
    Route::get('loans/daily/{loan?}/requirement/success', [LoanController::class, 'successRequirement'])->name('loans-daily-requirement.success');
    Route::get('loans/{loan?}/money/deliver', [LoanController::class, 'moneyDeliver'])->name('loans-money.deliver');
    Route::get('loans/contract/daily/{loan?}', [LoanController::class, 'printContracDaily']);
    Route::get('loans/{loan?}/success', [LoanController::class, 'successLoan'])->name('loans.success');
    Route::post('loans/{loan?}/agent/update', [LoanController::class, 'updateAgent'])->name('loans-agent.update');

    Route::get('loans/{loan?}/daily/money', [LoanController::class, 'dailyMoney'])->name('loans-daily.money');
    Route::post('loans/daily/money/store', [LoanController::class, 'dailyMoneyStore'])->name('loans-daily-money.store');
    Route::get('loans/daily/money/print/{loan_id}/{transaction_id?}', [LoanController::class, 'printDailyMoney']);//impresionde de pago diario de cada cuota pagada mediante los cajeros de las oficinas

    
   

    Route::resource('agents', AgentController::class);
    Route::get('agents', [AgentController::class, 'index'])->name('voyager.agents.index');
    Route::get('agents/ajax/list/{search?}', [AgentController::class, 'list']);
    Route::post('agents/store', [AgentController::class, 'store'])->name('agents.store');
    // Route::delete('agents/destroy/{id}', [AgentController::class, 'destroy'])->name('voyager.agents.destroy');


    Route::get('routes', [RouteController::class, 'index'])->name('voyager.routes.index');
    Route::get('routes/ajax/list/{search?}', [RouteController::class, 'list']);

    Route::get('routes/{route?}/collector', [RouteController::class, 'indexCollector'])->name('routes.collector.index');
    Route::get('routes/collector/ajax/list/{id?}/{search?}', [RouteController::class, 'listCollector']);
    Route::post('routes/{route?}/collector/store', [RouteController::class, 'storeCollector'])->name('routes.collector.store');

    Route::get('routes/{route?}/collector/{collector?}/inhabilitar', [RouteController::class, 'inhabilitarCollector'])->name('routes.collector.inhabilitar');
    Route::get('routes/{route?}/collector/{collector?}/habilitar', [RouteController::class, 'habilitarCollector'])->name('routes.collector.habilitar');
    Route::delete('routes/{route?}/collector/{collector?}/delete', [RouteController::class, 'deleteCollector'])->name('routes.collector.delete');









    Route::resource('vaults', VaultController::class);

    Route::post('vaults/{id}/details/store', [VaultController::class, 'details_store'])->name('vaults.details.store');
    Route::post('vaults/{id}/open', [VaultController::class, 'open'])->name('vaults.open');
    Route::get('vaults/{id}/close', [VaultController::class, 'close'])->name('vaults.close');
    Route::post('vaults/{id}/close/store', [VaultController::class, 'close_store'])->name('vaults.close.store');
    Route::get('vaults/{vault}/print/status', [VaultController::class, 'print_status'])->name('vaults.print.status');


    Route::resource('cashiers', CashierController::class);
    Route::post('cashiers/{cashier}/change/status', [CashierController::class, 'change_status'])->name('cashiers.change.status');//para que acepta los cajeros el  monto dado de
    Route::get('cashiers/{cashier}/close/', [CashierController::class, 'close'])->name('cashiers.close');//para cerrar la caja el cajero vista 
    Route::post('cashiers/{cashier}/close/store', [CashierController::class, 'close_store'])->name('cashiers.close.store'); //para que el cajerop cierre la caja 
    // Route::get('cashiers/{cashier}/confirm_close', [CashierController::class, 'confirm_close'])->name('cashiers.confirm_close');
    // Route::post('cashiers/{cashier}/confirm_close/store', [CashierController::class, 'confirm_close_store'])->name('cashiers.confirm_close.store');


    Route::get('cashiers/print/open/{id?}', [CashierController::class, 'print_open'])->name('print.open');//para imprimir el comprobante cuando se abre una caja








});

Route::get('message/{id?}/verification', [MessageController::class, 'verification']);

Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/admin')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');
