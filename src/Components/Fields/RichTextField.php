<?php

namespace App\Components\Fields;

class RichTextField
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
        $this->required = $field['required'] ?? false;
        $this->value = htmlspecialchars_decode($field['value'] ?? '');
        $this->input_name = $field['input_name'];
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';
        
        return <<<HTML
        <div class="form-control mb-2 sm:mb-4 rich-text-field" data-content='{$this->value}' data-name="{$this->name}">
            <label class="label">
                <span class="label-text">{$this->label}</span>
            </label>
            <div>
                <div id="{$this->name}"></div>
                <input type="hidden" 
                       name="{$this->input_name}[name]" 
                       value="{$this->name}">
                <input type="hidden" 
                       name="{$this->input_name}[type]" 
                       value="rich_text">
            </div>
        </div>
        HTML;
    }
} 