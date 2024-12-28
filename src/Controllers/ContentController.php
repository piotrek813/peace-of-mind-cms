<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Content;
use App\Models\Media;

class ContentController extends Controller
{
    private Media $media;

    public function __construct()
    {
        $this->media = new Media();
    }

    public function query()
    {
        try {
            $slug = $_GET['slug'];

            $content = new Content();
            $contentData = $content->getBySlug($slug);

            $contentData['data'] = $this->addMedia(json_decode($contentData['data'], true));

            if (!$contentData) {
                return $this->json([
                    'error' => 'Content not found'
                ], 404);
            }
            
            return $this->json($contentData);
            
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function addMedia(array $contentData)
    {
        $result = [];

        foreach ($contentData as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->addMedia($value);
            } else if (is_numeric($value)) {
                $media = $this->media->getUrlById($value);
                $result[$key] = BASE_URL . "/" .$media;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
} 