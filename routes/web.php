<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd('Welcome to the Grocery API');
    return view('welcome');
});
