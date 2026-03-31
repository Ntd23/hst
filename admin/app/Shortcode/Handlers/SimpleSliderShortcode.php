<?php 
namespace App\Shortcode\Handlers;
use App\Shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\SimpleSlider\Models\SimpleSlider;

class SimpleSliderShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'simple-slider';
    }

    public function handle(array $attrs, string $locale): array|string|null
    {
         $sliderKey = $attrs['key'] ?? null;
        if (!$sliderKey) {
            return null;
        }

        $slider = SimpleSlider::query()
            ->wherePublished()
            ->where('key', $sliderKey)
            ->first();

        if (!$slider || $slider->sliderItems->isEmpty()) {
            return null;
        }

        $slider->sliderItems->loadMissing('metadata');

        $items = $slider->sliderItems->map(function ($item) use ($locale) {
            return [
                'id' => $item->id,
                'locale' => $locale,
                'title' => (string) $item->title,
                'description' => (string) $item->description,
                'link' => (string) $item->link,
                'order' => (int) $item->order,
                'image' => $this->imageUrl($item->image),
                'tablet_image' => $this->imageUrl($item->getMetaData('tablet_image', true) ?: null),
                'mobile_image' => $this->imageUrl($item->getMetaData('mobile_image', true) ?: null),
                'subtitle' => $item->getMetaData('subtitle', true) ?: null,
                'button_label' => $item->getMetaData('button_label', true) ?: null,
                'data_count' => $item->getMetaData('data_count', true) ?: null,
                'data_count_description' => $item->getMetaData('data_count_description', true) ?: null,
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'slider_id' => $slider->id,
            'slider_key' => $slider->key,
            'slider_name' => (string) $slider->name,
            'items' => $items,
        ];
    }
}