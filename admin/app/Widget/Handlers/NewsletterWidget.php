<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\WidgetApiTrait;
use App\Widget\Contracts\WidgetInterface;

class NewsletterWidget implements WidgetInterface
{
    use WidgetApiTrait;

    public static function widget(): string
    {
        return 'newsletter';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];

        return [
            'type' => 'newsletter',
            'title' => $data['title'] ?? '',
            'subtitle' => $data['subtitle'] ?? '',
            'description' => $data['description'] ?? '',
            'style' => $data['style'] ?? 'style-1',
            'image' => $this->resolveMediaUrl($data['image'] ?? null),
            // 'background_color' => $data['background_color'] ?? null,
            'background_image' => $this->resolveMediaUrl($data['background_image'] ?? null),
            'display_social_links' => (bool) ($data['display_social_links'] ?? false),
            'socials' => (bool) ($data['display_social_links'] ?? false) ? $this->getSocialLinks() : [],
        ];
    }
}
