<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Widget\Contracts\WidgetInterface;

class BlogTagsWidget implements WidgetInterface
{
    use ShortcodeApiTrait;

    public static function widget(): string
    {
        return 'blog-tags';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];
        $limit = (int) ($data['number_display'] ?? $data['limit'] ?? 5);
        $tags = collect(get_popular_tags($limit));

        return [
            'type' => 'blog_tags',
            'title' => $data['title'] ?? ($locale === 'en' ? 'Tags' : 'Thẻ'),
            'items' => $tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $this->getSlug($tag),
            ])->values()->toArray(),
        ];
    }
}
