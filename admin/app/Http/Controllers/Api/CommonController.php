<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\CommonApiTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class CommonController extends Controller
{
    use CommonApiTrait;

    /**
     * GET /api/common/header?locale=vi&absolute=0
     */
    public function getHeader(Request $request)
    {
        $absolute = $request->boolean('absolute', false);
        $locale = $request->input('locale', app()->getLocale());
        $cacheKey = "api:header:{$locale}:abs:" . (int) $absolute;

        $payload = Cache::remember($cacheKey, 300, function () use ($locale, $absolute) {
            return [
                'logo' => $this->getLogoData(),
                'header_top' => $this->getHeaderTopData($locale),
                'main_menu' => $this->getMenuByLocale($locale, $absolute),
                'is_transparent' => (bool) theme_option('is_header_transparent', false),
                'display_header_top' => (bool) theme_option('display_header_top', true),
            ];
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/common/navigation/{locale}
     * locale: en | vi
     */
    public function getMainMenu(Request $request, string $locale = 'vi')
    {
        $absolute = $request->boolean('absolute', false);
        $cacheKey = "api:navigation:{$locale}:abs:" . (int) $absolute;

        $payload = Cache::remember($cacheKey, 300, function () use ($locale, $absolute) {
            return $this->getMenuByLocale($locale, $absolute);
        });

        if (! $payload) {
            return response()->json([
                'message' => 'No menu found for locale',
                'locale' => $locale,
                'data' => [],
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getFooter(Request $request)
    {
        $locale = $request->input('locale', app()->getLocale());
        $cacheKey = "api:footer:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale) {
            return [
                'locale' => $locale,
                'theme' => $this->getWidgetThemeName($locale),
                'settings' => $this->getFooterSettings(),
                'top_footer' => $this->parseSidebar('top_footer_sidebar', $locale),
                'footer' => $this->parseSidebar('footer_sidebar', $locale),
                'bottom_footer' => $this->parseSidebar('bottom_footer_sidebar', $locale),
            ];
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
