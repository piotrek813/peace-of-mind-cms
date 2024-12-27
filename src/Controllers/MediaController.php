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
            "username" => $_SESSION['username'],
            "no_padding" => true,
        ], "dashboard");
    }

    public function upload()
    {
        try {
            $files = $_FILES["files"];

            $media = new Media();
            $response = [];
            
            for ($i = 0; $i < count($files["name"]); $i++) {
                $file = [
                    "name" => $files["name"][$i],
                    "type" => $files["type"][$i],
                    "tmp_name" => $files["tmp_name"][$i],
                    "error" => $files["error"][$i],
                    "size" => $files["size"][$i],
                ];
                $id = $media->store($file);
                $response[] = $media->getById($id);
            }
            
            return $this->json([
                'success' => true,
                'media' => $response,
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