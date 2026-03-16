<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Botble\Portfolio\Models\Service;

class PageDetailController extends Controller
{
    use ShortcodeApiTrait;

    public function getDetails(Request $request, string $slug)
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:entity:{$slug}:{$locale}";

        $payload = Cache::remember($cacheKey, 1, function () use ($slug, $locale) {
            $slugRecord = Slug::query()
                ->where('key', $slug)
                ->first();

            if (! $slugRecord) {
                return [
                    'type' => 'not_found',
                    'slug' => $slug,
                    'data' => null,
                    'seo' => null,
                ];
            }

            return match ($slugRecord->reference_type) {
                Page::class => $this->resolvePage($slugRecord, $locale, $slug),
                Post::class => $this->resolvePost($slugRecord, $locale, $slug),
                Service::class => $this->resolveService($slugRecord, $locale, $slug),
                default => [
                    'type' => 'unknown',
                    'slug' => $slug,
                    'data' => null,
                    'seo' => null,
                    'reference_type' => $slugRecord->reference_type,
                ],
            };
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    protected function resolvePage(Slug $slugRecord, string $locale, string $slug): array
    {
        $page = $slugRecord->reference;

        if (! $page) {
            return [
                'type' => 'not_found',
                'slug' => $slug,
                'data' => null,
                'seo' => null,
            ];
        }

        $page->loadMissing('translations');

        $content = $this->getTranslatedValue($page, 'content', $locale) ?: $page->content;
        $name = $this->getTranslatedValue($page, 'name', $locale) ?: $page->name;

        $meta = $page->getMetaData('seo_meta', true);

        return [
            'type' => 'page',
            'slug' => $slug,
            'data' => [
                'id' => $page->id,
                'name' => $name,
                'content' => $content,
                'template' => $page->template ?? null,
                'sections' => $content ? app(\App\Services\ShortcodeService::class)->allShortcode($content, $locale) : [],
            ],
            'seo' => [
                'title' => !empty($meta['seo_title']) ? $meta['seo_title'] : $name,
                'description' => !empty($meta['seo_description']) ? $meta['seo_description'] : null,
                'image' => !empty($meta['seo_image']) ? $this->imageUrl($meta['seo_image']) : null,
                'index' => !empty($meta['index']) ? $meta['index'] === 'index' : true,
            ],
        ];
    }

    protected function resolvePost(Slug $slugRecord, string $locale, string $slug): array
    {
        $post = $slugRecord->reference;

        if (! $post) {
            return [
                'type' => 'not_found',
                'slug' => $slug,
                'data' => null,
                'seo' => null,
            ];
        }

        $post->loadMissing(['translations', 'categories', 'tags']);

        $name = $this->getTranslatedValue($post, 'name', $locale) ?: $post->name;
        $description = $this->getTranslatedValue($post, 'description', $locale) ?: $post->description;
        $content = $this->getTranslatedValue($post, 'content', $locale) ?: $post->content;

        $post_new = Post::with('translations')
            ->whereKeyNot($post->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($post) use ($locale) {
                return [
                    'id' => $post->id,
                    'name' => $this->getTranslatedValue($post, 'name', $locale),
                    'image' => \RvMedia::getImageUrl($post->image),
                    'slug' => $post->slug,
                    'published_at' => $post->created_at->format('Y-m-d'),
                ];
        });
        // $posts = $this->getTranslatedValue($post_new, 'name', $locale) ?: $post_new;

        $meta = method_exists($post, 'getMetaData')
            ? $post->getMetaData('seo_meta', true)
            : [];

        return [
            'type' => 'blog',
            'slug' => $slug,
            'data' => [
                'id' => $post->id,
                'posts' => $post_new,
                'name' => $name,
                'description' => $description,
                'content' => $content,
                'image' => $this->imageUrl($post->image ?? null),
                'published_at' => optional($post->created_at)->toDateTimeString(),
                'categories' => $post->categories?->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                ])->values() ?? [],
                'tags' => $post->tags?->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                ])->values() ?? [],
            ],
            'seo' => [
                'title' => !empty($meta['seo_title']) ? $meta['seo_title'] : $name,
                'description' => !empty($meta['seo_description']) ? $meta['seo_description'] : $description,
                'image' => !empty($meta['seo_image'])
                    ? $this->imageUrl($meta['seo_image'])
                    : $this->imageUrl($post->image ?? null),
                'index' => !empty($meta['index']) ? $meta['index'] === 'index' : true,
            ],
        ];
    }

    protected function resolveService(Slug $slugRecord, string $locale, string $slug): array
    {
        $service = $slugRecord->reference;

        if (! $service) {
            return [
                'type' => 'not_found',
                'slug' => $slug,
                'data' => null,
                'seo' => null,
            ];
        }

        if (method_exists($service, 'loadMissing')) {
            $service->loadMissing('translations');
        }

        $name = $this->getTranslatedValue($service, 'name', $locale) ?: ($service->name ?? null);
        $description = $this->getTranslatedValue($service, 'description', $locale) ?: ($service->description ?? null);
        $content = $this->getTranslatedValue($service, 'content', $locale) ?: ($service->content ?? null);

        $meta = method_exists($service, 'getMetaData')
            ? $service->getMetaData('seo_meta', true)
            : [];

        return [
            'type' => 'service',
            'slug' => $slug,
            'data' => [
                'id' => $service->id,
                'name' => $name,
                'description' => $description,
                'content' => $content,
                'image' => $this->imageUrl($service->image ?? null),
            ],
            'seo' => [
                'title' => !empty($meta['seo_title']) ? $meta['seo_title'] : $name,
                'description' => !empty($meta['seo_description']) ? $meta['seo_description'] : $description,
                'image' => !empty($meta['seo_image'])
                    ? $this->imageUrl($meta['seo_image'])
                    : $this->imageUrl($service->image ?? null),
                'index' => !empty($meta['index']) ? $meta['index'] === 'index' : true,
            ],
        ];
    }
}