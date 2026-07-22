<?php

namespace App\Shortcode\Handlers;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use App\Shortcode\Contracts\ShortcodeInterface;

class TechnologyHeroShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

    public static function shortcode(): string
    {
        return 'technology-hero';
    }

    public function handle(array $attrs, string $locale): array
    {
        return [
            'locale' => $locale,
            'data' => [
                'badge' => $attrs['badge'] ?? 'Giải pháp công nghệ toàn diện',
                'title' => $attrs['title'] ?? 'Kiến tạo giải pháp',
                'highlight_text' => $attrs['highlight_text'] ?? 'công nghệ đột phá',
                'description' => $attrs['description'] ?? 'Chúng tôi thiết kế và phát triển các nền tảng số giúp doanh nghiệp vận hành hiệu quả, tối ưu trải nghiệm và sẵn sàng tăng trưởng.',
                'primary_button' => $attrs['primary_button'] ?? 'Khám phá giải pháp',
                'primary_url' => $attrs['primary_url'] ?? '/giai-phap',
                'secondary_button' => $attrs['secondary_button'] ?? 'Xem dự án',
                'secondary_url' => $attrs['secondary_url'] ?? '/du-an',
                'capability_1' => $attrs['capability_1'] ?? 'Thiết kế UI/UX',
                'capability_2' => $attrs['capability_2'] ?? 'Phát triển website',
                'capability_3' => $attrs['capability_3'] ?? 'Ứng dụng và AI',
                'primary_color' => $attrs['primary_color'] ?? '#0866FF',
                'glow_color' => $attrs['glow_color'] ?? '#35D6FF',
                'enable_3d' => $attrs['enable_3d'] ?? 'yes',
                'poster' => isset($attrs['poster']) ? $this->imageUrl($attrs['poster']) : null,
            ],
        ];
    }
}
