<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\Blog\Models\Post;
use Botble\Media\Facades\RvMedia;


class BlogPostFeaturedShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'blog-post-featured';
    }

    public function handle(array $attrs, string $locale): array
    {
         $postIds = collect($attrs)
        ->filter(fn($value, $key) => str_starts_with($key, 'post_'))
        ->values()
        ->toArray();

        $posts = Post::select('id','name', 'content', 'image', 'created_at')
        ->whereIn('id', $postIds)
        ->get()
        ->map(function ($post) {
            return [
                'id' => $post->id,
                'name' => $post->name,
                'content' => $post->content,
                'image' => \RvMedia::getImageUrl($post->image),
                'created_at' => $post->created_at,
                'slug' => $post->slug,
            ];
        })
        ->keyBy('id');

        $result = [];

        foreach ($attrs as $key => $id) {
            if (str_starts_with($key, 'post_')) {
                $result[$key] = $posts[$id] ?? null;
            }
        }
        return array_merge(
            ['locale' => $locale],
            [
                'items' => $result,
            ]
        );
    }
}