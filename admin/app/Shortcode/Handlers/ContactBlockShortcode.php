<?php

namespace App\Shortcode\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Shortcode\Contracts\ShortcodeInterface;

class ContactBlockShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

    public static function shortcode(): string
    {
        return 'contact-block';
    }

    public function handle(array $attrs, string $locale): array
    {
        $data = array_filter([
            'style' => $attrs['style'] ?? null,
            'title' => $attrs['title'] ?? null,
            'subtitle' => $attrs['subtitle'] ?? null,
            'button_label' => $attrs['button_label'] ?? null,
            'button_url' => $attrs['button_url'] ?? null,
            'background_image' => isset($attrs['background_image']) ? $this->imageUrl($attrs['background_image']) : null,
            'enable_lazy_loading' => $attrs['enable_lazy_loading'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');

        return [
            'locale' => $locale,
            'data' => $data,
        ];
    }
}
