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

        $html = <<<HTML
            <div class="media-library flex flex-col relative h-[90vh]">
                {$this->renderHeader()}

                <div id="media-grid" class="overflow-y-auto p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    {$this->renderMediaItems($mediaFiles)}
                </div>

                <template id="media-item-template">
                    {$this->renderMediaItem()}
                </template>

                {$this->renderNoMediaMessage($mediaFiles)}

                {$this->renderImagePreviewModal()}
            </div>
        HTML;

        if ($this->isModal) {
            $html = <<<HTML
                <dialog id="media-modal" class="modal">
                    <div class="modal-box w-11/12 max-w-5xl h-[90vh] p-0">
                        {$html}
                    </div>
                </dialog>
            HTML;
        }

        return $html;
    }

    private function renderHeader(): string
    {
        $selectButton = $this->isModal ? <<<HTML
                        <button id="select-action" class="btn btn-ghost btn-sm flex-grow sm:flex-grow-0" onclick="selectMedia()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="hidden sm:inline">Select</span>
                                </button> 
        HTML : '';

        $closeButton = $this->isModal ? <<<HTML
                        <form method="dialog">
                            <button class="btn btn-ghost btn-sm flex-grow sm:flex-grow-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Close
                            </button>
                        </form>
        HTML : '';

        return <<<HTML
                <div class="p-4 flex items-center justify-between w-full border-b border-gray-200/10 pb-4 mb-6">
                    <h1 class="max-sm:hidden text-2xl font-bold">Media Library</h1>

                    <div class="flex gap-2 items-center max-sm:w-full max-sm:justify-between">
                        {$closeButton}
                        <form>
                            <label for="upload-media" class="btn btn-sm btn-primary flex-grow sm:flex-grow-0 hover:shadow-lg transition-shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                Upload Media
                            </label>
                            <input type="file" id="upload-media" class="hidden" name="files[]" multiple accept="image/*" onchange="uploadMedia()">
                        </form>
                    </div>
                </div>

                <div id="selection-actions" class="absolute bottom-4 min-w-[357px] self-center z-50 flex gap-2 w-fit mx-auto bg-base-100 p-4 rounded-lg shadow-xl" style="display: none;">
                        <button id="delete-selected" class="btn btn-error btn-sm flex-grow sm:flex-grow-0" onclick="deleteMedia()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden sm:inline">Delete Selected</span>
                    </button>
                    <button id="copy-url" class="btn btn-ghost btn-sm flex-grow sm:flex-grow-0" onclick="copyUrl()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                        </svg>
                        <span class="sm:inline hidden">Copy URL</span>
                    </button>
                    <button id="download-action" class="btn btn-ghost btn-sm flex-grow sm:flex-grow-0" onclick="downloadMedia()">
                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                            <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                        </svg>

                        <span class="hidden sm:inline">Download</span>
                    </button>

                    {$selectButton}

                    <button id="clear-selection" class="btn btn-ghost btn-sm flex-grow sm:flex-grow-0" onclick="clearSelection()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                    </button>
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
                <div id="{$elementId}" class="p-2 hover:bg-base-content/10 rounded-sm media-item relative overflow-hidden [&:has(input:checked)]:bg-base-content/10 [&:has(input:checked)]:border-2 [&:has(input:checked)]:border-primary" data-id="{$id}">
                    <figure class="aspect-square">
                        <img src={$url} alt={$name}
                                class="w-full h-full object-cover rounded-sm" onclick="openImagePreview(event, this)">
                    </figure>
                    <div class="py-4 flex gap-2 flex-row justify-between">
                        <input type="checkbox"  onchange="handleSelection()"/>
                        <h3 class="card-title text-sm truncate">{$name}</h3>
                        <!-- <p class="text-xs text-base-content/70">{$formattedSize}</p> -->
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

    function renderImagePreviewModal(): string
    {
        return <<<HTML
            <dialog id="image-preview-modal" class="modal p-8">
                <div class="modal-box w-fit max-w-5xl max-h-[90vh] p-0 relative">
                    <button class="btn btn-sm btn-circle absolute right-2 top-2" onclick="this.closest('dialog').close()">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                    <img src="" alt="" />
                </div>
            </dialog>
        HTML;
    }
} 