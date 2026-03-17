<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Services\PageService;
use App\Services\ShortcodeService;
use Botble\Page\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShortcodeController extends Controller
{
    use ShortcodeApiTrait;

    public function __construct(
        protected PageService $pageService,
        protected ShortcodeService $shortcodeService,
    ) {}

    /**
     * GET /api/pages/{slug}/meta?locale=vi
     *
     * Trả về SEO meta cho bất kỳ entity (Page, Post, Service, Team...) dựa vào slug.
     */
    public function getMeta(Request $request, string $slug)
    {
        $locale   = $this->getApiLocale($request);
        $cacheKey = "api:pages:{$slug}:meta:{$locale}";

        $payload = Cache::remember($cacheKey, 1, function () use ($slug, $locale) {
            $slugRecord = $this->pageService->resolveSlug($slug);

            if (!$slugRecord || !$slugRecord->reference) {
                return null;
            }

            $model = $slugRecord->reference;

            // Load translations nếu model hỗ trợ
            if (method_exists($model, 'loadMissing')) {
                try {
                    $model->loadMissing('translations');
                } catch (\Throwable $e) {
                }
            }

            // Page → buildMetaPayload chuẩn (có fallback theme_option)
            if ($model instanceof Page) {
                return $this->buildMetaPayload($model, $locale);
            }

            // Các model khác → generic meta
            return $this->buildGenericMetaPayload($model, $locale);
        });

        if (!$payload) {
            return response()->json([
                'message' => 'Page meta not found',
                'locale'  => $locale,
                'data'    => null,
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
        $locale   = $this->getApiLocale($request);
        $cacheKey = "api:pages:{$slug}:sections:{$locale}";

        $payload = Cache::remember($cacheKey, 1, function () use ($slug, $locale) {
            $content = $this->pageService->getPage($slug, $locale);
            if (empty($content)) {
                return null;
            }

            return $this->shortcodeService->allShortcode($content, $locale);
        });

        if (!$payload) {
            return response()->json([
                'message' => 'Page sections not found',
                'locale'  => $locale,
                'data'    => ['sections' => []],
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }
}