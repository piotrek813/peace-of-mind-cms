<?php

namespace App\Components\Fields;

class BoolField
{
    private string $name;
    public string $label;
    private bool $required;
    private mixed $value;

    public function __construct(
        string $name,
        string $label,
        bool $required = false,
        mixed $value = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
        $this->value = $value;
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
                           name="{$this->name}" 
                           value="0">
                    <input type="checkbox" 
                           name="{$this->name}" 
                           class="toggle"{$required}{$checked}
                           value="1">
                </label>
            </div>
        </div>
        HTML;
    }
} 