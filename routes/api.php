<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationStatusController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmployerStatusController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
|                              Public routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
|                             Protected routes
|--------------------------------------------------------------------------
*/

// Authorized user routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    // Application statuses
    Route::get('/application-statuses', [ApplicationStatusController::class, 'index']);
    Route::get('/application-statuses/{id}', [ApplicationStatusController::class, 'show']);
    // Employers
    Route::get('/employers', [EmployerController::class, 'index']);
    Route::get('/employers/{id}', [EmployerController::class, 'show']);
    // Employer statuses
    Route::get('/employer-statuses', [EmployerStatusController::class, 'index']);
    Route::get('/employer-statuses/{id}', [EmployerStatusController::class, 'show']);
    // Faculties
    Route::get('/faculties', [FacultyController::class, 'index']);
    Route::get('/faculties/{id}', [FacultyController::class, 'show']);
    // Organizations
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
    // Specialities
    Route::get('/specialities', [SpecialityController::class, 'index']);
    Route::get('/specialities/{id}', [SpecialityController::class, 'show']);
    // Stages
    Route::get('/stages', [StageController::class, 'index']);
    Route::get('/stages/{id}', [StageController::class, 'show']);
    // Students
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'show']);
    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/search/{nickname}', [UserController::class, 'search']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    // Vacancies
    Route::get('/vacancies', [VacancyController::class, 'index']);
    Route::get('/vacancies/{id}', [VacancyController::class, 'show']);
    // Events
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    // News
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/{id}', [NewsController::class, 'show']);
});

// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {
    // Applications
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{id}', [ApplicationController::class, 'show']);
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']);
    // Application statuses
    Route::post('/application-statuses', [ApplicationStatusController::class, 'store']);
    Route::put('/application-statuses/{id}', [ApplicationStatusController::class, 'update']);
    Route::delete('/application-statuses/{id}', [ApplicationStatusController::class, 'destroy']);
    // Employers
    Route::post('/employers', [EmployerController::class, 'store']);
    Route::delete('/employers/{id}', [EmployerController::class, 'destroy']);
    // Employer statuses
    Route::post('/employer-statuses', [EmployerStatusController::class, 'store']);
    Route::put('/employer-statuses/{id}', [EmployerStatusController::class, 'update']);
    Route::delete('/employer-statuses/{id}', [EmployerStatusController::class, 'destroy']);
    // Faculties
    Route::post('/faculties', [FacultyController::class, 'store']);
    Route::put('/faculties/{id}', [FacultyController::class, 'update']);
    Route::delete('/faculties/{id}', [FacultyController::class, 'destroy']);
    // Organizations
    Route::post('/organizations', [OrganizationController::class, 'store']);
    Route::put('/organizations/{id}', [OrganizationController::class, 'update']);
    Route::delete('/organizations/{id}', [OrganizationController::class, 'destroy']);
    // Roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    // Specialities
    Route::post('/specialities', [SpecialityController::class, 'store']);
    Route::put('/specialities/{id}', [SpecialityController::class, 'update']);
    Route::delete('/specialities/{id}', [SpecialityController::class, 'destroy']);
    // Stages
    Route::post('/stages', [StageController::class, 'store']);
    Route::put('/stages/{id}', [StageController::class, 'update']);
    Route::delete('/stages/{id}', [StageController::class, 'destroy']);
    // Students
    Route::post('/students', [StudentController::class, 'store']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    // Users
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    // Events
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    // News
    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);
});

// Employer routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,employer']], function () {
    // Applications
    Route::get('/my-vacancies-applications', [ApplicationController::class, 'indexVacanciesApplications']);
    Route::put('/applications/{id}', [ApplicationController::class, 'update']);
    // Employers
    Route::put('/employers/{id}', [EmployerController::class, 'update']);
    // Vacancies
    Route::post('/vacancies', [VacancyController::class, 'store']);
    Route::put('/vacancies/{id}', [VacancyController::class, 'update']);
    Route::delete('/vacancies/{id}', [VacancyController::class, 'destroy']);
});

// Student routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,student']], function () {
    // Applications
    Route::get('/my-applications', [ApplicationController::class, 'indexStudentApplications']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    // Students 
    Route::put('/students/{id}', [StudentController::class, 'update']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
