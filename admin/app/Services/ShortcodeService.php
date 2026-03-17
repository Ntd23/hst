<?php

namespace App\Services;

use App\Shortcode\Core\ShortcodeManager;

class ShortcodeService
{
    public function __construct(
        protected ShortcodeManager $manager
    ) {}

    /**
     * Parse shortcode content → sections.
     * Gộp page info + sections thành response hoàn chỉnh.
     *
     * @param  array  $content  ['page' => Page, 'shortcode' => string]
     * @param  string $locale
     * @return array  ['locale' => ..., 'page' => ..., 'sections' => [...]]
     */
    public function allShortcode(array $content, string $locale): array
    {
        $sections = !empty($content['shortcode'])
            ? $this->manager->getShortcode($content['shortcode'], $locale)
            : [];

        return [
            'locale'   => $locale,
            'page'     => $content['page'],
            'sections' => $sections,
        ];
    }
}