<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\Portfolio\Models\DemoWebsite;

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
                $slug = $this->getSlug($web);
                return[
                    'name' => $this->getTranslatedValue($web, 'name', $locale),
                    'slug' => $slug,
                    'img_full' => $this->imageUrl($web->img_full),
                    'date' => $web->created_at->format('d/m/Y'),
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