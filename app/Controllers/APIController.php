<?php

namespace App\Controllers;

class APIController
{
    public function index()
    {
        // 1. Setup Parameter
        $apiKey = "15468d68f6de72987d690b2d4f6a7158102b3bfaf66983a963acff66cd741780"; // Ganti dengan API Key Anda
        
        // Contoh: Ingin mencari paper dari "B. J. Habibie"
        // Tips: Gunakan tanda kutip ("") di sekitar nama untuk pencarian yang lebih spesifik
        $authorName = "Elok Nur Hamdana"; 
        
        $params = [
            "engine"  => "google_scholar",
            // Format query untuk author adalah: author:"Nama Penulis"
            "q"       => 'author:"' . $authorName . '"', 
            "api_key" => $apiKey
        ];

        // 2. Build URL Query
        $url = "https://serpapi.com/search.json?" . http_build_query($params);

        // 3. Inisialisasi cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        // 4. Eksekusi Request
        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        // 5. Cek Error cURL
        if ($error) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $error]);
            return;
        }

        // 6. Decode JSON
        $data = json_decode($response, true);

        // 7. Ambil organic_results
        $organicResults = $data['organic_results'] ?? [];

        // Return JSON
        header('Content-Type: application/json');
        echo json_encode($organicResults);
    }
}