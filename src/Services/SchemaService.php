<?php

namespace App\Services;

class SchemaService
{
    private string $schemaPath;

    public function __construct()
    {
        $this->schemaPath = __DIR__ . '/../../schemas/';
    }

    public function getSchemas(): array
    {
        $schemas = [];
        $files = glob($this->schemaPath . '*.yaml');

        foreach ($files as $file) {
            $name = basename($file, '.yaml');
            $schema = yaml_parse_file($file);
            $schemas[$name] = [
                'name' => $name,
                'label' => $schema['label'] ?? ucfirst($name),
                'icon' => $schema['icon'] ?? 'document-text',
                'position' => $schema['sidebar_position'] ?? 999
            ];
        }

        // Sort schemas by position
        uasort($schemas, function($a, $b) {
            return $a['position'] <=> $b['position'];
        });
        
        return $schemas;
    }

    public function getSchema(string $type): ?array
    {
        $schemaFile = $this->schemaPath . $type . '.yaml';
        
        if (!file_exists($schemaFile)) {
            return null;
        }

        $schema = yaml_parse_file($schemaFile);

        foreach ($schema['fields'] as $key => $f) {
            if (is_string($f)) {
                $schema['fields'][$key] = $this->getBlockSchema($f);
            } else {
                $schema['fields'][$key]['input_name'] = $f['name'];
            }
        }

        return $schema;
    }

    public function getBlockSchema(string $type): ?array
    {
        $blockPath = 'schemas/' . $type . '.yaml';
        if (!file_exists($blockPath)) {
            return null;
        }

        $schema = yaml_parse_file($blockPath);
        return array_merge($schema, ['input_name' => $schema['name']]);
    }
} 