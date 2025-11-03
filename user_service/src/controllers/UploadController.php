<?php

namespace NewCo\UserService\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;

class UploadController
{
    /**
     * Accept an image upload, extract data from the upload, and pass the image
     * to the file storage service (localhost:4000) for saving.
     */
    public function upload(Request $request): Response
    {
        $file = $request->files->get('image');

        if (!$file) 
        {
            return new Response('No image uploaded', 400);
        }

        //metadata
        $originalName = $file->getClientOriginalName();
        $mime = null;
        $size = $file->getSize();
        $width = null;
        $height = null;

        if (function_exists('getimagesize')) 
        {
            $dims = @getimagesize($file->getPathname());
            if (is_array($dims)) 
            {
                [$width, $height] = $dims;
            }
        }

        // forward to file storage system (port:4000)
        $client = new Client(['timeout' => 10]);
        $gatewayUrl = 'http://localhost:4000/save';

        try 
        {
            $resp = $client->post($gatewayUrl, [
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $originalName,
                    ],
                    ['name' => 'original_name', 'contents' => $originalName],
                    ['name' => 'mime', 'contents' => (string)$mime],
                    ['name' => 'size', 'contents' => (string)$size],
                    ['name' => 'width', 'contents' => (string)($width ?? '')],
                    ['name' => 'height', 'contents' => (string)($height ?? '')],
                ],
            ]);

            return new Response(
                $resp->getBody()->getContents(),
                $resp->getStatusCode(),
                ['Content-Type' => $resp->getHeaderLine('Content-Type') ?: 'application/json']
            );
        } catch (\Throwable $e) 
        {
            return new Response('Forwarding failed: ' . $e->getMessage(), 502);
        }
    }
}
