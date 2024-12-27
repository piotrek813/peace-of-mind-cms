<?php

namespace App\Components\Fields;

class TextareaField
{
    private string $name;
    public string $label;
    private bool $required;
    private string $value;
    private string $input_name;

    public function __construct(array $field)
    {
        $this->name = $field['name'];
        $this->label = $field['label'];
        $this->required = $field['required'];
        $this->value = $field['value'];
        $this->input_name = $field['input_name'];
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';
        return <<<HTML
        <div class="form-control">
            <label class="label" for="{$this->input_name}">
                <span class="label-text">{$this->label}</span>
            </label>
            <input type="hidden" name="{$this->input_name}[name]" value="{$this->name}">
            <textarea 
                name="{$this->input_name}[value]" 
                class="textarea textarea-bordered bg-base-100 min-h-[400px] font-mono"
                $required>{$this->value}</textarea>
        </div>
        HTML;
    }
} 