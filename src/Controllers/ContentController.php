<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Content;

class ContentController extends Controller
{
    public function query()
    {
        try {
            $id = $_GET['id'];

            $content = new Content();
            $contentData = $content->getById($id);
            
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