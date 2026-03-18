<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Botble\Media\Facades\RvMedia;



class BlogController extends Controller
{
    /**
     * Tận dụng Trait để dùng getTranslatedValue, getLangCode, imageUrl, getSlug
     */
    use \App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

    public function getListing(Request $request)
    {
        try {
            $locale = $this->getApiLocale($request);
            $limit = $request->query('limit', 6);
            $search = $request->query('q');
            $categorySlug = $request->query('category');
            $tagSlug = $request->query('tag');

            // 1. Query Posts with Filters
            $query = Post::query()
                ->with(['slugable', 'translations', 'categories', 'author'])
                ->wherePublished()
                ->latest();

            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }

            if ($categorySlug) {
                $query->whereHas('categories.slugable', function ($q) use ($categorySlug) {
                    $q->where('slugs.key', $categorySlug);
                });
            }

            if ($tagSlug) {
                $query->whereHas('tags.slugable', function ($q) use ($tagSlug) {
                    $q->where('slugs.key', $tagSlug);
                });
            }

            $posts = $query->paginate($limit);

            $mappedPosts = collect($posts->items())->map(function ($post) use ($locale) {
                $slug = $this->getSlug($post);
                return [
                    'id'          => $post->id,
                    'name'        => $this->getTranslatedValue($post, 'name', $locale),
                    'description' => $this->getTranslatedValue($post, 'description', $locale),
                    'image'       => $this->imageUrl($post->image),
                    'url'         => $slug ? '/blog/' . $slug : null,
                    'slug'        => $slug,
                    'created_at'  => $post->created_at?->toIso8601String(),
                    'author'      => $post->author?->name ?? null,
                    'categories'  => $post->categories->map(fn($cat) => [
                        'id'   => $cat->id,
                        'name' => $cat->name,
                    ])->values()->toArray(),
                ];
            });

            // 2. Categories with Post Counts
            $categories = \Botble\Blog\Models\Category::query()
                ->withCount(['posts' => function($q) {
                    $q->wherePublished();
                }])
                ->wherePublished()
                ->limit(10)
                ->get()
                ->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                        'posts_count' => $cat->posts_count,
                    ];
                });

            // 3. Tags
            $tags = \Botble\Blog\Models\Tag::query()
                ->wherePublished()
                ->limit(20)
                ->get()
                ->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'slug' => $tag->slug,
                    ];
                });

            // 4. Recent Posts
            $recentPosts = Post::query()
                ->with(['slugable', 'translations'])
                ->wherePublished()
                ->latest()
                ->limit(4)
                ->get()
                ->map(function ($post) use ($locale) {
                    $slug = $this->getSlug($post);
                    return [
                        'id'         => $post->id,
                        'name'       => $this->getTranslatedValue($post, 'name', $locale),
                        'image'      => $this->imageUrl($post->image),
                        'url'        => $slug ? '/blog/' . $slug : null,
                        'slug'       => $slug,
                        'created_at' => $post->created_at?->toIso8601String(),
                    ];
                });

            return response()->json([
                'ok' => true,
                'data' => [
                    'posts' => [
                        'items' => $mappedPosts,
                        'current_page' => $posts->currentPage(),
                        'last_page'    => $posts->lastPage(),
                        'total'        => $posts->total(),
                        'per_page'     => $posts->perPage(),
                    ],
                    'sidebar' => [
                        'categories'   => $categories,
                        'tags'         => $tags,
                        'recent_posts' => $recentPosts,
                    ]
                ]
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function getBlogs()
    {
        try {
            $attrs = $this->parseAttributes('Blog');

            foreach ($attrs as $key => $value) {
                $method = 'get' . str_replace(' ', '', ucwords(str_replace('-', ' ', $key)));
                $post[$key]['items'] = $this->$method($value);
                $post[$key]['title'] = $value['title'];
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

    public function getPost(){
        try {
            $postFeatured = $this->getPostFeatured(2);
            $post = $this->findPostSlug("su-hop-tac-giua-hisotechgroup-va-asean-business-ky-ket-hop-dong-chien-luoc1");
            
            $data["postFeatured"] = $postFeatured;
            $data["post"] = $post;

            return response()->json([
                'ok' => true,
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
             return response()->json([
                'ok' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

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
                    'name' => $post->name,
                    'content' => $post->content,
                    'image' => \RvMedia::getImageUrl($post->image),
                    'created_at' => $post->created_at,
                    'slug' => $post->slug,
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

        foreach ($data as $key => $id) {
            if (str_starts_with($key, 'post_')) {
                $result[$key] = $posts[$id] ?? null;
            }
        }
        return $result;
    }
    

    private function getPostFeatured($limit = 2)
    {
        return Post::select('id','name', 'content', 'image', 'created_at')
            ->where("is_featured", 1)
            ->latest()
            ->limit($limit)
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
            ->toArray();
        
    }

    private function findPostSlug($slug)
    {
        $post = Post::
            join('slugs', function ($join) {
                $join->on('slugs.reference_id', '=', 'posts.id')
                    ->where('slugs.reference_type', Post::class);
            })
            ->where('slugs.key', $slug)
            ->first();

        if (!$post) {
            return null;
        }
        return [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'image' => \RvMedia::getImageUrl($post->image),
            'created_at' => $post->created_at,
            'slug' => $post->slug,
        ];
    }

}