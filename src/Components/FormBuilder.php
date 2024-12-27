<?php

namespace App\Components;

use App\Components\Fields\TextField;
use App\Components\Fields\SlugField;
use App\Components\Fields\TextareaField;
use App\Components\Fields\GroupField;
use App\Components\Fields\ListField;
use App\Components\Fields\BoolField;
use App\Components\Fields\MediaField;
use App\Components\Fields\StaticField;
use App\Components\Fields\SelectField;
use App\Services\SchemaService;

class FormBuilder
{
    private array $schema;
    private array $data;
    private SchemaService $schemaService;

    public function __construct(array $schema, array $data = [])
    {
        $this->schema = $schema;
        $this->data = $data;
        $this->schemaService = new SchemaService();
    }

    public function render(): string
    {
        $html = '';

        foreach ($this->schema['fields'] as $field) {
            $field['value'] = $this->getValue($field['name'], $field, $this->data);
            $html .= $this->createField($field)->render();
        }

        return $html;
    }

    private function getValue(string $name, array $field, array $data): mixed
    {
        if (isset($field['fields'])) {
            return $data[$name] ?? [];
        }

        return $data[$name] ?? ($field['default'] ?? null);
    }

    private function getValueOrDefault(mixed $value, array $field): mixed
    {
        if (is_array($value) && isset($value['value'])) {
            return $value['value'];
        }
        return $field['default'] ?? null;
    }

    private function createField(array $field, int $nest_level = 0): object
    {
        $field['required'] = $field['required'] ?? false;

        if (is_array($field) && isset($field['fields'])) {
            foreach ($field['fields'] as $key => $f) {
                if (is_string($f)) {
                    $field["fields"][$key] = $this->schemaService->getBlockSchema($f);
                }
            }
        }

        if (isset($field['fields']) && $field['value'] === null) {
            // TODO this should be done by shcemaservice
            $field['value'] = [];
        }

        if (!isset($field['fields'])) {
            $field = array_merge($field, [
                'value' => $this->getValueOrDefault($field['value'], $field)
            ]);
        }

        return match ($field['type']) {
            'text' => new TextField($field),
            'textarea' => new TextareaField($field),
            'slug' => new SlugField($field), 
            'bool' => new BoolField($field),
            'media' => new MediaField($field),
            'static' => new StaticField($field),
            'select' => new SelectField($field),
            'group' => new GroupField(
                array_merge($field, [
                    'fields' => $this->create_group_fields($field, $nest_level)
                ]), $nest_level
            ),
            'list' => new ListField(
                array_merge($field, [
                    'fields' => $this->create_list_fields($field, $nest_level),
                    'fieldConfig' => $field["fields"],
                    'templates' => $this->createTemplateFieldsForList($field, $nest_level),
                ]), $nest_level
            ),
            default => throw new \Exception("Unknown field type: {$field['type']}")
        };
    }

    private function create_group_fields(array $group_field, int $nest_level): array
    {
        $fields = [];
        foreach ($group_field['fields'] as $f) {
            $fields[] = $this->createField(array_merge($f, [
                'input_name' => $group_field["input_name"] . '[' . $f['name'] . ']',
                'value' => $this->getValue($f['name'], $f, $group_field['value'])
            ]), $nest_level + 1);
        }
        return $fields;
    }

    private function create_list_fields(array $list_field, int $nest_level): array
    {
        $fields = [];
        foreach ($list_field['value'] as $value) {
            foreach ($list_field['fields'] as $field) {
                if ($field['name'] === $value['name']) {
                    $fields[]= $this->createField(array_merge($field, [
                        'input_name' => $list_field["input_name"] . '[{{index}}]',
                        'value' => $value
                    ]), $nest_level + 1);
                }
            }
        }

        return $fields;
    }

    private function createTemplateFieldsForList(array $list_field, int $nest_level): string
    {
        return join("", array_map(
            function($field, $key) use ($list_field, $nest_level) {
                $fieldConfig = array_merge($field, [
                    'input_name' => $list_field["input_name"] . '[{{index}}]',
                    'value' => $this->getValue($field['name'], $field, $list_field['value'])
                ]);

                $renderedField = $this->createField($fieldConfig, $nest_level + 1);
                
                return sprintf(
                    '<template id="field-%s-%s-template">%s</template>',
                    $list_field['name'],
                    $key,
                    $renderedField->render()
                );
            },
            $list_field['fields'],
            array_keys($list_field['fields'])
        ));
    }
} 