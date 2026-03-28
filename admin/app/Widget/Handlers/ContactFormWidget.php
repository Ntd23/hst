<?php

namespace App\Widget\Handlers;

use App\Widget\Contracts\WidgetInterface;

class ContactFormWidget implements WidgetInterface
{
    public static function widget(): string
    {
        return 'contact-form';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];

        return [
            'type' => 'contact_form',
            'title' => $data['title'] ?? ($locale === 'en' ? 'Send Us Message' : 'Gửi tin nhắn cho chúng tôi'),
            'button_label' => $data['button_label'] ?? ($locale === 'en' ? 'Send Message' : 'Gửi tin nhắn'),
            'background_color' => $data['background_color'] ?? '#ECF6FA',
        ];
    }
}
