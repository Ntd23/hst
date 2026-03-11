<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Http\Controllers\Controller;
use Botble\Page\Models\Page;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ShortcodeApiTrait;

    public function getMeta(Request $request)
    {
        return $this->metaResponseFromShortcode($request, 'services', 'services');
    }

    /**
     * GET /api/pages/service/section/list?locale=vi
     *
     * Shortcode [services service_ids="1,2,..."] → query Service model.
     */
    public function getSectionListService(Request $request)
    {
        $pageId = (int) $request->query('page_id');
        $cacheKeySuffix = $pageId > 0 ? "page:{$pageId}" : null;

        return $this->sectionResponse($request, 'services:list', function ($locale) use ($pageId) {
            $page = null;
            if ($pageId > 0) {
                $page = Page::with('translations')->find($pageId);
                if (! $page) {
                    return null;
                }

                $content = $this->getTranslatedValue($page, 'content', $locale) ?: $page->content;
                if (! $content) {
                    return null;
                }

                $attrs = $this->getShortcodeAttributes($content, 'services');
            } else {
                $pages = Page::query()
                    ->with('translations')
                    ->wherePublished()
                    ->where('content', 'like', '%[services%')
                    ->get();

                $bestCount = -1;
                $bestAttrs = null;
                $bestPage = null;

                foreach ($pages as $candidate) {
                    $content = $this->getTranslatedValue($candidate, 'content', $locale) ?: $candidate->content;
                    if (! $content) {
                        continue;
                    }

                    $candidateAttrs = $this->getShortcodeAttributes($content, 'services');
                    if (! $candidateAttrs) {
                        continue;
                    }

                    $ids = isset($candidateAttrs['service_ids'])
                        ? array_filter(explode(',', $candidateAttrs['service_ids']))
                        : [];

                    $count = count($ids);
                    if ($count > $bestCount) {
                        $bestCount = $count;
                        $bestAttrs = $candidateAttrs;
                        $bestPage = $candidate;
                    }
                }

                $page = $bestPage;
                $attrs = $bestAttrs;
            }

            if (!$attrs) {
                return null;
            }

            $serviceIds = isset($attrs['service_ids'])
                ? array_filter(explode(',', $attrs['service_ids']))
                : [];

            if (empty($serviceIds)) {
                return null;
            }

            $services = \Botble\Portfolio\Models\Service::query()
                ->with(['metadata', 'slugable', 'translations'])
                ->wherePublished()
                ->whereIn('id', $serviceIds)
                ->get();

            if ($services->isEmpty()) {
                return null;
            }

            $items = $services->map(function ($service) use ($locale) {
                return [
                    'id' => $service->id,
                    'locale' => $locale,
                    'name' => (string) $this->getTranslatedValue($service, 'name', $locale),
                    'description' => (string) $this->getTranslatedValue($service, 'description', $locale),
                    'image' => $this->imageUrl($service->image),
                    'slug' => $service->slug,
                    'icon' => $service->getMetaData('icon', true) ?: null,
                    'icon_image' => $this->imageUrl($service->getMetaData('icon_image', true) ?: null),
                ];
            })->values()->toArray();

            return [
                'page_id' => $page?->id,
                'shortcode' => $this->commonSectionAttributes($attrs),
                'services' => $items,
            ];
        }, 300, $cacheKeySuffix);
    }
}
