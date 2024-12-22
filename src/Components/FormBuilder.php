<?php

namespace App\Components;

use App\Components\Fields\TextField;
use App\Components\Fields\SlugField;
use App\Components\Fields\TextareaField;
use App\Components\Fields\GroupField;
use App\Components\Fields\ListField;
use App\Components\Fields\BoolField;

class FormBuilder
{
    private array $schema;
    private array $data;

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

    private function getValue(string $name, array $field): mixed
    {
        if (isset($field['fields'])) {
            return $this->data[$name] ?? [];
        }
        return $this->data[$name] ?? ($field['default'] ?? null);
    }

    private function createField(array $field, int $nest_level = 0): object
    {
        $type = $field['type'];
        $name = $field['name'];
        $label = $field['label'];
        $required = $field['required'] ?? false;
        $value = $field['value'] ?? [];

        if ($type=="group") {
            echo "<pre>";
            var_dump(json_encode($field, JSON_PRETTY_PRINT));
            echo "</pre>";
        }

        if ($type === 'list') {
            $this->createTemplateFieldsForList($field['fields'], $name, $value, $nest_level);
        }

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
            'bool' => new BoolField(
                $name,
                $label,
                $required,
                $value
            ),
            'group' => new GroupField(
                $name,
                $label,
                $required,
                array_map(
                    fn($f, $key) => $this->createField(array_merge($f, [
                        'name' => $name . '[' . $key . ']',
                        'value' => $value[$key] ?? ($f['default'] ?? null)
                    ]), $nest_level + 1), 
                    $field['fields'],
                    array_keys($field['fields'])
                ),
                $nest_level
            ),
            'list' => new ListField(
                $name,
                $label,
                $required,
                $this->create_list_fields($value, $field, $name, $nest_level),
                $field["fields"],
                "",
                $nest_level,
            ),
            default => throw new \Exception("Unknown field type: {$type}")
        };
    }

    private function create_list_fields(array $value, array $field, string $name, int $nest_level): array
    {
        return array_map(
            function($value) use ($name, $field, $nest_level) {
                $key = array_keys($value)[0];
                return $this->createField(
                    array_merge(
                        [
                            'name' => $name . '[' . $key . ']',
                            'value' => $value[$key]
                        ],
                        $field["fields"][$key]
                    ),
                    $nest_level + 1
                );
            },
            $value
        );
    }
   

    private function createTemplateFieldsForList(array $fields, string $name, $value, int $nest_level): array
    {
        return array_map(
            function($field, $key) use ($name,$fields, $value, $nest_level) {
                $fieldConfig = array_merge($field, [
                    'name' => $name . '[{{index}}][' . $key . ']',
                    'value' => $value[$key] ?? ($field['default'] ?? null)
                ]);

                $renderedField = "";

                if ($field['type'] != 'group') {
                    $renderedField = $this->createField($fieldConfig, $nest_level + 1)->render();
                }

                echo "<pre>";
                var_dump(json_encode($fieldConfig, JSON_PRETTY_PRINT));
                echo "</pre>";


                
                return sprintf(
                    '<template id="field-%s-%s-template">%s</template>',
                    $name,
                    $key,
                    $renderedField
                );
            },
            $fields,
            array_keys($fields)
        );
    }
} 