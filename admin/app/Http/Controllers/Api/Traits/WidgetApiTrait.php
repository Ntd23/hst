<?php

namespace App\Http\Controllers\Api\Traits;

use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Botble\Theme\Facades\Theme;
use Botble\Widget\Models\Widget;

trait WidgetApiTrait
{
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
            ->whereIn('theme', $this->getWidgetThemeCandidates($locale))
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
        if (! empty($data['items']) && is_array($data['items'])) {
            return array_values(array_filter(array_map(function (array $fields) {
                $flat = [];

                foreach ($fields as $field) {
                    if (isset($field['key'])) {
                        $flat[$field['key']] = $field['value'] ?? null;
                    }
                }

                $title = $flat['title'] ?? '';

                if (! $title) {
                    return null;
                }

                return [
                    'title' => $title,
                    'icon' => $flat['icon'] ?? '',
                    'icon_image' => $this->resolveMediaUrl($flat['icon_image'] ?? null),
                    'url' => $flat['url'] ?? '',
                ];
            }, $data['items'])));
        }

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
                'icon_image' => $this->resolveMediaUrl($data["icon_image_{$i}"] ?? null),
                'url' => $data["url_{$i}"] ?? '',
            ];
        }

        return $items;
    }

    protected function getSocialLinks(): array
    {
        return array_map(function ($item) {
            return [
                'network' => $this->normalizeSocialNetwork($item->getName()),
                'label' => $item->getName(),
                'url' => $item->getUrl(),
                'icon' => $item->getIcon(),
                'icon_html' => $item->getIconHtml(),
                'icon_image' => $this->resolveMediaUrl($item->getImage()),
                'color' => $item->getColor(),
                'background_color' => $item->getBackgroundColor(),
            ];
        }, Theme::getSocialLinks());
    }

    protected function parseSocialLinkItems(array $data): array
    {
        if (! empty($data['items']) && is_array($data['items'])) {
            return array_values(array_filter(array_map(function (array $fields) {
                $flat = [];

                foreach ($fields as $field) {
                    if (isset($field['key'])) {
                        $flat[$field['key']] = $field['value'] ?? null;
                    }
                }

                $label = $flat['name'] ?? $flat['title'] ?? $flat['label'] ?? '';
                $url = $flat['url'] ?? '';

                if (! $label || ! $url) {
                    return null;
                }

                return [
                    'network' => $this->normalizeSocialNetwork($label),
                    'label' => $label,
                    'url' => $url,
                    'icon' => $flat['icon'] ?? '',
                    'icon_image' => $this->resolveMediaUrl($flat['icon_image'] ?? null),
                    'color' => $flat['color'] ?? null,
                    'background_color' => $flat['background_color'] ?? null,
                ];
            }, $data['items'])));
        }

        $quantity = (int) ($data['quantity'] ?? 0);
        $items = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $label = $data["name_{$i}"] ?? $data["title_{$i}"] ?? $data["label_{$i}"] ?? '';
            $url = $data["url_{$i}"] ?? '';

            if (! $label || ! $url) {
                continue;
            }

            $items[] = [
                'network' => $this->normalizeSocialNetwork($label),
                'label' => $label,
                'url' => $url,
                'icon' => $data["icon_{$i}"] ?? '',
                'icon_image' => $this->resolveMediaUrl($data["icon_image_{$i}"] ?? null),
                'color' => $data["color_{$i}"] ?? null,
                'background_color' => $data["background_color_{$i}"] ?? null,
            ];
        }

        return $items;
    }

    protected function normalizeSocialNetwork(string $label): string
    {
        $normalized = strtolower(trim($label));

        if (str_contains($normalized, 'facebook')) {
            return 'facebook';
        }

        if (str_contains($normalized, 'twitter') || $normalized === 'x') {
            return 'twitter';
        }

        if (str_contains($normalized, 'instagram')) {
            return 'instagram';
        }

        if (str_contains($normalized, 'youtube')) {
            return 'youtube';
        }

        if (str_contains($normalized, 'linkedin')) {
            return 'linkedin';
        }

        if (str_contains($normalized, 'tiktok')) {
            return 'tiktok';
        }

        return (string) str($label)->slug('-');
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

    protected function resolveSidebarWidgets(string $sidebarId, string $locale): array
    {
        $widgets = $this->querySidebarWidgets($sidebarId, $locale);

        if ($widgets->isEmpty() && $locale !== 'vi') {
            $widgets = $this->querySidebarWidgets($sidebarId, 'vi');
        }

        return $widgets->map(function ($widget) {
            $widgetKey = $this->resolveWidgetKey($widget->widget_id);

            return [
                'widget' => $widgetKey,
                'widget_name' => $this->resolveWidgetName($widget->widget_id),
                'widget_id' => $widget->widget_id,
                'position' => (int) $widget->position,
                'data' => is_string($widget->data)
                    ? json_decode($widget->data, true)
                    : (array) $widget->data,
            ];
        })->values()->toArray();
    }

    protected function querySidebarWidgets(string $sidebarId, string $locale)
    {
        return Widget::query()
            ->whereIn('theme', $this->getWidgetThemeCandidates($locale))
            ->where('sidebar_id', $sidebarId)
            ->orderBy('position')
            ->get();
    }

    protected function getFooterSettings(): array
    {
        return [
            'background_image' => $this->resolveMediaUrl(theme_option('footer_background_image')),
            'copyright' => theme_option('copyright', '© ' . date('Y') . ' ' . theme_option('site_title')),
        ];
    }

    protected function getWidgetThemeName(string $locale): string
    {
        return Widget::getThemeName($locale);
    }

    protected function getWidgetThemeCandidates(string $locale): array
    {
        $themes = [$this->getWidgetThemeName($locale)];

        if (! str_contains($locale, '_')) {
            $baseTheme = Theme::getThemeName();
            $localizedThemes = Widget::query()
                ->where('theme', 'like', $baseTheme . '-' . $locale . '_%')
                ->distinct()
                ->pluck('theme')
                ->all();

            $themes = [...$themes, ...$localizedThemes];
        }

        return array_values(array_unique(array_filter($themes)));
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

    protected function parseNumberedItems(array $data, array $fields, array $imageFields = []): array
    {
        $quantity = (int) ($data['quantity'] ?? 0);
        $items = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $item = [];

            foreach ($fields as $field) {
                $value = $data["{$field}_{$i}"] ?? null;

                if (in_array($field, $imageFields, true)) {
                    $value = $this->resolveMediaUrl($value);
                }

                $item[$field] = $value;
            }

            if (array_filter($item, fn ($value) => filled($value))) {
                $items[] = $item;
            }
        }

        return $items;
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

    protected function resolveWidgetName(string $widgetId): string
    {
        $widgetName = class_basename($widgetId);

        return preg_replace('/Widget$/', '', $widgetName) ?: $widgetName;
    }

    protected function resolveWidgetKey(string $widgetId): string
    {
        return (string) str($this->resolveWidgetName($widgetId))->kebab();
    }
}
