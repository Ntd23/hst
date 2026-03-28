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
        $cacheKey = "api:widgets:layout:v4:{$locale}";

        $payload = Cache::remember($cacheKey, 0, function () use ($locale) {
            return [
                'locale' => $locale,
                'theme' => $this->getWidgetThemeName($locale),
                'settings' => $this->getFooterSettings(),
                'menu_sidebar' => $this->makeLayoutSection('menu', 'menu_sidebar', $locale),
                'header_top_start' => $this->makeLayoutSection('header-top-start', 'header_top_start_sidebar', $locale),
                'header_top_end' => $this->makeLayoutSection('header-top-end', 'header_top_end_sidebar', $locale),
                'top_footer' => $this->makeLayoutSection('top-footer', 'top_footer_sidebar', $locale),
                'footer' => $this->makeLayoutSection('footer', 'footer_sidebar', $locale),
                'bottom_footer' => $this->makeLayoutSection('bottom-footer', 'bottom_footer_sidebar', $locale),
            ];
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getSidebarWidgets(Request $request)
    {
        $locale = $request->input('locale', app()->getLocale());
        $type = (string) $request->input('type', 'primary');
        $sidebarId = $this->resolveSidebarIdByType($type);
        $cacheKey = "api:widgets:sidebar:v2:{$locale}:{$type}";

        $payload = Cache::remember($cacheKey, 0, function () use ($locale, $type, $sidebarId) {
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
            'header-top-start' => 'header_top_start_sidebar',
            'header-top-end' => 'header_top_end_sidebar',
            'page', 'primary' => 'primary_sidebar',
            default => 'primary_sidebar',
        };
    }

    protected function makeLayoutSection(string $type, string $sidebarId, string $locale): array
    {
        return [
            'type' => $type,
            'sidebar' => $sidebarId,
            'items' => $this->widgetService->allWidgets(
                $this->resolveSidebarWidgets($sidebarId, $locale),
                $locale
            ),
        ];
    }
}
