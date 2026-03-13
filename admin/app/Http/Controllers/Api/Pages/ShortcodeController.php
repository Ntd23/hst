<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use App\Services\ShortcodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;



class ShortcodeController extends Controller
{
    use ShortcodeApiTrait;

    protected PageService $pageService;
    protected ShortcodeService $shortcodeService;
    public function __construct(PageService $pageService, ShortcodeService $shortcodeService)
    {
        $this->pageService = $pageService;
        $this->shortcodeService = $shortcodeService;
    }

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

        $payload = Cache::remember($cacheKey, 1, function () use ($slug, $locale) {

            //lấy page
            $content = $this->pageService->getPage($slug, $locale);
            if (empty($content)) {
                return null;
            }
            //lấy all shortcode
            $page = $this->shortcodeService->allShortcode($content,$locale);
            return $page;
        });
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

    
}