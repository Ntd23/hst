<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

class ContactBlockShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'contact-block';
    }
    public function handle(array $attrs, string $locale): array
    {
        return [
            'locale' => $locale,
            'data' => array_merge(
                ['phone_number' => $attrs['phone_number'] ?? null]
            ),
        ];
    }
}