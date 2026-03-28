<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Widget\Contracts\WidgetInterface;
use Botble\Blog\Models\Post;

class BlogPostsWidget implements WidgetInterface
{
    use ShortcodeApiTrait;

    public static function widget(): string
    {
        return 'blog-posts';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];
        $categoryIds = $data['category_ids'] ?? [];
        $limit = (int) ($data['limit'] ?? 4);

        $posts = Post::query()
            ->with(['slugable', 'translations', 'categories'])
            ->wherePublished()
            ->when(! empty($categoryIds), function ($query) use ($categoryIds) {
                $query->whereHas('categories', fn ($categoryQuery) => $categoryQuery->whereIn('categories.id', $categoryIds));
            })
            ->latest()
            ->limit($limit)
            ->get();

        return [
            'type' => 'blog_posts',
            'title' => $data['title'] ?? ($locale === 'en' ? 'Latest Posts' : 'Bài viết mới'),
            'items' => $posts->map(fn ($post) => [
                'id' => $post->id,
                'name' => $this->getTranslatedValue($post, 'name', $locale),
                'image' => $this->imageUrl($post->image),
                'slug' => $this->getSlug($post),
                'url' => $this->getSlug($post) ? '/blog/' . $this->getSlug($post) : null,
                'created_at' => $post->created_at?->toIso8601String(),
            ])->values()->toArray(),
        ];
    }
}
