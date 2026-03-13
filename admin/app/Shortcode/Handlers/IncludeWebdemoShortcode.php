<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

class IncludeWebdemoShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'include-webdemo';
    }
    public function handle (array $attrs, string $locale): array
    {
        $websites = \App\Models\DemoWebsite::query()
            ->whereNull('web_id')
            ->with('translations')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        if ($websites->isEmpty()) {
            return null;
        }

        $items = $websites->map(function ($website) use ($locale) {
            $translation = $website->translationByLang($locale);

            return [
                'id' => $website->id,
                'name' => (string) ($translation->name ?? $website->name),
                'slug' => $website->slug,
                'description' => (string) ($translation->description ?? $website->description),
                'seo_title' => (string) ($translation->seo_title ?? $website->seo_title),
                'seo_description' => (string) ($translation->seo_description ?? $website->seo_description),
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