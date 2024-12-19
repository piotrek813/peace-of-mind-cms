<?php

namespace App\Components\Fields;

class TextareaField
{
    private string $name;
    private string $label;
    private bool $required;
    private ?string $value;

    public function __construct(string $name, string $label, bool $required = false, ?string $value = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
        $this->value = $value;
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';
        return <<<HTML
        <div class="form-control">
            <label class="label">
                <span class="label-text">{$this->label}</span>
            </label>
            <textarea 
                name="{$this->name}" 
                class="textarea textarea-bordered bg-base-100 min-h-[400px] font-mono"
                $required>{$this->value}</textarea>
        </div>
        HTML;
    }
} 