<?php

use Illuminate\Support\Facades\Route;




include('admin.php');


Route::get('/', function () {
    return view('admin.home.dashboard');
});
