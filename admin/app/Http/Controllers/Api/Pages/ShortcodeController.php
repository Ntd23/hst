<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use App\Services\ShortcodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\Slug\Models\Slug;
use Botble\Page\Models\Page;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Tag;
use Botble\Team\Models\Team;
use Botble\Portfolio\Models\Package;
use Botble\Portfolio\Models\Project;
use Botble\Portfolio\Models\ServiceCategory;
use Botble\Portfolio\Models\Service;
use Botble\Gallery\Models\Gallery;



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

        $payload = Cache::remember($cacheKey, 1, function () use ($slug, $locale) {
            // Tìm slug record không giới hạn reference_type
            $slugRecord = Slug::where('key', $slug)->first();

            if (!$slugRecord || !$slugRecord->reference) {
                return null;
            }

            $model = $slugRecord->reference;

            // Load translations nếu model hỗ trợ
            if (method_exists($model, 'loadMissing')) {
                try {
                    $model->loadMissing('translations');
                }
                catch (\Throwable $e) {
                }
            }

            // Nếu là Page thì dùng buildMetaPayload chuẩn (có fallback theme_option)
            if ($model instanceof Page) {
                return $this->buildMetaPayload($model, $locale);
            }

            // Với các model khác: build meta generic
            return $this->buildGenericMetaPayload($model, $locale);
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
            $page = $this->shortcodeService->allShortcode($content, $locale);
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