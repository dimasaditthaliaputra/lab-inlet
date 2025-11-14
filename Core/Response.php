<?php

namespace Core;

class Response
{
    /**
     * Return JSON response
     */
    public function json($data = [], $statusCode = 200, $headers = [])
    {
        // Set content type
        header('Content-Type: application/json');
        
        // Set status code
        http_response_code($statusCode);
        
        // Set additional headers
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
        
        // Output JSON
        echo json_encode($data);
        exit;
    }

    /**
     * Return success JSON response
     */
    public function success($message = 'Success', $data = [], $statusCode = 200)
    {
        return $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Return error JSON response
     */
    public function error($message = 'Error', $errors = [], $statusCode = 400)
    {
        return $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    /**
     * Return file download response
     */
    public function download($filePath, $fileName = null)
    {
        if (!file_exists($filePath)) {
            http_response_code(404);
            die('File not found');
        }

        $fileName = $fileName ?? basename($filePath);
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        
        readfile($filePath);
        exit;
    }
}