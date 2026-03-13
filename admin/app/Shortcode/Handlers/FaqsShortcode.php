<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

class FaqsShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'faqs';
    }

    public function handle(array $attrs, string $locale): array
    {
        $query = \Botble\Faq\Models\Faq::query()
            ->with('translations')
            ->wherePublished()
            ->orderByDesc('created_at');

        $categoryIds = isset($attrs['faq_category_ids'])
            ? array_filter(explode(',', $attrs['faq_category_ids']))
            : [];

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        $limit = isset($attrs['limit']) ? (int) $attrs['limit'] : 4;
        $faqs = $query->limit($limit)->get();

        if ($faqs->isEmpty()) {
            return null;
        }

        $items = $faqs->map(function ($faq) use ($locale) {
            return [
                'id' => $faq->id,
                'question' => $this->getTranslatedValue($faq, 'question', $locale),
                'answer' => $this->getTranslatedValue($faq, 'answer', $locale),
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'title' => $attrs['title'] ?? null,
            'description' => $attrs['description'] ?? null,
            'image' => $this->imageUrl($attrs['image'] ?? null),
            'floating_block' => [
                'icon' => $attrs['floating_block_icon'] ?? null,
                'title' => $attrs['floating_block_title'] ?? null,
                'description' => $attrs['floating_block_description'] ?? null,
            ],
            'items' => $items,
        ];
    }
}