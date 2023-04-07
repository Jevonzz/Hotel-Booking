<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StaffSection;

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

Auth::routes();

// Completed routes 

/* Initial route */
Route::get('/', function () {
    return redirect('/login/admin');
});

/* Login Routes */
// Short-handed login route
Route::get('/login', function () {
    return redirect('/login/admin');
});

// Admin login routes
Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm']);
Route::post('/login/admin', [LoginController::class, 'adminLogin']);

// Clerk login routes
Route::get('/login/clerk', [LoginController::class, 'showClerkLoginForm']);
Route::post('/login/clerk', [LoginController::class, 'clerkLogin']);

/* Clerk route middlewares */
Route::group(['middleware' => 'auth'], function () {
    Route::view('/home', 'home');

    /* Clerk complaints routes */
    Route::get('/complaints', [ComplaintController::class, 'viewComplaints']);
    Route::post('/complaints', [ComplaintController::class, 'addComplaint']);
}); 

/* Admin route middlewares */
Route::group(['middleware' => 'auth'], function () {
    Route::view('/admin/dashboard', 'dashboard')->middleware('can:isAdmin');

    /* Admin complaints routes */
    Route::get('/admin/complaints', [ComplaintController::class, 'viewComplaints'])->middleware('can:isAdmin');
    Route::post('/admin/complaints', [ComplaintController::class, 'addComplaint'])->middleware('can:isAdmin');
    Route::post('/admin/complaints/update', [ComplaintController::class, 'updateComplaint'])->middleware('can:isAdmin');
}); 

/* Logout route */
Route::get('logout', [LoginController::class, 'logout']);

// Incompleted routes 
Route::view("clerkProfile",'clerkProfile');
Route::view("bookings",'bookings');
Route::get('/bookings-table', function () {
    return redirect('/bookings#bookings-table');
});
Route::get('/reservation-form', function () {
    return redirect('/home#submitReservationForm');
});
Route::view("about",'about');
Route::get('/', function () {
    return redirect('/login/admin');
});
Route::get('/login', function () {
    return redirect('/login/admin');
});

Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm']);
Route::get('/login/clerk', [LoginController::class, 'showClerkLoginForm']);

Route::post('/login/admin', [LoginController::class, 'adminLogin']);
Route::post('/login/clerk', [LoginController::class, 'clerkLogin']);

Route::group(['middleware' => 'auth:clerk'], function () {
    Route::view('clerk', 'clerk');
}); 

Route::group(['middleware' => 'auth:admin'], function () {
    Route::view('/admin/dashboard', 'dashboard');
}); 

Route::get('logout', [LoginController::class, 'logout']);

Route::get("/admin/staff", [StaffSection::class, 'viewStaff']);
Route::get("/admin/staff/{id}", [StaffSection::class, 'viewMore']);
Route::post("/admin/addStaff", [StaffSection::class, 'addStaff'])->name('add.staff');
Route::get("/admin/editStaff/{id}", [StaffSection::class, 'showUpdate'])->name('edit.staff');
Route::post("/admin/editStaff/{id}", [StaffSection::class, 'editStaff'])->name('update.staff');
Route::get("/admin/deleteStaff/{id}",[StaffSection::class,'deleteStaff']);

Route::view("/admin/bookings",'adminBooking');
Route::view('/admin/profile', 'adminProfile');