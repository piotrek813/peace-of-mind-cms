<?php

namespace App\Components\Fields;

use App\Enums\MediaMode;

class MediaField
{
    private string $name;
    private string $label;
    private bool $required;
    private mixed $value;
    private MediaMode $mode;
    private ?array $allowedTypes;
    private ?int $maxFileSize;

    public function __construct(
        string $name,
        string $label,
        bool $required = false,
        mixed $value = null,
        MediaMode $mode = MediaMode::SINGLE,
        ?array $allowedTypes = ['image/*'],
        ?int $maxFileSize = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
        $this->value = $value;
        $this->mode = $mode;
        $this->allowedTypes = $allowedTypes;
        $this->maxFileSize = $maxFileSize;
    }

    public function render(): string
    {
        $value = $this->value ? htmlspecialchars(json_encode($this->value)) : '';
        $required = $this->required ? 'required' : '';
        $modeAttr = $this->mode->value;
        $allowedTypesAttr = $this->allowedTypes ? htmlspecialchars(json_encode($this->allowedTypes)) : '';
        $maxFileSizeAttr = $this->maxFileSize ? htmlspecialchars((string)$this->maxFileSize) : '';
        
        return <<<HTML
            <div class="form-control w-full" 
                 data-mode="{$modeAttr}"
                 data-allowed-types="{$allowedTypesAttr}"
                 data-max-size="{$maxFileSizeAttr}">
                
                <label class="label" for="{$this->name}">
                    <span class="label-text">{$this->label}</span>
                </label>
                
                <div class="card bg-base-200 p-4">
                    <input type="hidden" 
                           id="{$this->name}"
                           name="{$this->name}" 
                           value="{$value}" 
                           {$required}>
                    
                    <div class="media-preview grid grid-cols-4 gap-4 mb-4">
                        <!-- Preview items will be inserted here via JavaScript -->
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <button type="button" class="btn btn-primary media-select">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                            Select Media
                        </button>
                        
                        <div class="media-info">
                            {$this->renderHelperText()}
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    }

    private function renderHelperText(): string
    {
        $helperText = [];

        if ($this->mode === MediaMode::MULTIPLE) {
            $helperText[] = "Multiple files allowed";
        }

        if ($this->allowedTypes) {
            $types = implode(', ', array_map(fn($type) => str_replace('*', 'files', $type), $this->allowedTypes));
            $helperText[] = "Accepts: {$types}";
        }

        if ($this->maxFileSize) {
            $size = $this->formatFileSize($this->maxFileSize);
            $helperText[] = "Max size: {$size}";
        }

        return !empty($helperText) 
            ? '<div class="text-sm text-base-content/70">' . implode(' • ', $helperText) . '</div>'
            : '';
    }

    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 1) . ' ' . $units[$pow];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
} 