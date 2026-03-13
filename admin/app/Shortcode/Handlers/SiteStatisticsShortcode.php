<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

class SiteStatisticsShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'about-us-information';
    }

     public function handle(array $attrs, string $locale): array
    {
        $tabs = $this->parseShortcodeTabs($attrs, ['title', 'data', 'unit', 'image'], ['image']);

        return [
            'locale' => $locale,
            'data' => array_merge(
                ['tabs' => $tabs]
            ),
        ];
    }
}