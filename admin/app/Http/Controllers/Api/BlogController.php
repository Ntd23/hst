<?php
namespace App\Http\Controllers\Api;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Botble\Media\Facades\RvMedia;


class BlogController extends Controller{
    public function getBlogs()
    {
        try {
            $attrs = $this->parseAttributes('Blog');

            foreach ($attrs as $key => $value) {
                $method = 'get' . str_replace(' ', '', ucwords(str_replace('-', ' ', $key)));
                $post[$key]['items'] = $this->$method($value);
                $post[$key]['items'] = $this->$method($value);
            }

            return response()->json([
                'ok' => true,
                'data' => $post
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'ok' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function findPost(){
        
    // }

    public function parseAttributes($name){
        $page = Page::where('name', $name)->first();
        preg_match_all('/\[([a-zA-Z0-9\-]+)(.*?)\]/', $page->content, $shortcodes, PREG_SET_ORDER);
        $result = [];
        foreach ($shortcodes as $shortcode) {
            $name = $shortcode[1];
            $attributesString = $shortcode[2];
            preg_match_all('/(\w+)="([^"]*)"/', $attributesString, $attrs, PREG_SET_ORDER);
            $attrArray = [];
            foreach ($attrs as $attr) {
                $attrArray[$attr[1]] = $attr[2];
            }
            $result[$name] = $attrArray;
        }
        return $result;
    }
    

    // lấy dữ liêu post từ DB
    private function getBlogPosts($data)
{
    $limit = $data['limit'] ?? 6;
    $categoryIds = explode(',', $data['category_ids'] ?? '');

    return Post::select('id','name', 'content', 'image', 'created_at')
        ->when(!empty($categoryIds), function ($query) use ($categoryIds) {
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        })
        ->limit($limit)
        ->get()
        ->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->name,
                'content' => $post->content,
                'image' => \RvMedia::getImageUrl($post->image),
                'created_at' => $post->created_at,
            ];
        })
        ->toArray();
}

    private function getBlogPostFeatured($data)
    {
        $postIds = collect($data)
        ->filter(fn($value, $key) => str_starts_with($key, 'post_'))
        ->values()
        ->toArray();

        $posts = Post::select('id','name', 'content', 'image', 'created_at')
        ->whereIn('id', $postIds)
        ->get()
        ->keyBy('id');

        $result = [];

        foreach ($data as $key => $id) {
            if (str_starts_with($key, 'post_')) {
                $result[$key] = $posts[$id] ?? null;
            }
        }
        return $result;
    }
}