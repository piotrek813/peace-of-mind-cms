<?php

namespace App\Components\Fields;

use App\Models\Media;

class MediaField
{
    private string $name;
    private string $label;
    private bool $required;
    private array $value;
    private string $input_name;
    private bool $multi;
    private Media $media;
    public function __construct(array $field) {
        $this->media = new Media();

        $this->name = $field['name'];
        $this->label = $field['label'];
        $this->required = $field['required'];

        if (is_array($field['value'])) {
            $this->value = $field['value'];
        } else if (is_numeric($field['value'])) {
            $this->value = [$field['value']];
        } else {
            $this->value = [];
        }

        $this->input_name = $field['input_name'];
        $this->multi = $field['multi'] ?? false;
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';

        $preview = join("", array_map(function($item) {
            $item = $this->media->getById($item);
            return $this->renderPreview($item['url'], $item['name'], $item['id']);
        }, $this->value));

        $dataMulti = $this->multi ? 'true' : 'false';

        return <<<HTML
            <div class="form-control w-full media-field" data-multi={$dataMulti}>
                <label class="label" for="{$this->input_name}">
                    <span class="label-text">{$this->label}</span>
                </label>
                
                <div class="card bg-base-200 p-4">
                    <input type="hidden" name="{$this->input_name}[name]" value="{$this->name}">

                    <div class="media-preview grid grid-cols-4 gap-4 mb-4">
                        {$preview}
                    </div>

                    <template class="media-preview-template">
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

    private function renderPreview(string $url = '{{url}}', string $name = '{{name}}', string $id = '{{id}}'): string
    {
        $name = !$this->multi ? "{$this->input_name}[value]" : "{$this->input_name}[value][]";

        return <<<HTML
            <div class="relative group">
                <img src="{$url}" alt="" class="w-full h-32 object-cover rounded">
                <input type="hidden" name="{$name}" value="{$id}">
                <button type="button" 
                    onclick="removeMedia(this, event)" 
                    class="btn btn-sm btn-circle btn-error absolute top-1 right-1 opacity-0 group-hover:opacity-100">
                ✕
            </button>
        </div>
        HTML;
    }
} 