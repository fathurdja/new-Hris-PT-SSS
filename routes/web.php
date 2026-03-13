<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Login;
use App\Livewire\Attendance;
use App\Livewire\Success;

Route::get('/', Login::class)->name('login');
Route::get('/attendance', Attendance::class)->name('attendance');
Route::get('/success', Success::class)->name('success');
