<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Content;
use App\Models\Media;
use App\Utils\DeltaToHtml;
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

            $contentData['data'] = $this->sprinkleInSomeGoodness(json_decode($contentData['data'], true));

            if (!$contentData) {
                return $this->json([
                    'error' => 'Content not found'
                ], 404);
            }
           
            exit();
            return $this->json($contentData);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function sprinkleInSomeGoodness(array $contentData)
    {
        $result = [];

        foreach ($contentData as $key => $value) {
            if (isset($value['type']) && $value['type'] == 'rich_text') {

                $result[$key] = $value;
                $result[$key]['value'] = (new DeltaToHtml(htmlspecialchars_decode($value['value'])))->toHtml();
            } else if (is_array($value) ) {
                $result[$key] = $this->sprinkleInSomeGoodness($value);
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