<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Widget\Contracts\WidgetInterface;
use Botble\Blog\Models\Category;

class BlogCategoriesWidget implements WidgetInterface
{
    use ShortcodeApiTrait;

    public static function widget(): string
    {
        return 'blog-categories';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];
        $categoryIds = $data['category_ids'] ?? [];

        $categories = Category::query()
            ->with(['slugable', 'translations'])
            ->withCount(['posts' => fn ($query) => $query->wherePublished()])
            ->wherePublished()
            ->when(! empty($categoryIds), fn ($query) => $query->whereIn('id', $categoryIds))
            ->get();

        return [
            'type' => 'blog_categories',
            'title' => $data['title'] ?? ($locale === 'en' ? 'Categories' : 'Danh mục'),
            'items' => $categories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $this->getTranslatedValue($category, 'name', $locale),
                'slug' => $this->getSlug($category),
                'posts_count' => (int) $category->posts_count,
            ])->values()->toArray(),
        ];
    }
}
