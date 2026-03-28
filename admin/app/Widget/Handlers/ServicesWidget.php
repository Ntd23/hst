<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Widget\Contracts\WidgetInterface;
use Botble\Portfolio\Models\Service;

class ServicesWidget implements WidgetInterface
{
    use ShortcodeApiTrait;

    public static function widget(): string
    {
        return 'services';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];
        $serviceIds = $data['service_ids'] ?? [];

        $services = Service::query()
            ->with(['slugable', 'translations'])
            ->wherePublished()
            ->when(! empty($serviceIds), fn ($query) => $query->whereIn('id', $serviceIds))
            ->get();

        return [
            'type' => 'services',
            'title' => $data['title'] ?? ($locale === 'en' ? 'Services' : 'Dịch vụ'),
            'items' => $services->map(fn ($service) => [
                'id' => $service->id,
                'name' => $this->getTranslatedValue($service, 'name', $locale),
                'slug' => $this->getSlug($service),
                'url' => $this->getSlug($service) ? '/services/' . $this->getSlug($service) : null,
            ])->values()->toArray(),
        ];
    }
}
