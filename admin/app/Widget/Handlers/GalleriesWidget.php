<?php

namespace App\Widget\Handlers;

use App\Http\Controllers\Api\Traits\WidgetApiTrait;
use App\Widget\Contracts\WidgetInterface;
use Botble\Gallery\Models\Gallery;

class GalleriesWidget implements WidgetInterface
{
    use WidgetApiTrait;

    public static function widget(): string
    {
        return 'galleries';
    }

    public function handle(array $widget, string $locale): array
    {
        $data = $widget['data'] ?? [];
        $limit = (int) ($data['limit'] ?? 6);

        $galleries = Gallery::query()
            ->with('slugable')
            ->wherePublished()
            ->when($limit > 0, fn ($query) => $query->limit($limit))
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        return [
            'type' => 'galleries',
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'limit' => $limit,
            'items' => $galleries->map(fn ($gallery) => [
                'id' => $gallery->id,
                'name' => $gallery->name,
                'image' => $this->resolveMediaUrl($gallery->image),
                'url' => $gallery->url ?? null,
            ])->values()->toArray(),
        ];
    }
}
