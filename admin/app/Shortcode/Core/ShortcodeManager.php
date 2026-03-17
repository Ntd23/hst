<?php

namespace App\Shortcode\Core;

class ShortcodeManager
{
    public function __construct(
        protected ShortcodeParser $parser
    ) {}

    /**
     * Parse shortcode string → dispatch handler classes → trả mảng sections.
     *
     * @param  string $shortcodeContent  Raw shortcode content string
     * @param  string $locale
     * @return array  Mảng sections [['shortcode' => name, 'content' => data, 'handler' => class], ...]
     */
    public function getShortcode(string $shortcodeContent, string $locale): array
    {
        $allShortcodes = $this->parser->getAllShortcodeAttributes($shortcodeContent);

        $sections = [];

        foreach ($allShortcodes as $item) {
            $shortcodeName = $item['name'];
            $attrs         = $item['attrs'];
            $handlerClass  = $this->resolveHandler($shortcodeName);

            $sectionData = class_exists($handlerClass)
                ? app($handlerClass)->handle($attrs, $locale)
                : ['locale' => $locale, 'data' => null];

            $sections[] = [
                'shortcode' => $shortcodeName,
                'content'   => $sectionData,
                'handler'   => $handlerClass,
            ];
        }

        return $sections;
    }

    /**
     * Convert shortcode name (kebab-case) → Handler class name.
     *
     * Ví dụ: 'simple-slider' → 'App\Shortcode\Handlers\SimpleSliderShortcode'
     */
    protected function resolveHandler(string $shortcodeName): string
    {
        return 'App\\Shortcode\\Handlers\\'
            . str_replace(' ', '', ucwords(str_replace('-', ' ', $shortcodeName)))
            . 'Shortcode';
    }
}