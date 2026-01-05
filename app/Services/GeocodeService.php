<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodeService
{
    /**
     * Convert address to latitude and longitude using Nominatim (OpenStreetMap)
     */
    public static function geocodeAddress(string $address): ?array
    {
        try {
            $response = Http::timeout(10)->get('https://nominatim.openstreetmap.org/search', [
                'q' => $address,
                'format' => 'json',
                'limit' => 1,
                'countrycodes' => 'id', // Indonesia focus
            ]);

            if ($response->successful() && count($response->json()) > 0) {
                $result = $response->json()[0];
                return [
                    'latitude' => (float) $result['lat'],
                    'longitude' => (float) $result['lon'],
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Geocoding failed for address: ' . $address, ['error' => $e->getMessage()]);
        }

        return null;
    }
}
