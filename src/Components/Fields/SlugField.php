<?php

namespace App\Components\Fields;

class SlugField extends TextField
{
    private string $sourceField;

    public function __construct(string $name, string $label, string $sourceField, bool $required = false, ?string $value = null)
    {
        parent::__construct($name, $label, $required, $value);

        $this->sourceField = $sourceField;
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';
        return <<<HTML
        <div class="form-control">
            <label class="label">
                <span class="label-text">{$this->label}</span>
            </label>
            <input type="text" 
                   name="{$this->name}" 
                   id="{$this->name}"
                   class="input input-bordered bg-base-100" 
                   value="{$this->value}"
                   $required />
        </div>
        <script>
            document.querySelector('[name="{$this->sourceField}"]').addEventListener('keyup', function(e) {
                const slug = e.target.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)+/g, '');
                document.getElementById('{$this->name}').value = slug;
            });
        </script>
        HTML;
    }
} 