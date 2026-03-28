<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\WidgetApiTrait;
use App\Widget\Contracts\WidgetInterface;

class SocialLinksWidget implements WidgetInterface
{
    use WidgetApiTrait;

    public static function widget(): string
    {
        return 'social-links';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];
        $socials = $this->parseSocialLinkItems($data);

        return [
            'type' => 'social_links',
            'title' => $data['title'] ?? '',
            'style' => $data['style'] ?? 'style-1',
            'socials' => ! empty($socials) ? $socials : $this->getSocialLinks(),
        ];
    }
}
