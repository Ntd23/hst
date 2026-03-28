<?php

namespace App\Widget\Handlers;

use App\Widget\Contracts\WidgetInterface;

class SiteCopyrightWidget implements WidgetInterface
{
    public static function widget(): string
    {
        return 'site-copyright';
    }

    public function handle(array $widget, string $locale): array
    {
        return [
            'type' => 'copyright',
            'content' => theme_option('copyright', '© ' . date('Y') . ' ' . theme_option('site_title')),
        ];
    }
}
