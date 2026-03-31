<?php 
namespace App\Shortcode\Handlers;
<<<<<<< HEAD
use App\Shortcode\Contracts\ShortcodeInterface;
=======
use App\shortcode\Contracts\ShortcodeInterface;
>>>>>>> origin/main
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

class ContactFormShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'contact-form';
    }
    public function handle(array $attrs, string $locale): array
    {
        $items = $attrs;
        return array_merge(
            ['locale' => $locale],
            [
                'items' => $attrs,
            ]
        );
    }
}