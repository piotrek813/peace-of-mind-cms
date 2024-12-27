<?php

namespace App\Components\Fields;

class BoolField
{
    private string $name;
    public string $label;
    private bool $required;
    private bool $value;
    private string $input_name;

    public function __construct(array $field) {
        $this->name = $field['name'];
        $this->label = $field['label'];
        $this->required = $field['required'];
        $this->value = $field['value'];
        $this->input_name = $field['input_name'];
    }

    public function render(): string
    {
        $required = $this->required ? ' required' : '';
        $checked = $this->value ? ' checked' : '';
        
        return <<<HTML
        <div class="form-control mb-2 sm:mb-4">
            <div class="bg-base-200 rounded-lg">
                <label class="label cursor-pointer p-4">
                    <span class="label-text">{$this->label}</span>
                    <input type="hidden" 
                           name="{$this->input_name}[name]" 
                           value="{$this->name}">
                    <input type="hidden" 
                           name="{$this->input_name}[value]" 
                           value="0">
                    <input type="checkbox" 
                           name="{$this->input_name}[value]" 
                           class="toggle"{$required}{$checked}
                           value="1">
                </label>
            </div>
        </div>
        HTML;
    }
} 