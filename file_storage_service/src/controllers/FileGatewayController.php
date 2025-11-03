<?php

namespace NewCo\FileGateway\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileGatewayController
{
    /**
     * Accept an image and save it to the images/ directory.
     */
    public function save(Request $request): Response
    {
        $file = $request->files->get('image');

        if (!$file) 
        {
            return new Response(
                json_encode(['saved' => false, 'error' => 'No file provided']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $uploadDir = __DIR__ . '/../../images';
        if (!is_dir($uploadDir)) 
        {
            @mkdir($uploadDir, 0777, true);
        }

        // Preserve original filename
        $originalName = $request->request->get('original_name') ?? $file->getClientOriginalName();
        if (!$originalName) 
        {
            $originalName = 'upload_' . uniqid() . '.bin';
        }

        try 
        {
            $file->move($uploadDir, $originalName);
        } 
        catch (\Throwable $e) 
        {
            return new Response(
                json_encode(['saved' => false, 'error' => $e->getMessage()]),
                500,
                ['Content-Type' => 'application/json']
            );
        }

        $payload = [
            'saved' => true,
            'file' => [
                'original_name' => $originalName,
                'path' => $uploadDir . DIRECTORY_SEPARATOR . $originalName,
            ],
        ];

        return new Response(json_encode($payload), 200, ['Content-Type' => 'application/json']);
    }
}
