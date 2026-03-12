<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Botble\Slug\Models\Slug;
use Botble\Page\Models\Page;
use Botble\SimpleSlider\Models\SimpleSlider;

class PageController extends Controller
{
    use ShortcodeApiTrait;

    /**
     * GET /api/pages/{slug}/meta?locale=vi
     * Trả về SEO meta cho trang dựa vào slug.
     */
    public function getMeta(Request $request, string $slug)
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:pages:{$slug}:meta:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($slug, $locale) {
            $pageSlug = Slug::where('key', $slug)
                ->where('reference_type', Page::class)
                ->first();

            if (!$pageSlug || !$pageSlug->reference) {
                return null;
            }

            $page = $pageSlug->reference;
            $page->loadMissing('translations');

            return $this->buildMetaPayload($page, $locale);
        });

        if (!$payload) {
            return response()->json([
                'message' => 'Page meta not found',
                'locale' => $locale,
                'data' => null,
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/pages/{slug}/sections?locale=vi
     *
     * Parse tất cả shortcodes trong trang (dựa vào slug) → trả về mảng các section.
     */
    public function getSections(Request $request, string $slug)
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:pages:{$slug}:sections:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($slug, $locale) {
            $pageSlug = Slug::where('key', $slug)
                ->where('reference_type', Page::class)
                ->first();

            if (!$pageSlug || !$pageSlug->reference) {
                return null;
            }

            $page = $pageSlug->reference;
            $page->loadMissing('translations');

            $content = $this->getTranslatedValue($page, 'content', $locale) ?: $page->content;
            $isBlogPage = (string) $page->id === (string) theme_option('blog_page_id');

            // Nếu không phải trang blog mà content lại rỗng, thì bỏ qua
            if (!$content && !$isBlogPage) {
                return null;
            }

            // Parse tất cả shortcodes từ content (dạng danh sách để giữ thứ tự)
            $allShortcodes = $content ? $this->getAllShortcodeAttributes($content) : [];
            $sections = [];

            foreach ($allShortcodes as $item) {
                $shortcodeName = $item['name'];
                $attrs = $item['attrs'];
                
                // Convert shortcode-name → getShortcodeName (camelCase handler)
                $method = 'get' . str_replace(' ', '', ucwords(str_replace('-', ' ', $shortcodeName)));

                $sectionData = null;
                if (method_exists($this, $method)) {
                    $sectionData = $this->$method($attrs, $locale);
                } else {
                    // Shortcode không có handler → trả raw attributes + common attributes
                    $sectionData = [
                        'locale' => $locale,
                        'data' => $this->commonSectionAttributes($attrs),
                    ];
                }
                
                $sections[] = [
                    'shortcode' => $shortcodeName,
                    'content' => $sectionData,
                ];
            }

            // Nếu trang này được cấu hình làm trang Blog trong Theme Options,
            // ta đẩy mảng Bài viết vào thẳng (mô phỏng cách Botble render loop.blade.php)
            if ((string) $page->id === (string) theme_option('blog_page_id')) {
                // Ta có thể gọi thẳng hàm getBlogPosts đã có sẵn bên dưới (coi như 1 shortcode ảo)
                $blogData = $this->getBlogPosts(['limit' => 10], $locale);
                if ($blogData) {
                    $sections[] = [
                        'shortcode' => 'blog-posts',
                        'content' => $blogData,
                    ];
                }
            }

            return [
                'locale' => $locale,
                'sections' => $sections,
            ];
        });

        // Chỉ báo lỗi Not found khi bản thân pageSlug ko tồn tại (đã xử lý phía trên trả null).
        // Nếu page có tồn tại nhưng ko có shortcode nào, trả về sections rỗng để Nuxt hiển thị trang trống hoặc page tĩnh.
        if (!$payload) {
            return response()->json([
                'message' => 'Page sections not found',
                'locale' => $locale,
                'data' => [
                    'sections' => []
                ],
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    // ──────────────────────────────────────────────
    //  SECTION HANDLERS (Gộp từ Home & Contact Controller Cũ)
    // ──────────────────────────────────────────────

    // --- HOME SHORTCODES ---

    private function getSimpleSlider(array $attrs, string $locale): ?array
    {
        $sliderKey = $attrs['key'] ?? null;
        if (!$sliderKey) {
            return null;
        }

        $slider = SimpleSlider::query()
            ->wherePublished()
            ->where('key', $sliderKey)
            ->first();

        if (!$slider || $slider->sliderItems->isEmpty()) {
            return null;
        }

        $slider->sliderItems->loadMissing('metadata');

        $items = $slider->sliderItems->map(function ($item) use ($locale) {
            return [
                'id' => $item->id,
                'locale' => $locale,
                'title' => (string) $item->title,
                'description' => (string) $item->description,
                'link' => (string) $item->link,
                'order' => (int) $item->order,
                'image' => $this->imageUrl($item->image),
                'tablet_image' => $this->imageUrl($item->getMetaData('tablet_image', true) ?: null),
                'mobile_image' => $this->imageUrl($item->getMetaData('mobile_image', true) ?: null),
                'subtitle' => $item->getMetaData('subtitle', true) ?: null,
                'button_label' => $item->getMetaData('button_label', true) ?: null,
                'data_count' => $item->getMetaData('data_count', true) ?: null,
                'data_count_description' => $item->getMetaData('data_count_description', true) ?: null,
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'slider_id' => $slider->id,
            'slider_key' => $slider->key,
            'slider_name' => (string) $slider->name,
            'items' => $items,
        ];
    }

    private function getSiteStatistics(array $attrs, string $locale): ?array
    {
        $tabs = $this->parseShortcodeTabs($attrs, ['title', 'data', 'unit', 'image'], ['image']);

        return [
            'locale' => $locale,
            'data' => array_merge(
                $this->commonSectionAttributes($attrs),
                ['tabs' => $tabs]
            ),
        ];
    }

    private function getServices(array $attrs, string $locale): ?array
    {
        $serviceIds = isset($attrs['service_ids'])
            ? array_filter(explode(',', $attrs['service_ids']))
            : [];

        if (empty($serviceIds)) {
            return null;
        }

        $services = \Botble\Portfolio\Models\Service::query()
            ->with(['metadata', 'slugable', 'translations'])
            ->wherePublished()
            ->whereIn('id', $serviceIds)
            ->get();

        if ($services->isEmpty()) {
            return null;
        }

        $items = $services->map(function ($service) use ($locale) {
            return [
                'id' => $service->id,
                'locale' => $locale,
                'name' => (string) $this->getTranslatedValue($service, 'name', $locale),
                'description' => (string) $this->getTranslatedValue($service, 'description', $locale),
                'image' => $this->imageUrl($service->image),
                'slug' => $service->slug,
                'icon' => $service->getMetaData('icon', true) ?: null,
                'icon_image' => $this->imageUrl($service->getMetaData('icon_image', true) ?: null),
            ];
        })->values()->toArray();

        return [
            'shortcode' => $this->commonSectionAttributes($attrs),
            'services' => $items,
        ];
    }

    private function getIncludeWebdemo(array $attrs, string $locale): ?array
    {
        $websites = \App\Models\DemoWebsite::query()
            ->whereNull('web_id')
            ->with('translations')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        if ($websites->isEmpty()) {
            return null;
        }

        $items = $websites->map(function ($website) use ($locale) {
            $translation = $website->translationByLang($locale);

            return [
                'id' => $website->id,
                'name' => (string) ($translation->name ?? $website->name),
                'slug' => $website->slug,
                'description' => (string) ($translation->description ?? $website->description),
                'seo_title' => (string) ($translation->seo_title ?? $website->seo_title),
                'seo_description' => (string) ($translation->seo_description ?? $website->seo_description),
                'url_client' => $website->url_client,
                'url_admin' => $website->url_admin,
                'img_full' => $this->imageUrl($website->img_full),
                'img_featured' => $this->imageUrl($website->img_feautrer),
                'created_at' => $website->created_at?->toIso8601String(),
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'items' => $items,
        ];
    }

    private function getAboutUsInformation(array $attrs, string $locale): ?array
    {
        $tabs = $this->parseShortcodeTabs($attrs, ['title', 'description', 'icon', 'icon_image'], ['icon_image']);

        return [
            'locale' => $locale,
            'data' => array_merge(
                $this->commonSectionAttributes($attrs),
                [
                    'image' => $this->imageUrl($attrs['image'] ?? null),
                    'image_1' => $this->imageUrl($attrs['image_1'] ?? null),
                    'image_2' => $this->imageUrl($attrs['image_2'] ?? null),
                    'data_count' => $attrs['data_count'] ?? null,
                    'data_count_description' => $attrs['data_count_description'] ?? null,
                    'author' => [
                        'name' => $attrs['author_name'] ?? null,
                        'title' => $attrs['author_title'] ?? null,
                        'avatar' => $this->imageUrl($attrs['author_avatar'] ?? null),
                        'signature' => $this->imageUrl($attrs['author_signature'] ?? null),
                    ],
                    'contact' => [
                        'title' => $attrs['contact_title'] ?? null,
                        'subtitle' => $attrs['contact_subtitle'] ?? null,
                        'url' => $attrs['contact_url'] ?? null,
                        'icon' => $attrs['contact_icon'] ?? null,
                        'icon_image' => $this->imageUrl($attrs['contact_icon_image'] ?? null),
                    ],
                    'tabs' => $tabs,
                ]
            ),
        ];
    }

    private function getContactBlock(array $attrs, string $locale): ?array
    {
        return [
            'locale' => $locale,
            'data' => array_merge(
                $this->commonSectionAttributes($attrs),
                ['phone_number' => $attrs['phone_number'] ?? null]
            ),
        ];
    }

    private function getTestimonials(array $attrs, string $locale): ?array
    {
        $testimonialIds = isset($attrs['testimonial_ids'])
            ? array_filter(explode(',', $attrs['testimonial_ids']))
            : [];

        if (empty($testimonialIds)) {
            return null;
        }

        $testimonials = \Botble\Testimonial\Models\Testimonial::query()
            ->with(['translations', 'metadata'])
            ->whereIn('id', $testimonialIds)
            ->wherePublished()
            ->get();

        if ($testimonials->isEmpty()) {
            return null;
        }

        $items = $testimonials->map(function ($testimonial) use ($locale) {
            return [
                'id' => $testimonial->id,
                'name' => $this->getTranslatedValue($testimonial, 'name', $locale),
                'company' => $this->getTranslatedValue($testimonial, 'company', $locale),
                'content' => $this->getTranslatedValue($testimonial, 'content', $locale),
                'image' => $this->imageUrl($testimonial->image),
                'rating_star' => (int) $testimonial->getMetaData('rating_star', true),
            ];
        })->values()->toArray();

        return array_merge(
            ['locale' => $locale],
            $this->commonSectionAttributes($attrs),
            [
                'image' => $this->imageUrl($attrs['image'] ?? null),
                'items' => $items,
            ]
        );
    }

    private function getTeam(array $attrs, string $locale): ?array
    {
        $teamIds = isset($attrs['team_ids'])
            ? array_filter(explode(',', $attrs['team_ids']))
            : [];

        if (empty($teamIds)) {
            return null;
        }

        $teams = \Botble\Team\Models\Team::query()
            ->with(['translations', 'metadata', 'slugable'])
            ->whereIn('id', $teamIds)
            ->wherePublished()
            ->get();

        if ($teams->isEmpty()) {
            return null;
        }

        $items = $teams->map(function ($team) use ($locale) {
            return [
                'id' => $team->id,
                'name' => $this->getTranslatedValue($team, 'name', $locale),
                'title' => $this->getTranslatedValue($team, 'title', $locale),
                'location' => $this->getTranslatedValue($team, 'location', $locale),
                'content' => $this->getTranslatedValue($team, 'content', $locale),
                'phone' => $this->getTranslatedValue($team, 'phone', $locale),
                'address' => $this->getTranslatedValue($team, 'address', $locale),
                'email' => $team->email,
                'website' => $team->website,
                'photo' => $this->imageUrl($team->photo),
                'url' => $team->url ?? null,
                'socials' => $team->socials ?? [],
            ];
        })->values()->toArray();

        return array_merge(
            ['locale' => $locale],
            $this->commonSectionAttributes($attrs),
            ['items' => $items]
        );
    }

    private function getFaqs(array $attrs, string $locale): ?array
    {
        $query = \Botble\Faq\Models\Faq::query()
            ->with('translations')
            ->wherePublished()
            ->orderByDesc('created_at');

        $categoryIds = isset($attrs['faq_category_ids'])
            ? array_filter(explode(',', $attrs['faq_category_ids']))
            : [];

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        $limit = isset($attrs['limit']) ? (int) $attrs['limit'] : 4;
        $faqs = $query->limit($limit)->get();

        if ($faqs->isEmpty()) {
            return null;
        }

        $items = $faqs->map(function ($faq) use ($locale) {
            return [
                'id' => $faq->id,
                'question' => $this->getTranslatedValue($faq, 'question', $locale),
                'answer' => $this->getTranslatedValue($faq, 'answer', $locale),
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'title' => $attrs['title'] ?? null,
            'description' => $attrs['description'] ?? null,
            'image' => $this->imageUrl($attrs['image'] ?? null),
            'floating_block' => [
                'icon' => $attrs['floating_block_icon'] ?? null,
                'title' => $attrs['floating_block_title'] ?? null,
                'description' => $attrs['floating_block_description'] ?? null,
            ],
            'items' => $items,
        ];
    }

    private function getBlogPosts(array $attrs, string $locale): ?array
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
            $this->commonSectionAttributes($attrs),
            [
                'image' => $this->imageUrl($attrs['image'] ?? null),
                'items' => $items,
            ]
        );
    }

    // --- CONTACT SHORTCODES ---

    private function getGoogleMap(array $attrs, string $locale): ?array
    {
        $address = trim((string) ($attrs['content'] ?? ($attrs['address'] ?? '')));

        if ($address === '') {
            return null;
        }

        return [
            'locale' => $locale,
            'data' => [
                'address' => $address,
                'width' => $attrs['width'] ?? '100%',
                'height' => $attrs['height'] ?? '500',
            ],
        ];
    }

    private function getContactForm(array $attrs, string $locale): ?array
    {
        $tabs = $this->parseShortcodeTabs($attrs, ['title', 'description', 'icon', 'icon_image'], ['icon_image']);

        return [
            'locale' => $locale,
            'data' => [
                'title' => $attrs['title'] ?? null,
                'description' => $attrs['description'] ?? null,
                'tabs' => $tabs,
                'form' => [
                    'title' => $attrs['form_title'] ?? null,
                    'description' => $attrs['form_description'] ?? null,
                    'button_label' => $attrs['form_button_label'] ?? null,
                ],
            ],
        ];
    }
}
