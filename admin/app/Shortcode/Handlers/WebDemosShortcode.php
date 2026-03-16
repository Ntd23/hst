<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Models\DemoWebsite;

class WebDemosShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

       public static function shortcode(): string
    {
        return 'web-demos';
    }
    public function handle(array $attrs, string $locale): array
    {
        $limit = isset($attrs['limit']) ? (int) $attrs['limit'] : 6;

        $webs = DemoWebsite::limit($limit)
            ->get()
            ->map(function ($web) use ($locale){
                return[
                    'name' => $this->getTranslatedValue($web, 'name', $locale),
                    'slug' => $web->slug,
                    'img_full' => $this->imageUrl($web->img_full),
                ];
            });

         return array_merge(
            ['locale' => $locale],
            [
                'items' => $webs,
            ]
        );
    }
}