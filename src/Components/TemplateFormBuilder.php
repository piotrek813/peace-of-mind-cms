<?php

namespace App\Components;

use App\Components\Fields\TextField;
use App\Components\Fields\SlugField;
use App\Components\Fields\TextareaField;
use App\Components\Fields\GroupField;
use App\Components\Fields\ListField;

class TemplateFormBuilder extends FormBuilder
{
    public function __construct(array $schema, array $data = [])
    {
        parent::__construct($schema, $data);
    }

    public function render(): string
    {
        $html = '';
        $i = 0;
        foreach ($this->schema as $name => $field) {
            $field['value'] = $this->getValue($name, $field);
            $html .= '<template id="field-'.$name.'-template">'.$this->createField($field)->render().'</template>';
        }
        return $html;
    }
} 