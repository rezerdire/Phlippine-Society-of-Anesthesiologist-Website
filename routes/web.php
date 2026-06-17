<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::view('/', 'pages.Home.index')->name('home');
Route::view('/About-Us/Office-and-board', 'pages.AboutUs.office-and-board')->name('Office-and-board');
Route::view('/About-Us/SubSpecialty-SIG', 'pages.AboutUs.subspecialty-sig')->name('SubSpecialty-SIG');
Route::view('/Chapter-Presidents', 'pages.AboutUs.chapter-presidents')->name('Chapter-Presidents');
Route::view('/Legacy', 'pages.AboutUs.legacy')->name('Legacy');
Route::view('/Gallery', 'pages.Gallery.gallery')->name('Gallery');
Route::view('/Membership', 'pages.Membership.member-registation')->name('Membership');
Route::view('/Event-Registration', 'pages.Event-Registration.events-registration')->name('Event-Registration');
