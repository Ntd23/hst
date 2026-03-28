<?php

namespace App\Widget\Contracts;

interface WidgetInterface
{
    public static function widget(): string;

    public function handle(array $widget, string $locale): array|string|null;
}
