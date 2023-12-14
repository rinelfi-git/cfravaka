<?php

	use App\Http\Controllers\ApplicationController;
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
	Route::middleware(['auth'])->controller(ApplicationController::class)->name('app.')->group(function () {
		Route::get('/', function () {
			return redirect()->route('app.dashboard');
		});
		Route::get('/dashboard', 'dashboardView')->name('dashboard');
		Route::get('/students', 'studentsView')->name('list.students');
		Route::get('/students-datatable', 'studentTableList')->name('list.students.datatable');
		Route::post('/student', 'studentGet')->name('list.students.get');
		Route::post('/students', 'studentsForm')->name('list.students.form');

		Route::get('/partners', 'partnersView')->name('list.partners');
		Route::get('/partners-datatable', 'partnerTableList')->name('list.partners.datatable');
		Route::post('/partner', 'partnerGet')->name('list.partners.get');
		Route::post('/partners', 'partnersForm')->name('list.partners.form');

		Route::get('/formations', 'formationsView')->name('list.formations');
		Route::get('/formations-datatable', 'formationTableList')->name('list.formations.datatable');
		Route::post('/formation', 'formationGet')->name('list.formations.get');
		Route::get('/formation-duplicate/{id}', 'formationDuplicate')->name('list.formation.duplicate');
		Route::post('/formations', 'formationsForm')->name('list.formations.form');

		Route::get('/sessions', 'sessionsView')->name('list.sessions');
		Route::get('/sessions-datatable', 'sessionTableList')->name('list.sessions.datatable');
		Route::post('/session', 'sessionGet')->name('list.sessions.get');
		Route::get('/session-duplicate/{id}', 'sessionDuplicate')->name('list.session.duplicate');
		Route::post('/sessions', 'sessionsForm')->name('list.sessions.form');
	});
