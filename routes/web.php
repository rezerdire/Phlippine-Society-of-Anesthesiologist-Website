<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RegistrationPdfController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/admin/registrations-export-pdf', [RegistrationPdfController::class, 'export'])
        ->name('admin.registrations.export-pdf');
});

Route::view('/', 'pages.Home.index')->name('home');
Route::view('/About-Us/Office-and-board', 'pages.AboutUs.office-and-board')->name('Office-and-board');
Route::view('/About-Us/SubSpecialty-SIG', 'pages.AboutUs.subspecialty-sig')->name('SubSpecialty-SIG');
Route::view('/Chapter-Presidents', 'pages.AboutUs.chapter-presidents')->name('Chapter-Presidents');
Route::view('/Legacy', 'pages.AboutUs.legacy')->name('Legacy');
Route::view('/Gallery', 'pages.Gallery.gallery')->name('Gallery');
Route::view('/Membership', 'pages.Membership.member-registation')->name('Membership');
Route::view('/Event-Registration', 'pages.Event-Registration.events-registration')->name('Event-Registration');
