<?php

use Illuminate\Support\Facades\Route;

// Raíz: redirigir al login del super-admin
Route::redirect('/', '/super-admin/login');
