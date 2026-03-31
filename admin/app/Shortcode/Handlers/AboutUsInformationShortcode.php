<?php 
namespace App\Shortcode\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Shortcode\Contracts\ShortcodeInterface;
use Illuminate\Support\Facades\DB;
use Botble\Media\Facades\RvMedia;

class AboutUsInformationShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;
    public static function shortcode(): string
    {
        return 'about-us-information';
    }
    public function handle(array $attrs, string $locale): array
    {
        $tabs = $this->parseShortcodeTabs($attrs, ['title', 'description', 'icon', 'icon_image'], ['icon_image']);

        return [
            'locale' => $locale,
            'data' => array_filter(
                [
                    'style' => $attrs['style'] ?? null,
                    'title' => $attrs['title'] ?? null,
                    'subtitle' => $attrs['subtitle'] ?? null,
                    'description' => $attrs['description'] ?? null,
                    'button_label' => $attrs['button_label'] ?? null,
                    'button_url' => $attrs['button_url'] ?? null,
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
                ],
                fn($val) => $val !== null && $val !== ''
            ),
        ];
    }
  
}