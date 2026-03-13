<?php 
namespace App\Shortcode\Handlers;

use App\shortcode\Contracts\ShortcodeInterface;

use Illuminate\Support\Facades\DB;
use Botble\Media\Facades\RvMedia;

class BlogPostsShortcode implements ShortcodeInterface
{
    public static function shortcode(): string
    {
        return 'blog-posts';
    }

    public function handle(array $attrs, string $locale): array
    {
        $limit = isset($attrs['limit']) ? (int) $attrs['limit'] : 4;

        $query = \Botble\Blog\Models\Post::query()
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
            return [
                'id' => $post->id,
                'name' => $this->getTranslatedValue($post, 'name', $locale),
                'description' => $this->getTranslatedValue($post, 'description', $locale),
                'image' => $this->imageUrl($post->image),
                'url' => $post->url ?? null,
                'created_at' => $post->created_at?->toIso8601String(),
                'author' => $post->author?->name ?? null,
                'categories' => $post->categories->map(fn($cat) => [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ])->values()->toArray(),
            ];
        })->values()->toArray();

        return array_merge(
            ['locale' => $locale],
            [
                'items' => $items,
            ]
        );
    }
    protected function getTranslatedValue($model, string $column, string $locale): ?string
    {
        if ($model->relationLoaded('translations')) {
            $langCode = $this->getLangCode($locale);

            $translated = $model->translations
                ->where('lang_code', $langCode)
                ->first();

            if ($translated && isset($translated->{$column})) {
                return $translated->{$column};
            }
        }

        return $model->getAttributes()[$column] ?? null;
    }
    protected function getLangCode(string $locale): string
    {
        static $map = null;
        if ($map === null) {
            $map = DB::table('languages')
                ->pluck('lang_code', 'lang_locale')
                ->toArray();
        }

        return $map[$locale] ?? $locale;
    }

     protected function imageUrl(?string $path): ?string
    {
        return $path ? RvMedia::getImageUrl($path) : null;
    }
}