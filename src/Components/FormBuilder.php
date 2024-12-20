<?php

namespace App\Components;

use App\Components\Fields\TextField;
use App\Components\Fields\SlugField;
use App\Components\Fields\TextareaField;
use App\Components\Fields\GroupField;
use App\Components\Fields\ListField;

class FormBuilder
{
    protected array $schema;
    protected array $data;

    public function __construct(array $schema, array $data = [])
    {
        $this->schema = $schema;
        $this->data = $data;
    }

    public function render(): string
    {
        $html = '';
        foreach ($this->schema['fields'] as $name => $field) {
            $field['name'] = $name;
            $field['value'] = $this->getValue($name, $field);
            $html .= $this->createField($field)->render();
        }
        return $html;
    }

    protected function getValue(string $name, array $field): mixed
    {
        if (isset($field['fields'])) {
            return $this->data[$name] ?? [];
        }
        return $this->data[$name] ?? ($field['default'] ?? null);
    }

    protected function createField(array $field): object
    {
        $type = $field['type'];
        $name = $field['name'];
        $label = $field['label'];
        $required = $field['required'] ?? false;
        $value = $field['value'];

        return match ($type) {
            'text' => new TextField(
                $name,
                $label,
                $required,
                $value
            ),
            'textarea' => new TextareaField(
                $name,
                $label,
                $required,
                $value
            ),
            'slug' => new SlugField(
                $name,
                $label,
                $field['source'],
                $required,
                $value
            ),
            'group' => new GroupField(
                $name,
                $label,
                array_map(
                    fn($f, $key) => $this->createField(array_merge($f, [
                        'name' => $name . '[' . $key . ']',
                        'value' => $value[$key] ?? ($f['default'] ?? null)
                    ])), 
                    $field['fields'],
                    array_keys($field['fields'])
                ),
                $required
            ),
            'list' => new ListField(
                $name,
                $label,
                $field["fields"],
                $required,
                $value ?? []
            ),
            default => throw new \Exception("Unknown field type: {$type}")
        };
    }
} 