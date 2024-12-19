<?php

namespace App\Components\Fields;

class ListField
{
    private string $name;
    private string $label;
    private array $fields;
    private bool $required;
    private array $value;

    public function __construct(string $name, string $label, array $fields, bool $required = false, array $value = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->fields = $fields;
        $this->required = $required;
        $this->value = $value;
    }

    public function render(): string
    {
        $template = $this->renderTemplate();
        $existingItems = $this->renderExistingItems();
        $fieldOptions = $this->renderFieldOptions();

        return <<<HTML
        <div class="form-control">
            <div class="bg-base-200 rounded-lg">
                <div class="group-field-header flex items-center justify-between p-4 cursor-pointer">
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
                            {$existingItems}
                        </div>
                        <button type="button" class="add-item btn btn-ghost mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Item
                        </button>

                        <!-- Field Search Modal -->
                        <dialog class="modal field-search-modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg mb-4">Add Field</h3>
                                <input type="text" 
                                       class="field-search input input-bordered w-full mb-4" 
                                       placeholder="Search fields...">
                                <div class="field-options space-y-2">
                                    {$fieldOptions}
                                </div>
                            </div>
                            <form method="dialog" class="modal-backdrop">
                                <button>close</button>
                            </form>
                        </dialog>

                        <template class="list-item-template">
                            {$template}
                        </template>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    private function renderFieldOptions(): string
    {
        $html = '';

        foreach ($this->fields as $key => $field) {
            $label = $field->label ?? ucfirst($key);
            $html .= <<<HTML
            <button type="button" 
                    class="field-option w-full text-left p-3 hover:bg-base-200 rounded-lg transition-colors"
                    data-field-key="{$key}">
                {$label}
            </button>
            HTML;
        }
        return $html;
    }

    private function renderTemplate(): string
    {
        // Implement this method to return the HTML template for a new list item
        // This is a placeholder and should be replaced with actual implementation
        return '';
    }

    private function renderExistingItems(): string
    {
        // Implement this method to return the HTML for existing list items
        // This is a placeholder and should be replaced with actual implementation
        return '';
    }
} 