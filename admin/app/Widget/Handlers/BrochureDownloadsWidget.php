<?php

namespace App\Widget\Handlers;

use App\Widget\Contracts\WidgetInterface;

class BrochureDownloadsWidget implements WidgetInterface
{
    public static function widget(): string
    {
        return 'brochure-downloads';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];
        $items = [];

        foreach ($data as $key => $value) {
            if (! str_starts_with($key, 'file_') || ! $value) {
                continue;
            }

            $extension = strtolower((string) pathinfo($value, PATHINFO_EXTENSION));
            $icon = match ($extension) {
                'pdf' => 'ti ti-file-type-pdf',
                'zip' => 'ti ti-file-zip',
                'doc', 'docx' => 'ti ti-file-type-doc',
                'xls', 'xlsx' => 'ti ti-file-type-xls',
                default => 'ti ti-file',
            };

            $items[] = [
                'name' => pathinfo($value, PATHINFO_FILENAME),
                'url' => route('public.download-file', ['filePath' => $value]),
                'icon' => $icon,
                'extension' => $extension,
            ];
        }

        return [
            'type' => 'brochure_downloads',
            'title' => $data['title'] ?? ($locale === 'en' ? 'Brochure' : 'Tài liệu'),
            'description' => $data['description'] ?? '',
            'items' => $items,
        ];
    }
}
