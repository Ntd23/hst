<?php 
namespace App\Shortcode\Handlers;
use App\Shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

class ContentFeatureListShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'content-feature-list';
    }
    
    public function handle(array $attrs, string $locale): array
    {
        $quantity = (int) ($attrs['quantity'] ?? 0);
        $features = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $title = $attrs["title_$i"] ?? null;
            $description = $attrs["description_$i"] ?? null;
            $icon = $attrs["icon_$i"] ?? null;

            if ($title || $description || $icon) {
                $features[] = [
                    'title' => $title,
                    'description' => $description,
                    'icon' => $icon,
                ];
            }
        }

        return [
            'shortcode' => [
                'title' => $attrs['title'] ?? null,
                'description' => $attrs['description'] ?? null,
                'background_color' => $attrs['background_color'] ?? null,
            ],
            'features' => $features,
        ];
    }
}
