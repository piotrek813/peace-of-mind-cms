<?php

namespace App\Components\Fields;

class MediaField
{
    private string $name;
    private string $label;
    private bool $required;
    private string $value;
    private string $input_name;

    public function __construct(array $field) {
        $this->name = $field['name'];
        $this->label = $field['label'];
        $this->required = $field['required'];
        $this->value = htmlspecialchars_decode($field['value'] ?? '[]');
        $this->input_name = $field['input_name'];
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';

        $preview = join("", array_map(function($item) {
            return $this->renderPreview($item['url'], $item['name']);
        }, json_decode($this->value, true)));

        return <<<HTML
            <div class="form-control w-full media-field">
                <label class="label" for="{$this->input_name}">
                    <span class="label-text">{$this->label}</span>
                </label>
                
                <div class="card bg-base-200 p-4">
                    <input type="hidden" name="{$this->input_name}[name]" value="{$this->name}">
                    <input type="hidden" 
                           name="{$this->input_name}[value]" 
                           value="{$this->value}" 
                           {$required}>
                    
                    <div class="media-preview grid grid-cols-4 gap-4 mb-4">
                        {$preview}
                    </div>

                    <template id="media-preview-template">
                        {$this->renderPreview()}
                    </template>
                    
                    <div class="flex items-center gap-4">
                        <button type="button" class="btn btn-primary media-select" onclick="openMediaModal(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                            Select Media
                        </button>
                    </div>
                </div>
            </div>
        HTML;
    }

    private function renderPreview(string $url = '{{url}}', string $name = '{{name}}'): string
    {
        return <<<HTML
            <div class="relative group">
                <img src="{$url}" alt="{$name}" class="w-full h-32 object-cover rounded">
                <button type="button" 
                    onclick="removeMedia(this, event)" 
                    class="btn btn-sm btn-circle btn-error absolute top-1 right-1 opacity-0 group-hover:opacity-100">
                ✕
            </button>
        </div>
        HTML;
    }
} 