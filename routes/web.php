<?php
	
	use App\Http\Controllers\AuthenticationController;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Route;
	
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
	Route::controller(AuthenticationController::class)->group(function () {
		Route::get('/', function () {
			return redirect()->route('login');
		});
		Route::get('/login', 'loginView')->name('login');
		Route::get('/lock', 'lockView')->name('lock');
		Route::post('/login', 'loginRequest')->name('request.login');
		Route::post('/logout', 'logoutRequest')->name('request.logout');
	});
	Route::middleware(['auth'])->controller(\App\Http\Controllers\ApplicationController::class)->name('app.')->group(function () {
		Route::get('/', function () {
			return redirect()->route('app.dashboard');
		});
		Route::get('/dashboard', 'dashboardView')->name('dashboard');
		Route::get('/students', 'studentsView')->name('list.students');
		Route::get('/students-datatable', 'studentTableList')->name('list.students.datatable');
		Route::post('/student', 'studentGet')->name('list.students.get');
		Route::post('/students', 'studentsForm')->name('list.students.form');
	});
