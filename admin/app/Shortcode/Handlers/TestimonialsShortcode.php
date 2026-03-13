<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

class TestimonialsShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'testimonials';
    }

    public function handle(array $attrs, string $locale): array
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

        return array_merge(
            ['locale' => $locale],
            [
                'items' => $items,
            ]
        );
    }
}