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

            return [
                'title' => $seoTitle,
                'description' => $seoDescription,
                'robots' => $seoIndex ? 'index, follow' : 'noindex, nofollow',
                'canonical' => url('/'),
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
     * GET /api/pages/home/section/hero?key=home-slider
     *
     * Trả về dữ liệu hero slider cho trang chủ.
     * Slider được tìm theo `key` column trong bảng simple_sliders.
     * Mỗi slide sử dụng các field trực tiếp + MetaData:
     *   - title, description, link, image  (direct columns)
     *   - subtitle, button_label           (MetaData)
     *   - data_count, data_count_description (MetaData – style-2 badge)
     *   - tablet_image, mobile_image       (MetaData – responsive)
     */
    public function getSectionHero(Request $request)
    {
        $sliderKey = $request->input('key', 'home-silde');
        $cacheKey = "api:pages:home:hero:{$sliderKey}";

        $payload = Cache::remember($cacheKey, 300, function () use ($sliderKey) {
            /** @var SimpleSlider|null $slider */
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

                    // Badge style-2 (số năm kinh nghiệm, v.v.)
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
                'message' => "No published slider found for key: {$sliderKey}",
                'data' => null,
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
