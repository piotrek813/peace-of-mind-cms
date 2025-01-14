<?php

namespace App\Components\Fields;

class ListField
{
    private string $name;
    public string $label;
    private bool $required;
    private array $value;
    private array $fields;
    private int $nest_level;
    private string $templates;
    private string $input_name;

    public function __construct(array $field, int $nest_level)
    {
        $this->name = $field['name'];
        $this->label = $field['label'];
        $this->required = $field['required'];
        $this->value = $field['fields'];
        $this->fields = $field['fieldConfig'];
        $this->templates = $field['templates'];
        $this->nest_level = $nest_level;
        $this->input_name = $field['input_name'];
    }

    public function render(): string
    {
        $fieldConfigs = json_encode($this->fields);

        return <<<HTML
        <div class="form-control mb-2 sm:mb-4">
            <div class="bg-base-200 rounded-lg border border-base-300">
                <div class="collapseble-header flex items-center justify-between p-3 sm:p-4 cursor-pointer">
                    <span class="label-text">{$this->label}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-4 w-4 sm:h-5 sm:w-5 collapse-icon transition-transform" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div class="list-field-content p-3 sm:p-4">
                    <div class="list-field" data-name="{$this->name}" data-fields='{$fieldConfigs}'>
                        <div class="list-items space-y-2 sm:space-y-4">
                            {$this->renderExistingItems()}
                        </div>
                        <button type="button" class="add-item btn btn-ghost mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Item
                        </button>

                        <template id="list-item-template">
                            {$this->renderListItem()}
                        </template>

                        {$this->templates}

                        <dialog class="modal field-search-modal">
                            <div class="modal-box">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="font-bold text-lg ">Add Field</h3>
                                    <button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('dialog').close()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <input type="text" class="field-search input input-bordered w-full mb-4" placeholder="Search fields...">
                                <div class="field-options space-y-2">
                                    {$this->renderFieldOptions()}
                                </div>
                            </div>
                            <div class="modal-backdrop"></div>
                        </dialog>
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
            $label = $field['label'] ?? ucfirst($key);
            $type = $this->getFieldType($field);
            $html .= <<<HTML
            <button type="button" 
                    class="field-option w-full text-left p-3 hover:bg-base-200 rounded-lg transition-colors"
                    data-field-key="{$key}"
                    data-field-type="{$type}">
                <div class="flex items-center">
                    <span class="flex-1">{$label}</span>
                    <span class="text-sm opacity-50">{$type}</span>
                </div>
            </button>
            HTML;
        }
        return $html;
    }

    private function getFieldType($field): string
    {
        if (is_array($field)) {
            return $field['type'] ?? 'text';
        }
        return $field->type ?? 'text';
    }

    private function renderListItem(string $child = "", string $index = "{{index}}", string $label = "{{label}}"): string {
        return <<<HTML
            <div class="list-item bg-base-200 rounded-lg mb-4" data-index={$index}>
                <div class="flex items-center p-4 cursor-pointer list-item-header">
                    <button type="button" class="drag-handle mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </button>
                    <span class="font-medium flex-1">{$label}</span>
                    <svg class="collapse-icon w-5 h-5 transition-transform mr-2" 
                            xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 20 20" 
                            fill="currentColor">
                        <path fill-rule="evenodd" 
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                                clip-rule="evenodd" />
                    </svg>
                    <button type="button" class="delete-item text-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 list-item-content">
                    {$child}
                </div>
            </div>
        HTML;
    }

    private function renderExistingItems(): string
    {
        $html = '';
        $i = 0;
        foreach ($this->value as $field) {
            $html .= $this->renderListItem($field->render(), $i++, $field->label);
        }
        return $html;
    }
} 
