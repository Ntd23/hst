<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;

class NavigationController extends Controller
{
    /**
     * GET /api/navigation/{location}?absolute=0|1
     *
     * - location: lấy theo bảng menu_locations.location (vd: main-menu)
     * - absolute=1: trả URL absolute theo APP_URL
     * - absolute=0 (default): trả URL relative (vd: /blog)
     */
    public function byLocation(Request $request, string $location)
    {
        $absolute = $request->boolean('absolute', false);

        $cacheKey = "api:navigation:{$location}:abs:" . (int) $absolute;

        $payload = Cache::remember($cacheKey, 300, function () use ($location, $absolute) {
            // Bảng menu_locations của bố có thể trùng location, lấy bản mới nhất
            $loc = MenuLocation::query()
                ->where('location', $location)
                ->orderByDesc('id')
                ->first();

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
                'location' => $location,
                'menu_id'  => (int) $loc->menu_id,
                'data'     => $this->buildTreeFast($nodes, $absolute),
            ];
        });

        if (! $payload) {
            return response()->json([
                'message'  => 'Menu location not found',
                'location' => $location,
                'data'     => [],
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Build tree theo parent_id (root = 0), tối ưu O(n).
     */
    private function buildTreeFast($nodes, bool $absolute): array
    {
        // group nodes theo parent_id
        $grouped = [];

        foreach ($nodes as $n) {
            $pid = (int) ($n->parent_id ?? 0);
            $grouped[$pid][] = $n;
        }

        // sort từng nhóm theo position (phòng khi DB trả không chuẩn)
        foreach ($grouped as $pid => $items) {
            usort($items, function ($a, $b) {
                return (int) $a->position <=> (int) $b->position;
            });
            $grouped[$pid] = $items;
        }

        $walk = function (int $parentId) use (&$walk, $grouped, $absolute): array {
            $items = $grouped[$parentId] ?? [];
            $out = [];

            foreach ($items as $n) {
                $id = (int) $n->id;

                $out[] = [
                    'id'             => $id,
                    'title'          => (string) $n->title,
                    'url'            => $this->normalizeUrl((string) ($n->url ?? ''), $absolute),
                    'target'         => $n->target ?: '_self',
                    'class'          => $n->css_class ?: '',
                    'icon'           => $n->icon_font ?: '',
                    'position'       => (int) $n->position,
                    'reference_id'   => $n->reference_id ?: null,
                    'reference_type' => $n->reference_type ?: null,
                    'children'       => $walk($id),
                ];
            }

            return $out;
        };

        return $walk(0);
    }

    /**
     * Chuẩn hoá URL:
     * - default: trả relative (/blog)
     * - absolute=1: trả absolute (https://domain/blog)
     */
    private function normalizeUrl(string $url, bool $absolute = false): string
    {
        $url = trim($url);

        if ($url === '') {
            return $absolute ? rtrim(config('app.url'), '/') . '/' : '/';
        }

        if ($url === '#') {
            return '#';
        }

        // nếu url là absolute
        if (preg_match('~^https?://~i', $url)) {
            if ($absolute) {
                return $url;
            }

            // convert absolute -> path + query
            $u = parse_url($url);
            $path = ($u['path'] ?? '/') ?: '/';
            $q = isset($u['query']) ? ('?' . $u['query']) : '';

            return $path . $q;
        }

        // đảm bảo có dấu /
        $path = str_starts_with($url, '/') ? $url : '/' . $url;

        if ($absolute) {
            return rtrim(config('app.url'), '/') . $path;
        }

        return $path;
    }
}
