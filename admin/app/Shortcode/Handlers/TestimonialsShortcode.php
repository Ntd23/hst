<?php

namespace App\Shortcode\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Shortcode\Contracts\ShortcodeInterface;

class TestimonialsShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

    public static function shortcode(): string
    {
        return 'testimonials';
    }

    public function handle(array $attrs, string $locale): ?array
    {
        $testimonialIds = isset($attrs['testimonial_ids'])
            ? array_filter(explode(',', $attrs['testimonial_ids']))
            : [];

        if (empty($testimonialIds)) {
            return null;
        }

        $testimonials = \Botble\Testimonial\Models\Testimonial::query()
            ->with(['translations', 'metadata'])
            ->whereIn('id', $testimonialIds)
            ->wherePublished()
            ->get();

        if ($testimonials->isEmpty()) {
            return null;
        }

        $items = $testimonials->map(function ($testimonial) use ($locale) {
            return [
                'id' => $testimonial->id,
                'name' => $this->getTranslatedValue($testimonial, 'name', $locale),
                'company' => $this->getTranslatedValue($testimonial, 'company', $locale),
                'content' => $this->getTranslatedValue($testimonial, 'content', $locale),
                'image' => $this->imageUrl($testimonial->image),
                'rating_star' => (int) $testimonial->getMetaData('rating_star', true),
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'data' => array_filter([
                'style' => $attrs['style'] ?? null,
                'title' => $attrs['title'] ?? null,
                'subtitle' => $attrs['subtitle'] ?? null,
                'background_color' => $attrs['background_color'] ?? null,
                'enable_lazy_loading' => $attrs['enable_lazy_loading'] ?? null,
                'items' => $items,
            ], fn ($value) => $value !== null && $value !== ''),
        ];
    }
}
