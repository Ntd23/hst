<?php

namespace App\Services;

use App\Widget\Core\WidgetManager;

class WidgetService
{
    public function __construct(
        protected WidgetManager $manager
    ) {}

    public function allWidgets(array $widgets, string $locale): array
    {
        return $this->manager->getWidgets($widgets, $locale);
    }
}
