<?php

use App\Models\settings;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Ticket;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DepartmentsController;
use App\Http\Controllers\FindController;

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
    return view('welcome');
});
Route::get('/dashboard', function () {
    $settings = settings::first(); // Fetch your settings here.
    return view('dashboard')->with('settings', $settings);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::get('/ticket',[AdminController::class,'Showw'])->name('ee');

    // Route::middleware(['checkUserRole:admin'])->group(function () {
        Route::middleware(['checkUserRole'])->group(function () {


    Route::get('/admin', [AdminController::class,'index'])->name('admin');//
    Route::resource('/admin/faq',FaqController::class);//
    Route::resource('admin/departments', DepartmentsController::class);//
    Route::get('/admin/tickets',[TicketsController::class,'adminTickets'])->name('adminTicket');
    Route::resource('admin/staff',StaffController::class);
    Route::resource('admin/admins',AdminController::class);
    Route::resource('/admin/clients',ClientsController::class);

    /*user routes*/
    Route::get('/all/users', [UsersController::class,'viewUsers']);
    Route::post('/delete/users/{id}',[UsersController::class,'deleteUsers']);
    Route::get('/edit/users/{id}', [UsersController::class,'editUsers']);
    Route::post('/update/users/{id}',[UsersController::class,'updateUsers']);

    Route::get('/search/ticket/',[SearchController::class,'search']);
    Route::get('/search/status/{status}',[SearchController::class,'status']);
    Route::get('/search/department/{id}',[SearchController::class,'department']);

    /*settings*/
    Route::get('/admin/settings',[SettingsController::class,'index'])->name('admin.settings');
    Route::post('/admin/settings',[SettingsController::class,'update']);
    
 });

    /*pages*/
Route::get('/about',[PagesController::class,'about']);
Route::get('/contact',[PagesController::class,'contact'])->name('contact');
Route::post('/contact', [PagesController::class,'contactMail'])->name('contact');

Route::get('/tickets', [TicketsController::class,'index'])->name('tickets');
Route::post('tickets', [TicketsController::class,'store'])->name('storeTickets');
Route::get('/ticket/{id}/{title}',[TicketsController::class,'ticketDetail']);
Route::get('/edit/tickets/{id}', [TicketsController::class,'editTickets']);
Route::get('/new/ticket',[TicketsController::class,'create']);
Route::post('/update/tickets/{id}',[TicketsController::class,'updateTickets'])->name('updateTicket');
Route::post('/delete/tickets/{id}',[TicketsController::class,'deleteTickets'])->name('deleteTicket');//
Route::post('/delete/replies/{id}',[TicketsController::class,'deleteReplies'])->name('deleteReplies');
Route::put('/update/status/{id}', [TicketsController::class, 'updateStatus'])->name('updateStatus');

Route::post('/add-reply',[TicketsController::class,'addReply'])->name('addReply');
Route::post('/assign/ticket/{id}',[TicketsController::class,'assignTicket'])->name('assignTicket');
Route::get('/download/{file_name}',[TicketsController::class,'download']);


// Route::group(['middleware' => ['role:staff|client']], function() {
    Route::get('/find/ticket/',[FindController::class,'search']);
    Route::get('/find/status/{status}',[FindController::class,'status']);
    Route::get('/find/department/{id}',[FindController::class,'department']);
// });

/*User account settings*/
Route::get('profile/settings',[ProfileController::class,'profile']);
Route::post('profile/settings',[ProfileController::class,'updateProfile'])->name('updateProfile');

/*change password*/
Route::get('change/password',[ProfileController::class,'resetPassword']);
Route::post('change/password',[ProfileController::class,'updatePassword'])->name('updatePassword');
Route::get('login', function () {
    $settings = settings::first(); // Fetch your settings here.
    return view('auth.login')->with('settings', $settings);
})->name('login');

Route::get('register', function () {
    $settings = settings::first(); // Fetch your settings here.
    return view('auth.register')->with('settings', $settings);
})->name('register');

// });
