<?php

namespace App\Components\Fields;

class GroupField
{
    private string $name;
    private string $label;
    private array $fields;
    private bool $required;

    public function __construct(string $name, string $label, array $fields, bool $required = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->fields = $fields;
        $this->required = $required;
    }

    public function render(): string
    {
        return <<<HTML
        <div class="form-control">
            <div class="bg-base-200 rounded-lg">
                <div class="group-field-header flex items-center justify-between p-4 cursor-pointer">
                    <span class="font-medium">{$this->label}</span>
                    <svg class="collapse-icon w-5 h-5 transition-transform duration-200" 
                         xmlns="http://www.w3.org/2000/svg" 
                         viewBox="0 0 20 20" 
                         fill="currentColor">
                        <path fill-rule="evenodd" 
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                              clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="group-field-content p-4 border-t border-base-300">
                    {$this->renderFields()}
                </div>
            </div>
        </div>
        HTML;
    }

    private function renderFields(): string
    {
        $html = "";
        foreach ($this->fields as $field) {
            $html .= $field->render();
        }
        return $html;
    }
} 