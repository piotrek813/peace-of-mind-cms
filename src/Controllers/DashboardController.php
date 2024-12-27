<?php

namespace App\Controllers;

use App\Controller;
use App\Components\FormBuilder;
use App\Services\SchemaService;
use App\Models\Content;
use App\Components\MediaLibrary;

class DashboardController extends Controller
{
    private SchemaService $schemaService;
    private Content $content;

    public function __construct()
    {
        $this->schemaService = new SchemaService();
        $this->content = new Content();
    }

    public function index()
    {
        $type = $_GET['type'] ?? null;
        $username = $_SESSION['username'] ?? 'User';
        $schemas = $this->schemaService->getSchemas();
        
        $data = [
            'username' => $username,
            'schemas' => $schemas,
            'activeSchema' => null,
            'entries' => []
        ];

        if ($type) {
            $schema = $this->schemaService->getSchema($type);
            $entries = $this->content->getByType($type, $_SESSION['user_id']);

            $data['activeSchema'] = $schema;
            $data['entries'] = $entries;
        }
        
        $this->render('dashboard/index', $data, 'dashboard');
    }

    public function editor($id = null)
    {
        $type = $_GET['type'] ?? 'post';
        $username = $_SESSION['username'] ?? 'User';
        $schemas = $this->schemaService->getSchemas();
        $schema = $this->schemaService->getSchema($type);

        $library = new MediaLibrary(true);
        
        $entry = null;
        
        if (isset($_GET['id'])) {
            $entry = $this->content->getById($_GET['id']);

            if (!$entry) {
                header('Location: dashboard?error=Entry not found');
                exit;
            }

            if ($entry['user_id'] != $_SESSION['user_id']) {
                header('Location: dashboard?error=Unauthorized');
                exit;
            }
        }

        $form = new FormBuilder($schema, json_decode($entry['data'] ?? '{}', true));

        $this->render('dashboard/editor', [
            'username' => $username,
            'schemas' => $schemas,
            'form' => $form,
            'entry' => $entry,
            'type' => $type,
            'library' => $library->render()
        ]);
    }

    public function saveEntry()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: dashboard');
            exit;
        }

        $id = $_POST['id'] ?? null;
        $type = $_POST['type'] ?? 'post';
        $data = $this->processFormData($_POST);

        // Remove technical fields from data
        unset($data['id']);

        try {
            if ($id) {
                $entry = $this->content->getById($id);
                if ($entry['user_id'] != $_SESSION['user_id']) {
                    throw new \Exception('Unauthorized');
                }
                $this->content->update($id, $data, $type);
            } else {
                $this->content->create($data, $_SESSION['user_id'], $type);
            }
            header('Location: dashboard?type=' . urlencode($type) . '&success=Entry saved successfully');
        } catch (\Exception $e) {
            header('Location: editor?type=' . urlencode($type) . '&error=' . urlencode($e->getMessage()));
        }
    }

    public function deleteEntry()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: dashboard');
            exit;
        }

        $id = $_POST['id'] ?? null;
        $type = $_POST['type'] ?? null;

        try {
            $entry = $this->content->getById($id);
            if (!$entry || $entry['user_id'] != $_SESSION['user_id']) {
                throw new \Exception('Unauthorized');
            }

            $this->content->delete($id);
            header('Location: dashboard?type=' . urlencode($type) . '&success=Entry deleted successfully');
        } catch (\Exception $e) {
            header('Location: dashboard?type=' . urlencode($type) . '&error=' . urlencode($e->getMessage()));
        }
    }

    private function processFormData(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if ($key === 'id' || $key === 'type') {
                continue;
            }
            if (is_array($value)) {
                $is_assoc = array_keys($value) !== range(0, count($value) - 1);
                $value = !$is_assoc ? array_values($value) : $value;
                $result[$key] = $this->processFormData($value);
            } else {
                $result[$key] = $this->sanitizeValue($value);
            }
        }

        return $result;
    }

    private function sanitizeValue($value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
} 