<?php

namespace App\Http\Controllers\Rajaongkir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EkspedisiController extends Controller
{
    public function cekTarif(Request $request) {
        $validated = $request->validate([
            'receiver_destination_id' => 'required',
            'weight' => 'required|numeric',
            'item_value' => 'required|numeric',
        ]);

        $shipperId = "2421"; // Banjarmasin

        $response = Http::withHeaders([
            'x-api-key' => config('services.rajaongkir.api_key'),
            'accept' => 'application/json',
        ])->get(config('services.rajaongkir.base_url') . '/calculate', [
            'shipper_destination_id' => $shipperId,
            'receiver_destination_id' => $validated['receiver_destination_id'],
            'weight' => $validated['weight'],
            'item_value' => $validated['item_value'],
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Gagal mengambil data ongkir',
                'detail' => $response->body(),
            ], $response->status());
        }
    }

    public function rincianKodepos(Request $request) {
        $request->validate([
            'keyword' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'x-api-key' => config('services.rajaongkir.api_key'),
            'accept' => 'application/json',
        ])->get(config('services.rajaongkir.base_url') . '/destination/search', [
            'keyword' => $request->input('keyword'),
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Gagal mengambil data ongkir',
                'detail' => $response->body(),
            ], $response->status());
        }
    }
}
