<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Content;

class ContentController extends Controller
{
    public function query()
    {
        try {
            $slug = $_GET['slug'];

            $content = new Content();
            $contentData = $content->getBySlug($slug);
            
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
} 