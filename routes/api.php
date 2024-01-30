<?php

use App\Http\Controllers\Calendar\CalendarController;
use App\Http\Controllers\Calendar\CalendarUserController;
use Illuminate\Support\Facades\Route;

Route::resource('calendars', CalendarController::class)->except('create', 'edit');
Route::resource('calendars.users', CalendarUserController::class)->except('edit', 'create');
