<?php

namespace App\Components;

use App\Components\Fields\TextField;
use App\Components\Fields\SlugField;
use App\Components\Fields\TextareaField;
use App\Components\Fields\GroupField;

class FormBuilder
{
    private array $schema;

    public function __construct(string $schemaPath)
    {
        $this->schema = yaml_parse_file($schemaPath);
    }

    public function render(): string
    {
        $html = '';
        foreach ($this->schema['fields'] as $name => $field) {
            $field['name'] = $name;
            $html .= $this->createField($field)->render();
        }
        $html .= '<input type="hidden" name="type" value="' . $this->schema['name'] . '"/>';
        return $html;
    }

    private function createField(array $field): object
    {
        $type = $field['type'];
        $name = $field['name'];
        $label = $field['label'];
        $required = $field['required'] ?? false;
        
        return match ($type) {
            'text' => new TextField(
                $name,
                $label,
                $required,
                $field['default'] ?? null
            ),
            'textarea' => new TextareaField(
                $name,
                $label,
                $required,
                $field['default'] ?? null
            ),
            'slug' => new SlugField(
                $name,
                $label,
                $field['source'],
                $required,
                $field['default'] ?? null
            ),
            'group' => new GroupField(
                $name,
                $label,
                array_map(
                    fn($f, $key) => $this->createField(array_merge($f, ['name' => $key])), 
                    $field['fields'],
                    array_keys($field['fields'])
                ),
                $required
            ),
            default => throw new \Exception("Unknown field type: {$type}")
        };
    }
} 