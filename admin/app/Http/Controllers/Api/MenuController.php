<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\MenuApiTrait;
use App\Http\Controllers\Api\Traits\WidgetApiTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    use MenuApiTrait;
    use WidgetApiTrait;

    public function getMenus(Request $request)
    {
        $absolute = $request->boolean('absolute', false);
        $locale = $request->input('locale', app()->getLocale());
        $cacheKey = "api:menus:{$locale}:abs:" . (int) $absolute;

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
}
