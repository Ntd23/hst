<?php

namespace App\Widget\Handlers;

use App\Widget\Contracts\WidgetInterface;

class ContactBlockWidget implements WidgetInterface
{
    public static function widget(): string
    {
        return 'contact-block';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];

        return [
            'type' => 'contact_block',
            'title' => $data['title'] ?? '',
            'phone_number' => $data['phone_number'] ?? '',
            'background_color' => $data['background_color'] ?? '#191D88',
        ];
    }
}
