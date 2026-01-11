<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    info('Welcome to the Grocery API');
    return view('welcome');
});
