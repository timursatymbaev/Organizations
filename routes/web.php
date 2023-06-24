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

    Route::controller(MinistryController::class)->group(function () {
        Route::get('/ministries/create', 'create')->name('ministries.create');
        Route::post('/ministries', 'store')->name('ministries.store');
        Route::put('/ministries/{ministry}', 'update')->name('ministries.update');
        Route::delete('/ministries/{ministry}', 'destroy')->name('ministries.destroy');

        Route::middleware(['check.ministry.access'])->group(function () {
            Route::get('/ministries/{ministry}', 'show')->name('ministries.show');
            Route::get('/ministries/{ministry}/edit', 'edit')->name('ministries.edit');
        });
    });

    Route::controller(CommitteeController::class)->group(function () {
        Route::get('/committees/create', 'create')->name('committees.create');
        Route::post('/committees', 'store')->name('committees.store');
        Route::put('/committees/{committee}', 'update')->name('committees.update');
        Route::delete('/committees/{committee}', 'destroy')->name('committees.destroy');

        Route::middleware(['check.committee.access'])->group(function () {
            Route::get('/committees/{committee}', 'show')->name('committees.show');
            Route::get('/committees/{committee}/edit', 'edit')->name('committees.edit');
        });
    });

    Route::controller(ManagementController::class)->group(function () {
        Route::get('/managements/create', 'create')->name('managements.create');
        Route::post('/managements', 'store')->name('managements.store');
        Route::put('/managements/{management}', 'update')->name('managements.update');
        Route::delete('/managements/{management}', 'destroy')->name('managements.destroy');

        Route::middleware(['check.management.access'])->group(function () {
            Route::get('/managements/{management}', 'show')->name('managements.show');
            Route::get('/managements/{management}/edit', 'edit')->name('managements.edit');
        });
    });
});

require __DIR__.'/auth.php';
