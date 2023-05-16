<?php

declare(strict_types=1);

use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\Faculty\FacultyListScreen;
use App\Orchid\Screens\Faculty\FacultyEditScreen;
use App\Orchid\Screens\Vacancy\VacancyListScreen;
use App\Orchid\Screens\Vacancy\VacancyEditScreen;
use App\Orchid\Screens\Application\ApplicationListScreen;
use App\Orchid\Screens\Application\ApplicationEditScreen;
use App\Orchid\Screens\Event\EventListScreen;
use App\Orchid\Screens\Event\EventEditScreen;
use App\Orchid\Screens\News\NewsListScreen;
use App\Orchid\Screens\News\NewsEditScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

/*
|--------------------------------------------------------------------------
|                                   Users
|--------------------------------------------------------------------------
*/

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('User'), route('platform.systems.users.edit', $user));
    });

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

/*
|--------------------------------------------------------------------------
|                                   Roles
|--------------------------------------------------------------------------
*/

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

/*
|--------------------------------------------------------------------------
|                               Faculties
|--------------------------------------------------------------------------
*/

// Platform > System > Faculties
Route::screen('faculties', FacultyListScreen::class)
    ->name('platform.systems.faculties')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push("Факультеты", route('platform.systems.faculties'));
    });

// Platform > System > Faculties > Create
Route::screen('faculties/create', FacultyEditScreen::class)
    ->name('platform.systems.faculties.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.faculties')
            ->push(__('Create'), route('platform.systems.faculties.create'));
    });

// Platform > System > Faculties > Faculty
Route::screen('faculties/{faculty}/edit', FacultyEditScreen::class)
    ->name('platform.systems.faculties.edit')
    ->breadcrumbs(function (Trail $trail, $faculty) {
        return $trail
            ->parent('platform.systems.faculties')
            ->push($faculty->title, route('platform.systems.faculties.edit', $faculty));
    });

/*
|--------------------------------------------------------------------------
|                                Vacancies
|--------------------------------------------------------------------------
*/

// Platform > Employment > Vacancies
Route::screen('vacancies', VacancyListScreen::class)
    ->name('platform.employment.vacancies')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push("Вакансии", route('platform.employment.vacancies'));
    });

// Platform > Employment > Vacancies > Create
Route::screen('vacancies/create', VacancyEditScreen::class)
    ->name('platform.employment.vacancies.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.employment.vacancies')
            ->push(__('Create'), route('platform.employment.vacancies.create'));
    });

// Platform > Employment > Vacancies > Vacancy
Route::screen('vacancies/{vacancy}/edit', VacancyEditScreen::class)
    ->name('platform.employment.vacancies.edit')
    ->breadcrumbs(function (Trail $trail, $vacancy) {
        return $trail
            ->parent('platform.employment.vacancies')
            ->push($vacancy->title, route('platform.employment.vacancies.edit', $vacancy));
    });

/*
|--------------------------------------------------------------------------
|                              Applications
|--------------------------------------------------------------------------
*/

// Platform > Employment > Applications
Route::screen('applications', ApplicationListScreen::class)
    ->name('platform.employment.applications')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push("Отклики", route('platform.employment.applications'));
    });

// Platform > Employment > Applications > Create
Route::screen('applications/create', ApplicationEditScreen::class)
    ->name('platform.employment.applications.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.employment.applications')
            ->push(__('Create'), route('platform.employment.applications.create'));
    });

// Platform > Employment > Applications > Application
Route::screen('applications/{application}/edit', ApplicationEditScreen::class)
    ->name('platform.employment.applications.edit')
    ->breadcrumbs(function (Trail $trail, $application) {
        return $trail
            ->parent('platform.employment.applications')
            ->push("Отклик", route('platform.employment.applications.edit', $application));
    });

/*
|--------------------------------------------------------------------------
|                                 Events
|--------------------------------------------------------------------------
*/

// Platform > Publications > Events
Route::screen('events', EventListScreen::class)
    ->name('platform.publications.events')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push("Мероприятия", route('platform.publications.events'));
    });

// Platform > Publications > Events > Create
Route::screen('events/create', EventEditScreen::class)
    ->name('platform.publications.events.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.publications.events')
            ->push(__('Create'), route('platform.publications.events.create'));
    });

// Platform > Publications > Events > Event
Route::screen('events/{event}/edit', EventEditScreen::class)
    ->name('platform.publications.events.edit')
    ->breadcrumbs(function (Trail $trail, $event) {
        return $trail
            ->parent('platform.publications.events')
            ->push($event->title, route('platform.publications.events.edit', $event));
    });

/*
|--------------------------------------------------------------------------
|                                   News
|--------------------------------------------------------------------------
*/

// Platform > Publications > News
Route::screen('news', NewsListScreen::class)
    ->name('platform.publications.news')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push("Новости", route('platform.publications.news'));
    });

// Platform > Publications > News > Create
Route::screen('news/create', NewsEditScreen::class)
    ->name('platform.publications.news.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.publications.news')
            ->push(__('Create'), route('platform.publications.news.create'));
    });

// Platform > Publications > News > News
Route::screen('news/{news}/edit', NewsEditScreen::class)
    ->name('platform.publications.news.edit')
    ->breadcrumbs(function (Trail $trail, $news) {
        return $trail
            ->parent('platform.publications.news')
            ->push($news->title, route('platform.publications.news.edit', $news));
    });