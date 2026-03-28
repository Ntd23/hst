<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\WidgetApiTrait;
use App\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class WidgetController extends Controller
{
    use WidgetApiTrait;

    public function __construct(
        protected WidgetService $widgetService
    ) {}

    public function getLayoutWidgets(Request $request)
    {
        $locale = $request->input('locale', app()->getLocale());
        $cacheKey = "api:widgets:layout:v1:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale) {
            return [
                'locale' => $locale,
                'theme' => $this->getWidgetThemeName($locale),
                'settings' => $this->getFooterSettings(),
                'top_footer' => $this->widgetService->allWidgets($this->resolveSidebarWidgets('top_footer_sidebar', $locale), $locale),
                'footer' => $this->widgetService->allWidgets($this->resolveSidebarWidgets('footer_sidebar', $locale), $locale),
                'bottom_footer' => $this->widgetService->allWidgets($this->resolveSidebarWidgets('bottom_footer_sidebar', $locale), $locale),
            ];
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getSidebarWidgets(Request $request)
    {
        $locale = $request->input('locale', app()->getLocale());
        $type = (string) $request->input('type', 'page');
        $sidebarId = $this->resolveSidebarIdByType($type);
        $cacheKey = "api:widgets:sidebar:v2:{$locale}:{$type}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale, $type, $sidebarId) {
            return [
                'locale' => $locale,
                'type' => $type,
                'sidebar' => $sidebarId,
                'items' => $this->widgetService->allWidgets(
                    $this->resolveSidebarWidgets($sidebarId, $locale),
                    $locale
                ),
            ];
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    protected function resolveSidebarIdByType(string $type): string
    {
        return match ($type) {
            'blog' => 'blog_sidebar',
            'service' => 'service_sidebar',
            'product' => 'product_sidebar',
            'menu' => 'menu_sidebar',
            'primary' => 'primary_sidebar',
            default => 'primary_sidebar',
        };
    }
}
