<?php

namespace App\Shortcode\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Shortcode\Contracts\ShortcodeInterface;
use Botble\Portfolio\Models\Service;
use Illuminate\Support\Arr;

class ServicesTabShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

    public static function shortcode(): string
    {
        return 'services-tab';
    }

    public function handle(array $attrs, string $locale): ?array
    {
        $tabs = $this->buildTabs($attrs);

        if (empty($tabs)) {
            return null;
        }

        $serviceIds = collect($tabs)
            ->pluck('service_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        if (empty($serviceIds)) {
            return null;
        }

        $services = Service::query()
            ->with(['metadata', 'slugable', 'translations'])
            ->wherePublished()
            ->whereIn('id', $serviceIds)
            ->get()
            ->keyBy('id');

        if ($services->isEmpty()) {
            return null;
        }

        $items = collect($tabs)
            ->map(function (array $tab) use ($services, $locale) {
                $service = $services->get((int) $tab['service_id']);

                if (! $service) {
                    return null;
                }

                $slug = $this->getSlug($service);
                $serviceName = $this->getTranslatedValue($service, 'name', $locale) ?: $service->name;
                $serviceDescription = $this->getTranslatedValue($service, 'description', $locale) ?: $service->description;

                return [
                    'service_id' => (int) $service->id,
                    'service' => [
                        'id' => (int) $service->id,
                        'name' => $serviceName,
                        'description' => $serviceDescription,
                        'slug' => $slug,
                        'url' => $service->url ?? ($slug ? url($slug) : null),
                        'image' => $this->imageUrl($service->image),
                        'icon' => $service->getMetaData('icon', true) ?: null,
                        'icon_image' => $this->imageUrl($service->getMetaData('icon_image', true) ?: null),
                    ],
                    'title' => $tab['title'] ?: $serviceName,
                    'description' => $tab['description'] ?: $serviceDescription,
                    'image' => $this->imageUrl($tab['image'] ?? null) ?: $this->imageUrl($service->image),
                    'featured_titles' => $tab['featured_titles'],
                    'button_label' => $tab['button_label'] ?: ($attrs['button_label'] ?? __('Read More')),
                    'button_url' => $tab['button_url'] ?: ($service->url ?? ($slug ? url($slug) : null)),
                ];
            })
            ->filter()
            ->values()
            ->toArray();

        if (empty($items)) {
            return null;
        }

        return [
            'locale' => $locale,
            'shortcode' => array_filter([
                ...$this->commonSectionAttributes($attrs),
                'title' => $attrs['title'] ?? null,
                'button_label' => $attrs['button_label'] ?? null,
                'button_url' => $attrs['button_url'] ?? null,
                'enable_lazy_loading' => $attrs['enable_lazy_loading'] ?? null,
            ], fn ($value) => $value !== null && $value !== ''),
            'tabs' => $items,
        ];
    }

    private function buildTabs(array $attrs): array
    {
        $tabs = [];

        foreach ($this->extractTabIndexes($attrs) as $index) {
            $serviceId = Arr::get($attrs, "service_id_{$index}");
            $title = Arr::get($attrs, "title_{$index}");

            if (! $serviceId || ! $title) {
                continue;
            }

            $featuredTitles = collect(range(1, 5))
                ->map(fn ($featureIndex) => Arr::get($attrs, "featured_title_{$featureIndex}_{$index}"))
                ->filter()
                ->values()
                ->all();

            $tabs[] = [
                'service_id' => $serviceId,
                'title' => $title,
                'description' => Arr::get($attrs, "description_{$index}"),
                'image' => Arr::get($attrs, "image_{$index}"),
                'button_label' => Arr::get($attrs, "button_label_{$index}"),
                'button_url' => Arr::get($attrs, "button_url_{$index}"),
                'featured_titles' => $featuredTitles,
            ];
        }

        return $tabs;
    }

    private function extractTabIndexes(array $attrs): array
    {
        $indexes = [];

        foreach (array_keys($attrs) as $key) {
            if (preg_match('/^service_id_(\d+)$/', $key, $matches)) {
                $indexes[] = (int) $matches[1];
            }
        }

        sort($indexes);

        return array_values(array_unique($indexes));
    }
}
