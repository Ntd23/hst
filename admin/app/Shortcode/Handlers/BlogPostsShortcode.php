<?php

namespace App\Shortcode\Handlers;

use App\Shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\Blog\Models\Post;

class BlogPostsShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

    public static function shortcode(): string
    {
        return 'blog-posts';
    }

    public function handle(array $attrs, string $locale): ?array
    {
        $limit = isset($attrs['limit']) ? (int)$attrs['limit'] : 4;

        $query = Post::query()
            ->with(['slugable', 'translations', 'categories'])
            ->wherePublished()
            ->latest();

        $categoryIds = isset($attrs['category_ids'])
            ? array_filter(explode(',', $attrs['category_ids']))
            : [];

        if (!empty($categoryIds)) {
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        $posts = $query->limit($limit)->get();

        if ($posts->isEmpty()) {
            return null;
        }

        $items = $posts->map(function ($post) use ($locale) {
            $slug = $this->getSlug($post);
            return [
                'id'          => $post->id,
                'name'        => $this->getTranslatedValue($post, 'name', $locale),
                'description' => $this->getTranslatedValue($post, 'description', $locale),
                'image'       => $this->imageUrl($post->image),
                'url'         => $slug ? '/' . $slug : null,
                'slug'        => $slug,
                'created_at'  => $post->created_at?->toIso8601String(),
                'author'      => $post->author?->name ?? null,
                'categories'  => $post->categories->map(fn($cat) => [
                    'id'   => $cat->id,
                    'name' => $cat->name,
                ])->values()->toArray(),
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'items'  => $items,
        ];
    }
}