<?php 
namespace App\Shortcode\Handlers;

use App\shortcode\Contracts\ShortcodeInterface;
use Illuminate\Support\Facades\DB;
use Botble\Media\Facades\RvMedia;

class AboutUsInformationShortcode implements ShortcodeInterface
{
    public static function shortcode(): string
    {
        return 'about-us-information';
    }
    public function handle(array $attrs, string $locale): array
    {
        $tabs = $this->parseShortcodeTabs($attrs, ['title', 'description', 'icon', 'icon_image'], ['icon_image']);

        return [
            'locale' => $locale,
            'data' => array_merge(
                [
                    'image' => $this->imageUrl($attrs['image'] ?? null),
                    'image_1' => $this->imageUrl($attrs['image_1'] ?? null),
                    'image_2' => $this->imageUrl($attrs['image_2'] ?? null),
                    'data_count' => $attrs['data_count'] ?? null,
                    'data_count_description' => $attrs['data_count_description'] ?? null,
                    'author' => [
                        'name' => $attrs['author_name'] ?? null,
                        'title' => $attrs['author_title'] ?? null,
                        'avatar' => $this->imageUrl($attrs['author_avatar'] ?? null),
                        'signature' => $this->imageUrl($attrs['author_signature'] ?? null),
                    ],
                    'contact' => [
                        'title' => $attrs['contact_title'] ?? null,
                        'subtitle' => $attrs['contact_subtitle'] ?? null,
                        'url' => $attrs['contact_url'] ?? null,
                        'icon' => $attrs['contact_icon'] ?? null,
                        'icon_image' => $this->imageUrl($attrs['contact_icon_image'] ?? null),
                    ],
                    'tabs' => $tabs,
                ]
            ),
        ];
    }
     protected function imageUrl(?string $path): ?string
    {
        return $path ? RvMedia::getImageUrl($path) : null;
    }
    protected function parseShortcodeTabs(array $attrs, array $fields, array $imageFields = []): array
    {
        $quantity = isset($attrs['quantity']) ? (int) $attrs['quantity'] : 0;
        $tabs = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $tab = [];
            foreach ($fields as $field) {
                $key = "{$field}_{$i}";
                $value = $attrs[$key] ?? null;

                if ($value && in_array($field, $imageFields)) {
                    $value = $this->imageUrl($value);
                }

                $tab[$field] = $value;
            }
            $tabs[] = $tab;
        }

        return $tabs;
    }
}