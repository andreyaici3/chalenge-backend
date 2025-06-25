<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::resource('cars', CarController::class);

Route::prefix("/orders")->controller(OrderController::class)->group(function () {
    Route::post("/create/{car}", "store");
    Route::delete("/delete/{order}", "destroy");
    Route::put("/update/{order}", "update");
    Route::get("/", "index");
    Route::get("/show/{orderId}", "show");
});
