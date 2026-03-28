<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\WidgetApiTrait;
use App\Widget\Contracts\WidgetInterface;

class ContactInformationWidget implements WidgetInterface
{
    use WidgetApiTrait;

    public static function widget(): string
    {
        return 'contact-information';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];

        return [
            'type' => 'contact_information',
            'alignment' => $data['alignment'] ?? 'start',
            'items' => $this->parseContactInfoItems($data),
        ];
    }
}
