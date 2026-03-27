<?php

namespace App\Http\Controllers\Api\Traits;

use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Widget\Models\Widget;

trait CommonApiTrait
{
    protected function getMenuByLocale(string $locale, bool $absolute): ?array
    {
        $supportedLocales = array_keys(config('laravellocalization.supportedLocales', []));

        if (empty($supportedLocales)) {
            $supportedLocales = ['en', 'vi'];
        }

        $localeIndex = array_search($locale, $supportedLocales);

        if ($localeIndex === false) {
            return null;
        }

        $locations = MenuLocation::query()
            ->where('location', 'main-menu')
            ->orderBy('id')
            ->get();

        $loc = $locations->get($localeIndex);

        if (! $loc) {
            $loc = $locations->last();
        }

        if (! $loc) {
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

    protected function buildTree($nodes, bool $absolute): array
    {
        $grouped = [];

        foreach ($nodes as $node) {
            $grouped[(int) ($node->parent_id ?? 0)][] = $node;
        }

        foreach ($grouped as $parentId => $items) {
            usort($items, fn ($a, $b) => (int) $a->position <=> (int) $b->position);
            $grouped[$parentId] = $items;
        }

        $walk = function (int $parentId) use (&$walk, $grouped, $absolute): array {
            $out = [];

            foreach ($grouped[$parentId] ?? [] as $node) {
                $id = (int) $node->id;
                $children = $walk($id);

                $out[] = [
                    'id' => $id,
                    'title' => (string) $node->title,
                    'url' => $this->normalizeUrl((string) ($node->url ?? ''), $absolute),
                    'target' => $node->target ?: '_self',
                    'css_class' => $node->css_class ?: '',
                    'icon' => $node->icon_font ?: '',
                    'position' => (int) $node->position,
                    'has_children' => count($children) > 0,
                    'reference_id' => $node->reference_id ?: null,
                    'reference_type' => $node->reference_type ?: null,
                    'children' => $children,
                ];
            }

            return $out;
        };

        return $walk(0);
    }

    protected function getLogoData(): array
    {
        return [
            'site_title' => theme_option('site_title', config('app.name')),
            'logo' => $this->resolveMediaUrl(theme_option('logo')),
            'retina_logo' => $this->resolveMediaUrl(theme_option('retina_logo')),
            'favicon' => $this->resolveMediaUrl(theme_option('favicon')),
            'home_url' => url('/'),
        ];
    }

    protected function getHeaderTopData(string $locale): array
    {
        $widgets = Widget::query()
            ->whereIn('sidebar_id', ['header_top_start_sidebar', 'header_top_end_sidebar'])
            ->where('theme', $this->getWidgetThemeName($locale))
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

    protected function parseContactInfoItems(array $data): array
    {
        $quantity = (int) ($data['quantity'] ?? 0);
        $items = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $title = $data["title_{$i}"] ?? '';

            if (! $title) {
                continue;
            }

            $items[] = [
                'title' => $title,
                'icon' => $data["icon_{$i}"] ?? '',
                'icon_image' => $data["icon_image_{$i}"] ?? '',
                'url' => $data["url_{$i}"] ?? '',
            ];
        }

        return $items;
    }

    protected function getSocialLinks(): array
    {
        $out = [];

        foreach (['facebook', 'twitter', 'instagram', 'youtube', 'tiktok', 'linkedin'] as $network) {
            if ($url = theme_option("social_{$network}")) {
                $out[] = [
                    'network' => $network,
                    'url' => $url,
                ];
            }
        }

        return $out;
    }

    protected function resolveMediaUrl($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            $file = MediaFile::find($value);

            return $file ? RvMedia::getImageUrl($file->url) : null;
        }

        if (is_string($value)) {
            return RvMedia::getImageUrl($value);
        }

        return null;
    }

    protected function normalizeUrl(string $url, bool $absolute = false): string
    {
        $url = trim($url);

        if ($url === '') {
            return $absolute ? rtrim(config('app.url'), '/') . '/' : '/';
        }

        if ($url === '#') {
            return '#';
        }

        if (preg_match('~^https?://~i', $url)) {
            if ($absolute) {
                return $url;
            }

            $parsed = parse_url($url);

            return (($parsed['path'] ?? '/') ?: '/') . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
        }

        $path = str_starts_with($url, '/') ? $url : '/' . $url;

        return $absolute ? rtrim(config('app.url'), '/') . $path : $path;
    }

    protected function parseSidebar(string $sidebarId, string $locale): array
    {
        $widgets = Widget::query()
            ->where('theme', $this->getWidgetThemeName($locale))
            ->where('sidebar_id', $sidebarId)
            ->orderBy('position')
            ->get();

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

    protected function normalizeWidgetData(string $widgetId, array $data): array
    {
        return match (true) {
            str_contains($widgetId, 'SiteInformation') => [
                'type' => 'site_information',
                'style' => $data['style'] ?? 'style-1',
                'logo' => $this->resolveMediaUrl($data['logo'] ?? theme_option('logo')),
                'description' => $data['description'] ?? '',
                'display_social_links' => (bool) ($data['display_social_links'] ?? false),
                'socials' => (bool) ($data['display_social_links'] ?? false) ? $this->getSocialLinks() : [],
                'items' => $this->normalizeRepeaterItems($data['items'] ?? []),
            ],
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
                'title' => $data['title'] ?? '',
                'style' => $data['style'] ?? 'style-1',
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

    protected function getFooterSettings(): array
    {
        return [
            'background_color' => theme_option('footer_background_color', '#FFFFFF'),
            'text_color' => theme_option('footer_text_color', theme_option('text_color', '#3E4073')),
            'heading_color' => theme_option('footer_heading_color', theme_option('primary_color', '#14176C')),
            'background_image' => $this->resolveMediaUrl(theme_option('footer_background_image')),
            'border_color' => theme_option('footer_border_color', '#CFDDE2'),
            'bottom_background_color' => theme_option('footer_bottom_background_color', '#ECF6FA'),
            'copyright' => theme_option('copyright', '© ' . date('Y') . ' ' . theme_option('site_title')),
        ];
    }

    protected function getWidgetThemeName(string $locale): string
    {
        return Widget::getThemeName($locale);
    }

    protected function normalizeRepeaterItems(array $items): array
    {
        return array_map(function (array $fields) {
            $flat = [];

            foreach ($fields as $field) {
                if (isset($field['key'])) {
                    $flat[$field['key']] = $field['value'] ?? null;
                }
            }

            return [
                'title' => $flat['title'] ?? '',
                'description' => $flat['description'] ?? '',
                'icon' => $flat['icon'] ?? '',
                'icon_image' => $this->resolveMediaUrl($flat['icon_image'] ?? null),
            ];
        }, $items);
    }

    protected function parseCoreSimpleMenuItems(array $items): array
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
