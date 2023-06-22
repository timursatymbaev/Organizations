<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\MinistryController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\ManagementController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(OrganizationController::class)->group(function () {
        Route::get('/organizations', 'index')->name('organizations.index');
        Route::get('/organizations/search', 'search')->name('organizations.search');
    });

    Route::resource('ministries', MinistryController::class)->except(['index']);
    Route::resource('committees', CommitteeController::class)->except(['index']);
    Route::resource('managements', ManagementController::class)->except(['index']);
});

require __DIR__.'/auth.php';
