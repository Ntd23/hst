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
    public function handle (array $attrs, string $locale): array
    {
        $websites = DemoWebsite::query()
            ->whereNull('web_id')
            ->with('translations')
            ->orderBy('created_at', 'desc')
            ->take(6)
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
            'items' => $items,
        ];
    }
}