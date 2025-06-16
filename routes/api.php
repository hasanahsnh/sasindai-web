<?php

use App\Http\Controllers\Midtrans\CallbackController;
use App\Http\Controllers\Midtrans\CheckoutController;
use App\Http\Controllers\Midtrans\PlCallbackController;
use App\Http\Controllers\Midtrans\PlCheckoutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Http\Controllers\Rajaongkir\EkspedisiController;

Route::get('/ekspedisi', [EkspedisiController::class, 'cekTarif']);
Route::get('/rincian-kodepos', [EkspedisiController::class, 'rincianKodepos']);
Route::post('/checkout', [CheckoutController::class, 'checkout']);
Route::post('/midtrans/callback', [CallbackController::class, 'handleCallback']);

Route::post('/cpl_checkout', [PlCheckoutController::class, 'checkout']);
Route::match(['GET', 'POST'], "/midtrans/cpl_callback", [PlCallbackController::class, "handleCallback"]);

Route::get('/test', fn () => 'Ngrok OK');