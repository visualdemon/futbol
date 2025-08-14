<?php

use App\Livewire\PublicRegistration;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicRegistration::class)
    ->name('public.registration');
