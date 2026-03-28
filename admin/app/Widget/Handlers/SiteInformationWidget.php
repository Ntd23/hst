<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\WidgetApiTrait;
use App\Widget\Contracts\WidgetInterface;

class SiteInformationWidget implements WidgetInterface
{
    use WidgetApiTrait;

    public static function widget(): string
    {
        return 'site-information';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];
        $items = ! empty($data['items'])
            ? $this->normalizeRepeaterItems($data['items'])
            : $this->parseNumberedItems($data, ['title', 'description', 'icon', 'icon_image'], ['icon_image']);

        return [
            'type' => 'site_information',
            'style' => $data['style'] ?? 'style-1',
            'logo' => $this->resolveMediaUrl($data['logo'] ?? theme_option('logo')),
            'description' => $data['description'] ?? '',
            'display_social_links' => (bool) ($data['display_social_links'] ?? false),
            'socials' => (bool) ($data['display_social_links'] ?? false) ? $this->getSocialLinks() : [],
            'items' => $items,
        ];
    }
}
