<?php

namespace App\Controllers;

class ErrorController
{
    public function notFound()
    {
        http_response_code(404);
        echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        echo "<p>Maaf, halaman yang Anda cari tidak ada.</p>";
    }
}