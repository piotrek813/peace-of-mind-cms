<?php

namespace App\Controllers;

use App\Controller;
use App\Services\SchemaService;
class MediaController extends Controller
{
    public function __construct()
    {
        $this->schemaService = new SchemaService();
    }

    public function index()
    {
        $schemas = $this->schemaService->getSchemas();
        $this->render('media-library/index', ["schemas" => $schemas, "media" => []]);
    }

    public function upload()
    {
        try {
            $file = $_FILES['file'];
            var_dump($file);

            $media = new Media();
            $media->store($file);
            
            return $this->json([
                'success' => true,
                'media' => $media->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
} 