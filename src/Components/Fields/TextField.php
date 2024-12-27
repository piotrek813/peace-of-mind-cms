<?php

namespace App\Components\Fields;

class TextField
{
    protected string $name;
    public string $label;
    protected bool $required;
    protected string $value;
    protected string $input_name;

    public function __construct(array $field)
    {
        $this->name = $field['name'];
        $this->label = $field['label'];
        $this->required = $field['required'];
        $this->value = $field['value'] ?? '';
        $this->input_name = $field['input_name'];
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';
        
        return <<<HTML
        <div class="form-control">
            <input type="hidden" name="{$this->input_name}[name]" value="{$this->name}">
            <label class="label" for="{$this->input_name}">
                <span class="label-text">{$this->label}</span>
            </label>
            <input type="text" 
                   name="{$this->input_name}[value]" 
                   class="input input-bordered bg-base-100" 
                   value="{$this->value}"
                   $required />
        </div>
        HTML;
    }
} 