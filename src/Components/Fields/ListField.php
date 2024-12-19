<?php

namespace App\Components\Fields;

class ListField
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
            <div class="group-field">
                <div class="group-field-header">
                    <span class="label-text">{$this->label}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 collapse-icon transition-transform" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div class="group-field-content">
                    <div class="list-field" data-name="{$this->name}">
                        <div class="list-items space-y-4">
                            <div class="list-item bg-base-200 rounded-lg" draggable="true">
                                <div class="flex items-center p-4 border-b border-base-300">
                                    <button type="button" class="drag-handle mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                        </svg>
                                    </button>
                                    <button type="button" class="delete-item ml-auto text-error">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-4">
                                    {$this->renderFields()}
                                </div>
                            </div>
                        </div>
                        <button type="button" class="add-item btn btn-ghost mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Item
                        </button>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    private function renderFields(): string
    {
        $html = '<div class="space-y-4">';
        foreach ($this->fields as $field) {
            $html .= $field->render();
        }
        $html .= '</div>';
        return $html;
    }
} 