<?php

namespace App\Components\Fields;

class SelectField
{
    private string $name;
    public string $label;
    private string $input_name;
    private array $options;
    private bool $required;
    private mixed $value;

    public function __construct(array $field) {
        $this->name = $field['name'];
        $this->label = $field['label'];
        $this->input_name = $field['input_name'];
        $this->options = $field['options'];
        $this->required = $field['required'];
        $this->value = $field['value'];
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';
        $options = $this->renderOptions();

        return <<<HTML
        <div class="form-control rounded-lg mb-2 bg-base-200 p-4">
            <label class="label" for="{$this->input_name}">{$this->label}</label>
            <input type="hidden" name="{$this->input_name}[name]" value="{$this->name}">
            <select 
                name="{$this->input_name}[value]" 
                class="select select-bordered w-full max-w-xs" 
                {$required}
            >
                    {$options}
                </select>
        </div>
        HTML;
    }

    private function renderOptions(): string
    {
        $options = '';
        foreach ($this->options as $option) {
            $selected = $this->value == $option['value'] ? 'selected' : '';
            $options .= "<option value=\"{$option['value']}\" {$selected}>{$option['label']}</option>";
        }
        return $options;
    }
} 