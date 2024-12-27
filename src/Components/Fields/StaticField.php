<?php

namespace App\Components\Fields;

class StaticField {

    public string $name;
    public string $label;
    public string $input_name;

    public function __construct(array $field) {
        $this->name = $field['name'];
        $this->label = $field['label'];
        $this->input_name = $field['input_name'];
    }

    public function render(): string
    {
        return <<<HTML
        <div class="static-field">
            <h3 class="text-lg font-bold">{$this->label}</h3>
            <input type="hidden" name="{$this->input_name}[name]" value="{$this->name}">
            <input type="hidden" name="{$this->input_name}[value]" value="{$this->name}">
        </div>
        HTML;
    }
}