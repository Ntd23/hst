<?php

namespace App\Widget\Core;

class WidgetManager
{
    public function getWidgets(array $widgets, string $locale): array
    {
        $sections = [];

        foreach ($widgets as $widget) {
            $widgetKey = $widget['widget'];
            $handlerClass = $this->resolveHandler($widgetKey);

            $content = class_exists($handlerClass)
                ? app($handlerClass)->handle($widget, $locale)
                : ($widget['data'] ?? null);

            $sections[] = [
                'widget' => $widgetKey,
                'content' => $content,
                'handler' => $handlerClass,
                'widget_name' => $widget['widget_name'] ?? null,
                'widget_id' => $widget['widget_id'] ?? null,
                'position' => $widget['position'] ?? null,
            ];
        }

        return $sections;
    }

    protected function resolveHandler(string $widgetKey): string
    {
        return 'App\\Widget\\Handlers\\'
            . str_replace(' ', '', ucwords(str_replace('-', ' ', $widgetKey)))
            . 'Widget';
    }
}
