<?php 
namespace App\Shortcode\Contracts;

interface ShortcodeInterface 
{
    public static function shortcode(): string;

    public function handle(array $attrs, string $locale): array;
}