<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use Botble\Media\Facades\RvMedia;
use Botble\Page\Models\Page;
use Botble\SimpleSlider\Models\SimpleSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * GET /api/pages/home/meta?locale=vi
     * Trả về SEO meta cho trang chủ.
     *
     * Homepage trong Botble KHÔNG dùng MetaBox seo_meta,
     * mà dùng theme_option (tab SEO trong Theme Options).
     * Tuy nhiên ta cũng fallback sang MetaBox nếu admin đã set.
     */
    public function getMeta(Request $request)
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:pages:home:meta:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale) {
            // Giá trị mặc định từ theme_option (Admin > Appearance > Theme Options > SEO)
            $seoTitle = theme_option('seo_title', theme_option('site_title', config('app.name')));
            $seoDescription = theme_option('seo_description', '');
            $seoImage = theme_option('seo_image', '');
            $seoIndex = (bool) theme_option('seo_index', true);
            $ogImage = null;

            // Fallback: nếu admin set seo_meta trực tiếp trên Page model của homepage
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
                    $ogImage = RvMedia::getImageUrl($meta['seo_image']);
                }
                if (!empty($meta['index'])) {
                    $seoIndex = $meta['index'] === 'index';
                }
            }

            // OG image fallback → theme_option seo_image
            if (!$ogImage && $seoImage) {
                $ogImage = RvMedia::getImageUrl($seoImage);
            }

            // Favicon
            $favicon = theme_option('favicon');
            $faviconUrl = $favicon ? RvMedia::getImageUrl($favicon) : null;

            return [
                'title' => $seoTitle,
                'description' => $seoDescription,
                'robots' => $seoIndex ? 'index, follow' : 'noindex, nofollow',
                'canonical' => url('/'),
                'favicon' => $faviconUrl,
                'locale' => $locale,
                'og' => [
                    'title' => $seoTitle,
                    'description' => $seoDescription,
                    'image' => $ogImage,
                    'type' => 'website',
                    'url' => url('/'),
                ],
                'twitter' => [
                    'card' => 'summary_large_image',
                    'title' => $seoTitle,
                    'description' => $seoDescription,
                    'image' => $ogImage,
                ],
            ];
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/pages/home/section/simple-slider?locale=vi
     *
     * Trả về dữ liệu slider cho trang chủ theo ngôn ngữ.
     * Web dùng shortcode [simple-slider key="home-slider"], API cũng sẽ bóc tách shortcode từ nội dung Page.
     *
     * Mỗi slide trả về:
     *   - title, description, link, image  (direct columns)
     *   - subtitle, button_label           (MetaData)
     *   - data_count, data_count_description (MetaData – style-2 badge)
     *   - tablet_image, mobile_image       (MetaData – responsive)
     */
    public function getSectionSimpleSlider(Request $request)
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:pages:home:simple-slider:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () {
            // Lấy nội dung trang chủ
            $page = $this->getHomepage();
            if (!$page) {
                return null;
            }

            // Tìm chuỗi shortcode [simple-slider ...] trong nội dung page
            if (!preg_match_all('/\[simple-slider\s+(.*?)\]/usi', $page->content, $matches) || empty($matches[1][0])) {
                return null;
            }

            $attributesString = $matches[1][0];
            $attributes = [];

            // Pattern lấy tất cả cặp key="value" hoặc key='value'
            if (preg_match_all('/(\w+)=["\'](.*?)["\']/usi', $attributesString, $attrMatches)) {
                foreach ($attrMatches[1] as $index => $key) {
                    $attributes[$key] = $attrMatches[2][$index];
                }
            }

            // Lấy slider key
            $sliderKey = $attributes['key'] ?? null;
            if (!$sliderKey) {
                return null;
            }

            // Tìm slider theo key
            $slider = SimpleSlider::query()
                ->wherePublished()
                ->where('key', $sliderKey)
                ->first();

            if (!$slider || $slider->sliderItems->isEmpty()) {
                return null;
            }

            // Eager-load metadata để tránh N+1
            $slider->sliderItems->loadMissing('metadata');

            $items = $slider->sliderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => (string) $item->title,
                    'description' => (string) $item->description,
                    'link' => (string) $item->link,
                    'order' => (int) $item->order,

                    // Ảnh
                    'image' => $item->image ? RvMedia::getImageUrl($item->image) : null,
                    'tablet_image' => ($v = $item->getMetaData('tablet_image', true))
                        ? RvMedia::getImageUrl($v) : null,
                    'mobile_image' => ($v = $item->getMetaData('mobile_image', true))
                        ? RvMedia::getImageUrl($v) : null,

                    // Text / button
                    'subtitle' => $item->getMetaData('subtitle', true) ?: null,
                    'button_label' => $item->getMetaData('button_label', true) ?: null,

                    // Badge style-2
                    'data_count' => $item->getMetaData('data_count', true) ?: null,
                    'data_count_description' => $item->getMetaData('data_count_description', true) ?: null,
                ];
            })->values()->toArray();

            return [
                'slider_id' => $slider->id,
                'slider_key' => $slider->key,
                'slider_name' => (string) $slider->name,
                'items' => $items,
            ];
        });

        if (!$payload) {
            return response()->json([
                'message' => "No published slider shortcode found in homepage content.",
                'locale' => $locale,
                'data' => null,
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        $payload['locale'] = $locale;

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * GET /api/pages/home/section/services?locale=vi
     *
     * Trả về dữ liệu của section "Services" từ shortcode ở trang chủ.
     * Web nhận $shortcode (attributes) và $services, API cũng sẽ trả về đúng cấu trúc như vậy.
     */
    public function getSectionServices(Request $request)
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:pages:home:services:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () {
            // Lấy nội dung trang chủ
            $page = $this->getHomepage();
            if (!$page) {
                return null;
            }

            // Tìm chuỗi shortcode [services ...] trong nội dung page
            // Trích xuất phần cấu hình bên trong (attributes)
            if (!preg_match_all('/\[services\s+(.*?)\]/usi', $page->content, $matches) || empty($matches[1][0])) {
                return null;
            }

            $attributesString = $matches[1][0];
            $attributes = [];

            // Pattern lấy tất cả cặp key="value" hoặc key='value'
            if (preg_match_all('/(\w+)=["\'](.*?)["\']/usi', $attributesString, $attrMatches)) {
                foreach ($attrMatches[1] as $index => $key) {
                    $attributes[$key] = $attrMatches[2][$index];
                }
            }

            // Lấy danh sách ID từ attribute service_ids
            $serviceIds = isset($attributes['service_ids']) ? explode(',', $attributes['service_ids']) : [];
            if (empty($serviceIds)) {
                return null;
            }

            // Lấy dữ liệu services như Web đang lấy
            $services = \Botble\Portfolio\Models\Service::query()
                ->with(['metadata', 'slugable'])
                ->wherePublished()
                ->whereIn('id', $serviceIds)
                ->get();

            if ($services->isEmpty()) {
                return null;
            }

            // Format lại data cho services
            $items = $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => (string) $service->name,
                    'description' => (string) $service->description,
                    'image' => $service->image ? RvMedia::getImageUrl($service->image) : null,
                    'slug' => $service->slug,
                    // Metadata theo định nghĩa của functions/shortcode-portfolio.php
                    'icon' => $service->getMetaData('icon', true) ?: null,
                    'icon_image' => ($v = $service->getMetaData('icon_image', true))
                        ? RvMedia::getImageUrl($v) : null,
                ];
            })->values()->toArray();

            // Format lại data cho shortcode attributes
            $bgImage = isset($attributes['background_image']) ? RvMedia::getImageUrl($attributes['background_image']) : null;

            return [
                'shortcode' => [
                    'style' => $attributes['style'] ?? 'style-1',
                    'title' => $attributes['title'] ?? null,
                    'subtitle' => $attributes['subtitle'] ?? null,
                    'description' => $attributes['description'] ?? null,
                    'button_label' => $attributes['button_label'] ?? null,
                    'button_url' => $attributes['button_url'] ?? null,
                    'background_color' => $attributes['background_color'] ?? null,
                    'background_image' => $bgImage,
                ],
                'services' => $items,
            ];
        });

        if (!$payload) {
            return response()->json([
                'message' => 'Services section not found or empty',
                'locale' => $locale,
                'data' => null,
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Helper method để lấy model Page của Trang Chủ (Homepage)
     * Botble lưu ID của trang chủ trong bảng theme_options với key 'homepage_id'.
     */
    protected function getHomepage(): ?Page
    {
        $homepageId = theme_option('homepage_id');
        if (!$homepageId) {
            return null;
        }

        return Page::find($homepageId);
    }
}
