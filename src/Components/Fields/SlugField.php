<?php

namespace App\Components\Fields;

class SlugField extends TextField
{
    private string $sourceField;

    public function __construct(array $field)
    {
        parent::__construct($field);

        $this->sourceField = $field['sourceField'];
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';
        return <<<HTML
        <div class="form-control" data-type="slug">
            <label class="label">
                <span class="label-text">{$this->label}</span>
            </label>
            <input type="hidden" name="{$this->input_name}[name]" value="{$this->name}">
            <input type="text" 
                   name="{$this->input_name}[value]" 
                   id="{$this->input_name}"
                   class="input input-bordered bg-base-100" 
                   value="{$this->value}"
                   $required />
        </div>
        <script>
            document.querySelectorAll('[data-type="slug"]').forEach(function(element) {
                element.addEventListener('keyup', function(e) {
                    const slug = e.target.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/(^-|-$)+/g, '');
                    e.target.value = slug;
                });
            });
        </script>
        HTML;
    }
} 