<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\WidgetApiTrait;
use App\Widget\Contracts\WidgetInterface;

class CoreSimpleMenuWidget implements WidgetInterface
{
    use WidgetApiTrait;

    public static function widget(): string
    {
        return 'core-simple-menu';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];

        return [
            'type' => 'menu',
            'title' => $data['name'] ?? '',
            'items' => $this->parseCoreSimpleMenuItems($data['items'] ?? []),
        ];
    }
}
