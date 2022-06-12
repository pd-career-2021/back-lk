<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationStatusController;
use App\Http\Controllers\AudienceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyTypeController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\VacancyFunctionController;
use App\Http\Controllers\VacancyTypeController;

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
// Audience
Route::get('/audience', [AudienceController::class, 'index']);
Route::get('/audience/{id}', [AudienceController::class, 'show']);
// Events
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
// News
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{id}', [NewsController::class, 'show']);
// Faculties
Route::get('/faculties', [FacultyController::class, 'index']);
Route::get('/faculties/{id}', [FacultyController::class, 'show']);
// Vacancies
Route::get('/vacancies', [VacancyController::class, 'index']);
Route::get('/vacancies/{id}', [VacancyController::class, 'show']);
// Vacancy types
Route::get('/vacancy-types', [VacancyTypeController::class, 'index']);
Route::get('/vacancy-types/{id}', [VacancyTypeController::class, 'show']);
// Vacancy function
Route::get('/vacancy-functions', [VacancyFunctionController::class, 'index']);
Route::get('/vacancy-functions/{id}', [VacancyFunctionController::class, 'show']);
// Employers
Route::get('/employers', [EmployerController::class, 'index']);
Route::get('/employers/{id}', [EmployerController::class, 'show']);
// Socials
Route::get('/socials', [SocialController::class, 'index']);
Route::get('/socials/{id}', [SocialController::class, 'show']);
// Industries
Route::get('/industries', [IndustryController::class, 'index']);
Route::get('/industries/{id}', [IndustryController::class, 'show']);
// Company types
Route::get('/company-types', [CompanyTypeController::class, 'index']);
Route::get('/company-types/{id}', [CompanyTypeController::class, 'show']);

/*
|--------------------------------------------------------------------------
|                             Protected routes
|--------------------------------------------------------------------------
*/

// Authorized user routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/search/{nickname}', [UserController::class, 'search']);
    // Application statuses
    Route::get('/application-statuses', [ApplicationStatusController::class, 'index']);
    Route::get('/application-statuses/{id}', [ApplicationStatusController::class, 'show']);
    // Students
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'show']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    // Roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
});

// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {
    // Application statuses
    Route::post('/application-statuses', [ApplicationStatusController::class, 'store']);
    Route::put('/application-statuses/{id}', [ApplicationStatusController::class, 'update']);
    Route::delete('/application-statuses/{id}', [ApplicationStatusController::class, 'destroy']);
    // Audience
    Route::post('/audience', [AudienceController::class, 'store']);
    Route::put('/audience/{id}', [AudienceController::class, 'update']);
    Route::delete('/audience/{id}', [AudienceController::class, 'destroy']);
    // Employers
    Route::post('/employers', [EmployerController::class, 'store']);
    Route::delete('/employers/{id}', [EmployerController::class, 'destroy']);
    // Faculties
    Route::post('/faculties', [FacultyController::class, 'store']);
    Route::put('/faculties/{id}', [FacultyController::class, 'update']);
    Route::delete('/faculties/{id}', [FacultyController::class, 'destroy']);
    // Roles;
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    // Students
    Route::post('/students', [StudentController::class, 'store']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    // Users
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    // Vacancy types
    Route::post('/vacancy-types', [VacancyTypeController::class, 'store']);
    Route::put('/vacancy-types/{id}', [VacancyTypeController::class, 'update']);
    Route::delete('/vacancy-types/{id}', [VacancyTypeController::class, 'destroy']);
    // Vacancy functions
    Route::post('/vacancy-functions', [VacancyFunctionController::class, 'store']);
    Route::put('/vacancy-functions/{id}', [VacancyFunctionController::class, 'update']);
    Route::delete('/vacancy-functions/{id}', [VacancyFunctionController::class, 'destroy']);
    // Industries
    Route::post('/industries', [IndustryController::class, 'store']);
    Route::put('/industries/{id}', [IndustryController::class, 'update']);
    Route::delete('/industries/{id}', [IndustryController::class, 'destroy']);
    // Company types
    Route::post('/company-types', [CompanyTypeController::class, 'store']);
    Route::put('/company-types/{id}', [CompanyTypeController::class, 'update']);
    Route::delete('/company-types/{id}', [CompanyTypeController::class, 'destroy']);
});

// Employer routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,employer']], function () {
    // Applications
    Route::get('/my-vacancies-applications', [ApplicationController::class, 'indexVacanciesApplications']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::put('/applications/{id}', [ApplicationController::class, 'update']);
    // Employers
    Route::put('/employers/{id}', [EmployerController::class, 'update']);
    // Vacancies
    Route::post('/vacancies', [VacancyController::class, 'store']);
    Route::put('/vacancies/{id}', [VacancyController::class, 'update']);
    Route::delete('/vacancies/{id}', [VacancyController::class, 'destroy']);
    // News
    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);
    // Events
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    // Socials
    Route::post('/socials', [SocialController::class, 'store']);
    Route::put('/socials/{id}', [SocialController::class, 'update']);
    Route::delete('/socials/{id}', [SocialController::class, 'destroy']);
});

// Student routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin,student']], function () {
    // Applications
    Route::get('/my-applications', [ApplicationController::class, 'indexStudentApplications']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::put('/applications/{id}', [ApplicationController::class, 'update']);
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']);
    // Students 
    Route::put('/students/{id}', [StudentController::class, 'update']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
