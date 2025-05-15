<?php

use App\Http\Controllers\Midtrans\CallbackController;
use App\Http\Controllers\Midtrans\CheckoutController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Rajaongkir\EkspedisiController;

Route::get('/ekspedisi', [EkspedisiController::class, 'cekTarif']);
Route::get('/rincian-kodepos', [EkspedisiController::class, 'rincianKodepos']);
Route::post('/checkout', [CheckoutController::class, 'checkout']);
Route::post('/midtrans/callback', [CallbackController::class, 'handleCallback']);
