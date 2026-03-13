<?php
namespace App\Shortcode\Core;

use App\Shortcode\Core\ShortcodeParser;


class ShortcodeManager
{
    public function __construct(
        protected ShortcodeParser $parser
    ) {}

   public function getShortcode($content, $locale){

        $allShortcodes = $content ? $this->parser->getAllShortcodeAttributes($content) : [];
        $sections = [];
        foreach ($allShortcodes as $item) {

            $shortcodeName = $item['name'];
            $attrs = $item['attrs'];

            $class = 'App\\Shortcode\\Handlers\\' .
                str_replace(' ', '', ucwords(str_replace('-', ' ', $shortcodeName))) .
                'Shortcode';

            $sectionData = null;

            if (class_exists($class)) {
                $handler = app($class);
                $sectionData = $handler->handle($attrs, $locale);
            } else {
                $sectionData = [
                    'locale' => $locale,
                    'data' => null
                ];
            }

            $sections[] = [
                'shortcode' => $shortcodeName,
                'content' => $sectionData,
                'handler' => $class
            ];
        }

        return [
                'locale' => $locale,
                'sections' => $sections,
            ];
    }
}