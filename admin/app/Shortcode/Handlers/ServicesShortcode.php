<?php

namespace App\Shortcode\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Shortcode\Contracts\ShortcodeInterface;

class ServicesShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

    public static function shortcode(): string
    {
        return 'services';
    }

    public function handle(array $attrs, string $locale): array|string|null
    {
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
            $slug = $this->getSlug($service);

            return [
                'id' => $service->id,
                'locale' => $locale,
                'name' => (string) $this->getTranslatedValue($service, 'name', $locale),
                'description' => (string) $this->getTranslatedValue($service, 'description', $locale),
                'image' => $this->imageUrl($service->image),
                'slug' => $slug,
                'icon' => $service->getMetaData('icon', true) ?: null,
                'icon_image' => $this->imageUrl($service->getMetaData('icon_image', true) ?: null),
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'shortcode' => array_filter([
                // 'style' => $attrs['style'] ?? null,
                'title' => $attrs['title'] ?? null,
                'subtitle' => $attrs['subtitle'] ?? null,
                // 'background_color' => $attrs['background_color'] ?? null,
                'enable_lazy_loading' => $attrs['enable_lazy_loading'] ?? null,
            ], fn ($value) => $value !== null && $value !== ''),
            'services' => $items,
        ];
    }
}
