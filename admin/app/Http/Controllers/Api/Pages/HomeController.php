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
        $locale = $request->input('locale', app()->getLocale());
        $cacheKey = "api:pages:home:meta:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale) {
            // Giá trị mặc định từ theme_option (Admin > Appearance > Theme Options > SEO)
            $seoTitle = theme_option('seo_title', theme_option('site_title', config('app.name')));
            $seoDescription = theme_option('seo_description', '');
            $seoImage = theme_option('seo_image', '');
            $seoIndex = (bool) theme_option('seo_index', true);
            $ogImage = null;

            // Fallback: nếu admin set seo_meta trực tiếp trên Page model của homepage
            $homepageId = theme_option('homepage_id');
            if ($homepageId) {
                $page = Page::find($homepageId);
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
     * Botble tạo slider riêng cho mỗi ngôn ngữ (giống menu).
     * Map locale → slider theo thứ tự supportedLocales.
     *
     * Mỗi slide trả về:
     *   - title, description, link, image  (direct columns)
     *   - subtitle, button_label           (MetaData)
     *   - data_count, data_count_description (MetaData – style-2 badge)
     *   - tablet_image, mobile_image       (MetaData – responsive)
     */
    public function getSectionSimpleSlider(Request $request)
    {
        $locale = $request->input('locale', app()->getLocale());
        $cacheKey = "api:pages:home:simple-slider:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale) {
            $slider = $this->getSliderByLocale($locale);

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
                'locale' => $locale,
                'slider_id' => $slider->id,
                'slider_key' => $slider->key,
                'slider_name' => (string) $slider->name,
                'items' => $items,
            ];
        });

        if (!$payload) {
            return response()->json([
                'message' => "No published slider found for locale: {$locale}",
                'locale' => $locale,
                'data' => null,
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Tìm SimpleSlider theo locale.
     * Botble tạo slider riêng cho mỗi ngôn ngữ, cùng kiểu list.
     * Row thứ N trong danh sách published sliders (order by id)
     * tương ứng với ngôn ngữ thứ N trong supportedLocales.
     *
     * DB hiện tại:
     *   id=2 (home-silde)    → vi (index 0 nếu vi đứng trước)
     *   id=3 (main-menu-eng) → en (index 1)
     */
    private function getSliderByLocale(string $locale): ?SimpleSlider
    {
        $supportedLocales = array_keys(config('laravellocalization.supportedLocales', []));
        if (empty($supportedLocales)) {
            $supportedLocales = ['vi', 'en'];
        }

        $localeIndex = array_search($locale, $supportedLocales);
        if ($localeIndex === false) {
            return null;
        }

        // Lấy tất cả sliders published, order by id ASC
        // Row thứ N tương ứng với ngôn ngữ thứ N
        $sliders = SimpleSlider::query()
            ->wherePublished()
            ->orderBy('id')
            ->get();

        $slider = $sliders->get($localeIndex);

        // Fallback: lấy row đầu tiên nếu index vượt quá
        if (!$slider) {
            $slider = $sliders->first();
        }

        return $slider;
    }
}
