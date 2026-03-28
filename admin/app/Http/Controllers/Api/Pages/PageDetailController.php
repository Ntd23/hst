<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Services\PageService;
use App\Shortcode\Core\ShortcodeManager;
use Botble\Blog\Models\Post;
use Botble\Page\Models\Page;
use Botble\Portfolio\Models\Service;
use Botble\Slug\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Botble\Portfolio\Models\DemoWebsite;


class PageDetailController extends Controller
{
    use ShortcodeApiTrait;

    public function __construct(
        protected PageService $pageService,
    ) {}

    /**
     * GET /api/pages/{slug}/details?locale=vi
     *
     * Resolve entity type qua slug → trả detail payload tương ứng.
     */
    public function getDetails(Request $request, string $slug)
    {
        $locale   = $this->getApiLocale($request);
        $cacheKey = "api:entity:{$slug}:{$locale}";

        $payload = Cache::remember($cacheKey, 1, function () use ($slug, $locale) {
            $slugRecord = $this->pageService->resolveSlug($slug);
            if (!$slugRecord) {
                return $this->notFoundPayload($slug);
            }

            return match ($slugRecord->reference_type) {
                Page::class    => $this->resolvePage($slugRecord, $locale, $slug),
                Post::class    => $this->resolvePost($slugRecord, $locale, $slug),
                Service::class => $this->resolveService($slugRecord, $locale, $slug),
                DemoWebsite::class => $this->resolveDemoWebsite($slugRecord, $locale, $slug),
                default        => [
                    'type'           => 'unknown',
                    'slug'           => $slug,
                    'data'           => null,
                    'seo'            => null,
                    'reference_type' => $slugRecord->reference_type,
                ],
            };
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    // ──────────────────────────────────────────────
    //  RESOLVERS
    // ──────────────────────────────────────────────

    protected function resolveDemoWebsite(Slug $slugRecord, string $locale, string $slug): array
    {
        $demoWeb = $slugRecord->reference;
         if (!$demoWeb) {
            return $this->notFoundPayload($slug);
        }
        $demoWeb->loadMissing(['translations']);

        $name        = $this->getTranslatedValue($demoWeb, 'name', $locale) ?: $demoWeb->name;
        $content = $this->getTranslatedValue($demoWeb, 'content', $locale) ?: $demoWeb->content;

        $relatedWeb = DemoWebsite::with('translations')
            ->whereKeyNot($demoWeb->id)
            ->latest()
            ->take(6)
            ->get()
            ->map(fn ($p) => [
                'id'           => $p->id,
                'name'         => $this->getTranslatedValue($p, 'name', $locale),
                'image'        => \RvMedia::getImageUrl($p->img_full),
                'slug'         => $p->slug,
                'published_at' => $p->created_at->format('Y-m-d'),
            ]);

        $meta = ['index'=> 'index'];

        return [
            'meta'=>$meta,
            'type' => 'blog',
            'slug' => $slug,
            'data' => [
                'id'           => $demoWeb->id,
                'name'         => $name,
                'content'  => $content,
                'seo_description' => $this->getTranslatedValue($demoWeb, 'seo_description', $locale),
                'img_feautrer'        => $this->imageUrl($demoWeb->img_feautrer ?? null),
                'url_client' => $demoWeb->url_client,
                'url_admin' => $demoWeb->url_admin,
                'date' => $demoWeb->created_at->format('Y-m-d'),
                'demo_webs' => $relatedWeb,
                'locale' => $locale,
            ],
            'seo' => $this->buildSeo($meta, $name, $demoWeb->seo_description, $demoWeb->img_feautrer ?? null),
        ];
    }

    protected function resolvePage(Slug $slugRecord, string $locale, string $slug): array
    {
        $page = $slugRecord->reference;

        if (!$page) {
            return $this->notFoundPayload($slug);
        }

        $page->loadMissing('translations');

        $content = $this->getTranslatedValue($page, 'content', $locale) ?: $page->content;
        $name    = $this->getTranslatedValue($page, 'name', $locale) ?: $page->name;
        $meta    = $page->getMetaData('seo_meta', true);

        return [
            'type' => 'page',
            'slug' => $slug,
            'data' => [
                'id'       => $page->id,
                'name'     => $name,
                'content'  => $this->stripShortcodeTags($content),
                'template' => $page->template ?? null,
                'sections' => $this->parseContentSections($content, $locale),

            ],
            'seo' => $this->buildSeo($meta, $name),
        ];
    }

    protected function resolvePost(Slug $slugRecord, string $locale, string $slug): array
    {
        $post = $slugRecord->reference;

        if (!$post) {
            return $this->notFoundPayload($slug);
        }

        $post->loadMissing(['translations', 'categories', 'tags']);

        $name        = $this->getTranslatedValue($post, 'name', $locale) ?: $post->name;
        $description = $this->getTranslatedValue($post, 'description', $locale) ?: $post->description;
        $content     = $this->getTranslatedValue($post, 'content', $locale) ?: $post->content;

        $relatedPosts = Post::with('translations')
            ->whereKeyNot($post->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'id'           => $p->id,
                'name'         => $this->getTranslatedValue($p, 'name', $locale),
                'image'        => \RvMedia::getImageUrl($p->image),
                'slug'         => $p->slug,
                'published_at' => $p->created_at->format('Y-m-d'),
            ]);

        $meta = method_exists($post, 'getMetaData')
            ? $post->getMetaData('seo_meta', true)
            : [];


        return [
            'type' => 'blog',
            'slug' => $slug,
            'data' => [
                'id'           => $post->id,
                'posts'        => $relatedPosts,
                'name'         => $name,
                'description'  => $description,
                'content'      => $this->stripShortcodeTags($content),
                'image'        => $this->imageUrl($post->image ?? null),
                'published_at' => optional($post->created_at)->toDateTimeString(),
                'categories'   => $post->categories?->map(fn ($c) => [
                    'id'   => $c->id,
                    'name' => $c->name,
                ])->values() ?? [],
                'tags' => $post->tags?->map(fn ($t) => [
                    'id'   => $t->id,
                    'name' => $t->name,
                ])->values() ?? [],
                'sections' => $this->parseContentSections($content, $locale),
            ],
            'seo' => $this->buildSeo($meta, $name, $description, $post->image ?? null),
        ];
    }

    protected function resolveService(Slug $slugRecord, string $locale, string $slug): array
    {
        $service = $slugRecord->reference;

        if (!$service) {
            return $this->notFoundPayload($slug);
        }

        if (method_exists($service, 'loadMissing')) {
            $service->loadMissing('translations');
        }

        $name        = $this->getTranslatedValue($service, 'name', $locale) ?: ($service->name ?? null);
        $description = $this->getTranslatedValue($service, 'description', $locale) ?: ($service->description ?? null);
        $content     = $this->getTranslatedValue($service, 'content', $locale) ?: ($service->content ?? null);

        $meta = method_exists($service, 'getMetaData')
            ? $service->getMetaData('seo_meta', true)
            : [];

        return [
            'type' => 'service',
            'slug' => $slug,
            'data' => [
                'id'          => $service->id,
                'name'        => $name,
                'description' => $description,
                'content'     => $this->stripShortcodeTags($content),
                'image'       => $this->imageUrl($service->image ?? null),
                'sections'    => $this->parseContentSections($content, $locale),
            ],
            'seo' => $this->buildSeo($meta, $name, $description, $service->image ?? null),
        ];
    }

    // ──────────────────────────────────────────────
    //  HELPERS
    // ──────────────────────────────────────────────

    /**
     * Parse shortcodes từ content string → sections array.
     *
     * Content dạng: <shortcode>[about-us-info ...][/about-us-info]</shortcode><p>text</p>
     * → Strip <shortcode> wrapper tags → feed vào ShortcodeManager.
     */
    private function parseContentSections(?string $content, string $locale): array
    {
        if (!$content) {
            return [];
        }

        // Strip <shortcode>...</shortcode> wrapper tags, chỉ giữ nội dung bên trong
        $shortcodeContent = preg_replace('/<\/?shortcode>/i', '', $content);

        // Bỏ HTML tags thường (<p>, <div>...) vì ShortcodeManager chỉ cần [shortcode ...]
        // Nhưng giữ nguyên nếu nằm trong shortcode attributes
        $shortcodeContent = strip_tags($shortcodeContent, ['[', ']']);

        return app(ShortcodeManager::class)->getShortcode($shortcodeContent, $locale);
    }

    /**
     * Strip <shortcode> wrapper tags khỏi content, chỉ giữ HTML text thường.
     * Dùng để trả content "sạch" cho frontend render v-html.
     */
    private function stripShortcodeTags(?string $content): ?string
    {
        if (!$content) {
            return null;
        }

        // Xóa toàn bộ block <shortcode>...[/shortcode-name]</shortcode>
        return preg_replace('/<shortcode>.*?<\/shortcode>/is', '', $content);
    }

    private function notFoundPayload(string $slug): array
    {
        return [
            'type' => 'not_found',
            'slug' => $slug,
            'data' => null,
            'seo'  => null,
        ];
    }

    private function buildSeo(
        array $meta,
        ?string $fallbackTitle,
        ?string $fallbackDescription = null,
        ?string $fallbackImage = null,
    ): array {
        return [
            'title'       => !empty($meta['seo_title']) ? $meta['seo_title'] : $fallbackTitle,
            'description' => !empty($meta['seo_description']) ? $meta['seo_description'] : $fallbackDescription,
            'image'       => !empty($meta['seo_image'])
                ? $this->imageUrl($meta['seo_image'])
                : $this->imageUrl($fallbackImage),
            'index' => !empty($meta['index']) ? $meta['index'] === 'index' : true,
        ];
    }
}
