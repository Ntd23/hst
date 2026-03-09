<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\Media\Facades\RvMedia;
use Botble\Page\Models\Page;
use Botble\SimpleSlider\Models\SimpleSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    use ShortcodeApiTrait;

    /**
     * GET /api/pages/home/meta?locale=vi
     * Trả về SEO meta cho trang chủ.
     */
    public function getMeta(Request $request)
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:pages:home:meta:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale) {
            $seoTitle = theme_option('seo_title', theme_option('site_title', config('app.name')));
            $seoDescription = theme_option('seo_description', '');
            $seoImage = theme_option('seo_image', '');
            $seoIndex = (bool) theme_option('seo_index', true);
            $ogImage = null;

            $page = $this->getHomepage();
            if ($page) {
                $meta = $page->getMetaData('seo_meta', true);
                if (!empty($meta['seo_title'])) {
                    $seoTitle = $meta['seo_title'];
                }
                if (!empty($meta['seo_description'])) {
                    $seoDescription = $meta['seo_description'];
                }
                if (!empty($meta['seo_image'])) {
                    $ogImage = $this->imageUrl($meta['seo_image']);
                }
                if (!empty($meta['index'])) {
                    $seoIndex = $meta['index'] === 'index';
                }
            }

            if (!$ogImage && $seoImage) {
                $ogImage = $this->imageUrl($seoImage);
            }

            return [
                'locale' => $locale,
                'seo_title' => $seoTitle,
                'seo_description' => $seoDescription,
                'og_image' => $ogImage,
                'seo_index' => $seoIndex,
                'favicon' => theme_option('favicon')
                    ? $this->imageUrl(theme_option('favicon'))
                    : null,
            ];
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/pages/home/section/simple-slider?locale=vi
     *
     * Shortcode [simple-slider key="home-slider"] → query SimpleSlider model by key.
     */
    public function getSectionSimpleSlider(Request $request)
    {
        return $this->sectionResponse($request, 'home:simple-slider', function ($locale) {
            $attrs = $this->getPageShortcode('homepage_id', 'simple-slider', $locale);
            if (!$attrs) {
                return null;
            }

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
        });
    }

    /**
     * GET /api/pages/home/section/site-statistics?locale=vi
     *
     * Shortcode [site-statistics ...] → data + tabs (title_N, data_N, unit_N, image_N).
     */
    public function getSectionSiteStatistics(Request $request)
    {
        return $this->sectionResponse($request, 'home:site-statistics', function ($locale) {
            $attrs = $this->getPageShortcode('homepage_id', 'site-statistics', $locale);
            if (!$attrs) {
                return null;
            }

            $tabs = $this->parseShortcodeTabs($attrs, ['title', 'data', 'unit', 'image'], ['image']);

            return [
                'locale' => $locale,
                'data' => array_merge(
                    $this->commonSectionAttributes($attrs),
                    ['tabs' => $tabs]
                ),
            ];
        });
    }

    /**
     * GET /api/pages/home/section/services?locale=vi
     *
     * Shortcode [services service_ids="1,2,..."] → query Service model.
     */
    public function getSectionServices(Request $request)
    {
        return $this->sectionResponse($request, 'home:services', function ($locale) {
            $attrs = $this->getPageShortcode('homepage_id', 'services', $locale);
            if (!$attrs) {
                return null;
            }

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
        });
    }

    /**
     * GET /api/pages/home/section/include-webdemo?locale=vi
     *
     * DemoWebsite (web_id = null), translation qua translationByLang().
     */
    public function getSectionIncludeWebdemo(Request $request)
    {
        return $this->sectionResponse($request, 'home:include-webdemo', function ($locale) {
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
        });
    }

    /**
     * GET /api/pages/home/section/about-us-information?locale=vi
     *
     * Shortcode [about-us-information ...] → all data in attributes + tabs.
     */
    public function getSectionAboutUsInformation(Request $request)
    {
        return $this->sectionResponse($request, 'home:about-us-information', function ($locale) {
            $attrs = $this->getPageShortcode('homepage_id', 'about-us-information', $locale);
            if (!$attrs) {
                return null;
            }

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
        });
    }

    /**
     * GET /api/pages/home/section/contact-block?locale=vi
     *
     * Shortcode [contact-block ...] → all data in attributes.
     */
    public function getSectionContactBlock(Request $request)
    {
        return $this->sectionResponse($request, 'home:contact-block', function ($locale) {
            $attrs = $this->getPageShortcode('homepage_id', 'contact-block', $locale);
            if (!$attrs) {
                return null;
            }

            return [
                'locale' => $locale,
                'data' => array_merge(
                    $this->commonSectionAttributes($attrs),
                    ['phone_number' => $attrs['phone_number'] ?? null]
                ),
            ];
        });
    }

    /**
     * GET /api/pages/home/section/testimonials?locale=vi
     *
     * Shortcode [testimonials testimonial_ids="1,2,3"] → query Testimonial model.
     */
    public function getSectionTestimonials(Request $request)
    {
        return $this->sectionResponse($request, 'home:testimonials', function ($locale) {
            $attrs = $this->getPageShortcode('homepage_id', 'testimonials', $locale);
            if (!$attrs) {
                return null;
            }

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
        });
    }

    /**
     * GET /api/pages/home/section/team?locale=vi
     *
     * Shortcode [team team_ids="1,2,3"] → query Team model.
     */
    public function getSectionTeam(Request $request)
    {
        return $this->sectionResponse($request, 'home:team', function ($locale) {
            $attrs = $this->getPageShortcode('homepage_id', 'team', $locale);
            if (!$attrs) {
                return null;
            }

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
        });
    }

    /**
     * GET /api/pages/home/section/faqs?locale=vi
     *
     * Shortcode [faqs faq_category_ids="..." limit="4"] → query Faq model.
     */
    public function getSectionFaqs(Request $request)
    {
        return $this->sectionResponse($request, 'home:faqs', function ($locale) {
            $attrs = $this->getPageShortcode('homepage_id', 'faqs', $locale);
            if (!$attrs) {
                return null;
            }

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
        });
    }

    /**
     * GET /api/pages/home/section/blog-posts?locale=vi
     *
     * Shortcode [blog-posts category_ids="..." limit="4"] → query Post model.
     */
    public function getSectionBlogPosts(Request $request)
    {
        return $this->sectionResponse($request, 'home:blog-posts', function ($locale) {
            $attrs = $this->getPageShortcode('homepage_id', 'blog-posts', $locale);
            if (!$attrs) {
                return null;
            }

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
        });
    }
}
