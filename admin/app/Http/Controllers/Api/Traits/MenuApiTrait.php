<?php

namespace App\Http\Controllers\Api\Traits;

use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;

trait MenuApiTrait
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
}
