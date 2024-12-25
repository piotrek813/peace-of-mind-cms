<?php

namespace App\Components;

use App\Models\Media;

class MediaLibrary
{
    private bool $isModal;
    private Media $media;
    private ?string $fieldName;
    
    public function __construct(bool $isModal = false, ?string $fieldName = null)
    {
        $this->isModal = $isModal;
        $this->media = new Media();
        $this->fieldName = $fieldName;
    }

    public function render(): string
    {
        $mediaFiles = $this->media->findAll();
        
        return <<<HTML
            <div class="media-library">
                {$this->renderHeader()}

                <div id="media-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    {$this->renderMediaItems($mediaFiles)}
                </div>

                <template id="media-item-template">
                    {$this->renderMediaItem()}
                </template>

                {$this->renderNoMediaMessage($mediaFiles)}
            </div>
        HTML;
    }

    private function renderHeader(): string
    {
        $selectButton = $this->isModal ? <<<HTML
                        <button id="select-action" class="btn btn-ghost btn-sm flex-grow sm:flex-grow-0" onclick="selectMedia()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Select
                                </button> 
        HTML : '';

        return <<<HTML
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    <div id="selection-actions" class="flex flex-wrap gap-2 w-full sm:w-auto" style="display: none;">
                        <button id="delete-selected" class="btn btn-error btn-sm flex-grow sm:flex-grow-0" onclick="deleteMedia()">
                            Delete Selected
                        </button>
                        <button id="clear-selection" class="btn btn-ghost btn-sm flex-grow sm:flex-grow-0" onclick="clearSelection()">
                            Clear Selection
                        </button>
                        <button id="copy-url" class="btn btn-ghost btn-sm flex-grow sm:flex-grow-0" onclick="copyUrl()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                            </svg>
                            Copy URL
                        </button>
                        <button id="download-action" class="btn btn-ghost btn-sm flex-grow sm:flex-grow-0" onclick="downloadMedia()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            Download
                        </button>
                        {$selectButton}
                    </div>
                    <label for="upload-media" class="btn btn-primary flex-grow sm:flex-grow-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        Upload Media
                    </label>
                    <input type="file" id="upload-media" class="hidden" multiple accept="image/*" onchange="uploadMedia()">
                </div>
            </div>
        HTML;
    }

    function renderMediaItem(string $id = "{{id}}", string $url = "{{url}}", string $name = "{{name}}", string $formattedSize = "{{formattedSize}}"): string
    {
        $id = htmlspecialchars($id);
        $url = htmlspecialchars($url);
        $name = htmlspecialchars($name);
        $formattedSize = htmlspecialchars($formattedSize);
        $elementId = "media-item-" . $id;

        return <<<HTML
            <label>
                <div id="{$elementId}" class="card bg-base-100 shadow-sm group media-item relative overflow-hidden [&:has(input:checked)]:border-2 [&:has(input:checked)]:border-primary" data-id="{$id}">
                    <input type="checkbox" class="hidden" onchange="handleSelection()"/>
                    <figure class="aspect-square">
                        <img src={$url} alt={$name}
                                class="w-full h-full object-cover">
                    </figure>
                    <div class="card-body p-3">
                        <h3 class="card-title text-sm truncate">{$name}</h3>
                        <p class="text-xs text-base-content/70">{$formattedSize}</p>
                    </div>
                </div>
            </label>
        HTML;
    }

    private function renderMediaItems(array $media): string
    {
        if (empty($media)) {
            return '';
        }

        return implode('', array_map(function ($item) {
            return $this->renderMediaItem($item['id'], $item['url'], $item['name'], $item['formatted_size']);
        }, $media));
    }


    private function renderNoMediaMessage(array $mediaFiles): string
    {
        $media = empty($mediaFiles) ? 'flex' : 'none';
        return <<<HTML
            <div id="no-media-message" class="flex flex-col items-center justify-center p-8 text-base-content/70" style="display: {$media}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-lg font-medium mb-2">No media files yet</p>
                <p>Upload some files to get started</p>
            </div>
        HTML;
    }
} 