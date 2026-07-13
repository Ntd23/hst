<?php 
namespace App\Shortcode\Handlers;
use App\Shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\Portfolio\Models\DemoWebsite;

class IncludeWebdemoShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'include-webdemo';
    }
    public function handle(array $attrs, string $locale): array|string|null
    {
        $limit = max(1, min(12, (int) ($attrs['limit'] ?? 6)));

        $websites = DemoWebsite::query()
            ->whereNull('web_id')
            ->wherePublished()
            ->with('translations')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        if ($websites->isEmpty()) {
            return null;
        }

        $items = $websites->map(function ($website) use ($locale) {

            return [
                'id' => $website->id,
                'name' => $this->getTranslatedValue($website, 'name', $locale),
                'content' => $this->getTranslatedValue($website, 'content', $locale),
                'seo_title' => $this->getTranslatedValue($website, 'seo_title', $locale),
                'seo_description' => $this->getTranslatedValue($website, 'seo_description', $locale),
                'url_client' => $website->url_client,
                'url_admin' => $website->url_admin,
                'img_full' => $this->imageUrl($website->img_full),
                'img_featured' => $this->imageUrl($website->img_feautrer),
                'created_at' => $website->created_at?->toIso8601String(),
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'title' => $attrs['title'] ?? null,
            'subtitle' => $attrs['subtitle'] ?? null,
            'items' => $items,
        ];
    }
}
