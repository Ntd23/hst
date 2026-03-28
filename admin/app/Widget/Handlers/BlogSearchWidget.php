<?php

namespace App\Widget\Handlers;

use App\Widget\Contracts\WidgetInterface;

class BlogSearchWidget implements WidgetInterface
{
    public static function widget(): string
    {
        return 'blog-search';
    }

    public function handle(array $widget, string $locale): array
    {
        return [
            'type' => 'blog_search',
            'title' => $locale === 'en' ? 'Search' : 'Tìm kiếm',
            'placeholder' => $locale === 'en' ? 'Search blog...' : 'Tìm bài viết...',
        ];
    }
}
