<?php

namespace App\Utils;

class DeltaToHtml
{
    private array $delta;
    private string $html = '';
    private bool $inList = false;
    private string $listType = '';
    private array $listItems = [];

    public function __construct(string|array $delta)
    {
        if (is_string($delta)) {
            $this->delta = json_decode($delta, true) ?? [];
        } else {
            $this->delta = $delta;
        }
    }

    public function toHtml(): string
    {
        foreach ($this->delta as $op) {
            if (!isset($op['insert'])) {
                continue;
            }

            $text = $op['insert'];
            $attrs = $op['attributes'] ?? [];

            // Handle newline with attributes (block level formats)
            if ($text === "\n") {
                if (isset($attrs['list'])) {
                    if (!$this->inList) {
                        $this->inList = true;
                        $this->listType = $attrs['list'];
                        $this->listItems = [];
                    }
                    continue;
                } elseif ($this->inList) {
                    $this->html .= $this->renderList();
                    $this->inList = false;
                    $this->listItems = [];
                    continue;
                }
            }

            // Process text content
            $formatted = $this->formatText($text, $attrs);

            // Handle list items
            if (isset($attrs['list'])) {
                $this->listItems[] = $formatted;
                continue;
            }

            // Handle block level formats
            if (isset($attrs['header'])) {
                $this->html .= sprintf('<h%1$d>%2$s</h%1$d>', $attrs['header'], $formatted);
            } elseif (isset($attrs['blockquote'])) {
                $this->html .= sprintf('<blockquote>%s</blockquote>', $formatted);
            } elseif (isset($attrs['code-block'])) {
                $this->html .= sprintf('<pre><code>%s</code></pre>', $formatted);
            } else {
                $this->html .= $formatted;
            }
        }

        // Close any remaining list
        if ($this->inList && !empty($this->listItems)) {
            $this->html .= $this->renderList();
        }

        return $this->html;
    }

    private function formatText(string $text, array $attrs): string
    {
        $formatted = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        // Apply inline formatting
        if (!empty($attrs)) {
            if (isset($attrs['bold'])) {
                $formatted = sprintf('<strong>%s</strong>', $formatted);
            }
            if (isset($attrs['italic'])) {
                $formatted = sprintf('<em>%s</em>', $formatted);
            }
            if (isset($attrs['underline'])) {
                $formatted = sprintf('<u>%s</u>', $formatted);
            }
            if (isset($attrs['strike'])) {
                $formatted = sprintf('<s>%s</s>', $formatted);
            }
            if (isset($attrs['link'])) {
                $formatted = sprintf('<a href="%s">%s</a>', 
                    htmlspecialchars($attrs['link'], ENT_QUOTES, 'UTF-8'),
                    $formatted
                );
            }
            if (isset($attrs['code'])) {
                $formatted = sprintf('<code>%s</code>', $formatted);
            }
        }

        return $formatted;
    }

    private function renderList(): string
    {
        $tag = $this->listType === 'ordered' ? 'ol' : 'ul';
        $items = array_map(fn($item) => sprintf('<li>%s</li>', $item), $this->listItems);
        return sprintf('<%1$s>%2$s</%1$s>', $tag, implode('', $items));
    }

    public static function convert(string|array $delta): string
    {
        return (new self($delta))->toHtml();
    }
} 