<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

class ServicesShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;
     public static function shortcode(): string
    {
        return 'services';
    }

    public function handle(array $attrs, string $locale): array
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
            'locale' => $locale,
            'services' => $items,
        ];
    }
}