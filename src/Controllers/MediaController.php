<?php

namespace App\Controllers;

use App\Controller;
use App\Services\SchemaService;
use App\Models\Media;
use App\Components\MediaLibrary;

class MediaController extends Controller
{
    private $media;

    public function __construct()
    {
        $this->schemaService = new SchemaService();
        $this->media = new Media();
    }

    public function index()
    {
        $schemas = $this->schemaService->getSchemas();

        $library = new MediaLibrary();

        $this->render('media-library/index', [
            "schemas" => $schemas,
            "library" => $library->render(),
            "username" => $_SESSION['username']
        ], "dashboard");
    }

    public function upload()
    {
        try {
            $file = $_FILES['file'];

            $media = new Media();
            $id = $media->store($file);
            
            return $this->json([
                'success' => true,
                'media' => $media->getById($id),
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function delete($id)
    {
        $this->media->delete($id);
        return $this->json(['success' => true]);
    }
} 