<?php

namespace App\Http\Controllers\Api;

use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Botble\Widget\Facades\WidgetGroup;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;

class CommonController extends Controller
{
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



    // -------------------------------------------------------------------------

    /**
     * GET /api/common/navigation/{locale}
     * locale: en | vi
     * 
     * DB thực tế:
     *   menu_id=1 → EN (Main menu)
     *   menu_id=4 → VI (Main menu-1)
     * Cả 2 đều có location = 'main-menu'
     */
    public function getMainMenu(Request $request, string $locale = 'vi')
    {
        $absolute = $request->boolean('absolute', false);
        $cacheKey = "api:navigation:{$locale}:abs:" . (int) $absolute;

        $payload = Cache::remember($cacheKey, 300, function () use ($locale, $absolute) {
            return $this->getMenuByLocale($locale, $absolute);
        });

        if (!$payload) {
            return response()->json([
                'message' => 'No menu found for locale',
                'locale' => $locale,
                'data' => [],
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Botble lưu menu đa ngôn ngữ bằng cách tạo nhiều Menu riêng biệt,
     * tất cả gán cùng location slug (vd: 'main-menu').
     * Thứ tự insert = thứ tự ngôn ngữ trong config.
     *
     * Giải pháp: join với bảng menus, lọc theo slug của Menu
     * hoặc map locale → menu_id qua thứ tự trong supported locales.
     */
    private function getMenuByLocale(string $locale, bool $absolute): ?array
    {
        // Lấy danh sách ngôn ngữ hỗ trợ theo thứ tự (vd: ['en', 'vi'])
        $supportedLocales = array_keys(config('laravellocalization.supportedLocales', []));

        // Nếu không config, fallback theo thứ tự mặc định
        if (empty($supportedLocales)) {
            $supportedLocales = ['en', 'vi'];
        }

        $localeIndex = array_search($locale, $supportedLocales);

        if ($localeIndex === false) {
            return null;
        }

        // Lấy tất cả menu_locations có location = 'main-menu', order by id ASC
        // Row thứ N tương ứng với ngôn ngữ thứ N trong supportedLocales
        $locations = MenuLocation::query()
            ->where('location', 'main-menu')
            ->orderBy('id')
            ->get();

        $loc = $locations->get($localeIndex);

        if (!$loc) {
            // Fallback: thử lấy row cuối nếu index vượt quá
            $loc = $locations->last();
        }

        if (!$loc) {
            return null;
        }

        $nodes = MenuNode::query()
            ->where('menu_id', $loc->menu_id)
            ->orderBy('position')
            ->get([
                'id',
                'menu_id',
                'parent_id',
                'reference_id',
                'reference_type',
                'url',
                'icon_font',
                'position',
                'title',
                'css_class',
                'target',
                'has_child',
            ]);

        return [
            'locale' => $locale,
            'menu_id' => (int) $loc->menu_id,
            'items' => $this->buildTree($nodes, $absolute),
        ];
    }

    private function buildTree($nodes, bool $absolute): array
    {
        $grouped = [];
        foreach ($nodes as $n) {
            $grouped[(int) ($n->parent_id ?? 0)][] = $n;
        }

        foreach ($grouped as $pid => $items) {
            usort($items, fn($a, $b) => (int) $a->position <=> (int) $b->position);
            $grouped[$pid] = $items;
        }

        $walk = function (int $parentId) use (&$walk, $grouped, $absolute): array {
            $out = [];
            foreach ($grouped[$parentId] ?? [] as $n) {
                $id = (int) $n->id;
                $children = $walk($id);
                $out[] = [
                    'id' => $id,
                    'title' => (string) $n->title,
                    'url' => $this->normalizeUrl((string) ($n->url ?? ''), $absolute),
                    'target' => $n->target ?: '_self',
                    'css_class' => $n->css_class ?: '',
                    'icon' => $n->icon_font ?: '',
                    'position' => (int) $n->position,
                    'has_children' => count($children) > 0,
                    'reference_id' => $n->reference_id ?: null,
                    'reference_type' => $n->reference_type ?: null,
                    'children' => $children,
                ];
            }
            return $out;
        };

        return $walk(0);
    }

    private function getLogoData(): array
    {
        return [
            'site_title' => theme_option('site_title', config('app.name')),
            'logo' => $this->resolveMediaUrl(theme_option('logo')),
            'retina_logo' => $this->resolveMediaUrl(theme_option('retina_logo')),
            'favicon' => $this->resolveMediaUrl(theme_option('favicon')),
            'home_url' => url('/'),
        ];
    }

    private function getHeaderTopData(string $locale): array
    {
        $themeName = \Theme::getThemeName();
        $localeThemeMap = [
            'vi' => $themeName,
            'en' => $themeName . '-en_US',
        ];
        $theme = $localeThemeMap[$locale] ?? $themeName;

        $widgets = \Botble\Widget\Models\Widget::query()
            ->whereIn('sidebar_id', ['header_top_start_sidebar', 'header_top_end_sidebar'])
            ->where('theme', $theme)
            ->orderBy('sidebar_id')
            ->orderBy('position')
            ->get();

        $result = [];

        foreach ($widgets as $widget) {
            $raw = is_string($widget->data)
                ? json_decode($widget->data, true)
                : (array) $widget->data;

            if ($widget->widget_id === 'ContactInformationWidget') {
                $result[$widget->sidebar_id][] = [
                    'type' => 'contact_info',
                    'alignment' => $raw['alignment'] ?? 'start',
                    'items' => $this->parseContactInfoItems($raw),
                ];
            }
        }

        return [
            'start' => $result['header_top_start_sidebar'] ?? [],
            'end' => $result['header_top_end_sidebar'] ?? [],
            'socials' => $this->getSocialLinks(),
        ];
    }

    private function parseContactInfoItems(array $data): array
    {
        $quantity = (int) ($data['quantity'] ?? 0);
        $items = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $title = $data["title_{$i}"] ?? '';
            if (!$title)
                continue;

            $items[] = [
                'title' => $title,
                'icon' => $data["icon_{$i}"] ?? '',
                'icon_image' => $data["icon_image_{$i}"] ?? '',
                'url' => $data["url_{$i}"] ?? '',
            ];
        }

        return $items;
    }

    private function getSocialLinks(): array
    {
        $out = [];
        foreach (['facebook', 'twitter', 'instagram', 'youtube', 'tiktok', 'linkedin'] as $net) {
            if ($url = theme_option("social_{$net}")) {
                $out[] = ['network' => $net, 'url' => $url];
            }
        }
        return $out;
    }

  private function resolveMediaUrl($value): ?string
{
    if (empty($value)) {
        return null;
    }

    // Nếu là numeric → có thể là media ID cũ
    if (is_numeric($value)) {
        $file = MediaFile::find($value);
        return $file ? RvMedia::getImageUrl($file->url) : null;
    }

    // Nếu là string path (Botble mới)
    if (is_string($value)) {
        return RvMedia::getImageUrl($value);
    }

    return null;
}

    private function normalizeUrl(string $url, bool $absolute = false): string
    {
        $url = trim($url);
        if ($url === '')
            return $absolute ? rtrim(config('app.url'), '/') . '/' : '/';
        if ($url === '#')
            return '#';

        if (preg_match('~^https?://~i', $url)) {
            if ($absolute)
                return $url;
            $u = parse_url($url);
            return (($u['path'] ?? '/') ?: '/') . (isset($u['query']) ? '?' . $u['query'] : '');
        }

        $path = str_starts_with($url, '/') ? $url : '/' . $url;
        return $absolute ? rtrim(config('app.url'), '/') . $path : $path;
    }
    public function getFooter(Request $request)
    {
        $locale = $request->input('locale', app()->getLocale());
        $cacheKey = "api:footer:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale) {
            // Set locale trước khi load widgets
            app()->setLocale($locale);

            // Load toàn bộ widgets (giống dynamic_sidebar làm)
            WidgetGroup::load(true);

            return [
                'top_footer' => $this->parseSidebar('top_footer_sidebar'),
                'footer' => $this->parseSidebar('footer_sidebar'),
                'bottom_footer' => $this->parseSidebar('bottom_footer_sidebar'),
            ];
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    private function parseSidebar(string $sidebarId): array
    {
        $group = WidgetGroup::group($sidebarId);

        if (!$group) {
            return [];
        }

        $widgets = WidgetGroup::getData()
            ->filter(fn($w) => $w->sidebar_id === $sidebarId)
            ->sortBy('position');

        return $widgets->map(function ($widget) {
            $data = is_string($widget->data)
                ? json_decode($widget->data, true)
                : (array) $widget->data;

            return [
                'widget_id' => $widget->widget_id,
                'position' => (int) $widget->position,
                'data' => $this->normalizeWidgetData($widget->widget_id, $data ?? []),
            ];
        })->values()->toArray();
    }
    private function normalizeWidgetData(string $widgetId, array $data): array
    {
        return match (true) {

            // CoreSimpleMenu: items là mảng key-value lồng nhau
            str_contains($widgetId, 'CoreSimpleMenu') => [
                'type' => 'menu',
                'title' => $data['name'] ?? '',
                'items' => $this->parseCoreSimpleMenuItems($data['items'] ?? []),
            ],

            str_contains($widgetId, 'Newsletter') => [
                'type' => 'newsletter',
                'title' => $data['title'] ?? '',
                'description' => $data['description'] ?? '',
            ],

            str_contains($widgetId, 'SocialLinks') => [
                'type' => 'social_links',
                'socials' => $this->getSocialLinks(),
            ],

            str_contains($widgetId, 'SiteCopyright') => [
                'type' => 'copyright',
                'content' => theme_option('copyright', '© ' . date('Y') . ' ' . theme_option('site_title')),
            ],

            str_contains($widgetId, 'Galleries') => [
                'type' => 'galleries',
                'title' => $data['title'] ?? '',
                'limit' => (int) ($data['limit'] ?? 6),
            ],

            default => ['type' => 'raw'] + $data,
        };
    }

    private function parseCoreSimpleMenuItems(array $items): array
    {
        return array_map(function (array $fields) {
            $flat = [];
            foreach ($fields as $field) {
                if (isset($field['key'])) {
                    $flat[$field['key']] = $field['value'] ?? null;
                }
            }

            return [
                'label' => $flat['label'] ?? '',
                'url' => $flat['url'] ?? '#',
                'icon' => $flat['attributes'] ?? '',
                'icon_image' => $flat['icon_image'] ?? '',
                'open_new_tab' => ($flat['is_open_new_tab'] ?? '0') === '1',
            ];
        }, $items);
    }
}