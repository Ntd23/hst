<?php 
namespace App\Shortcode\Handlers;
use App\shortcode\Contracts\ShortcodeInterface;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;

class TeamShortcode implements ShortcodeInterface
{
    use ShortcodeApiTrait;

     public static function shortcode(): string
    {
        return 'team';
    }
    public function handle(array $attrs, string $locale): array
    {
        $teamIds = isset($attrs['team_ids'])
            ? array_filter(explode(',', $attrs['team_ids']))
            : [];

        if (empty($teamIds)) {
            return null;
        }

        $teams = \Botble\Team\Models\Team::query()
            ->with(['translations', 'metadata', 'slugable'])
            ->whereIn('id', $teamIds)
            ->wherePublished()
            ->get();

        if ($teams->isEmpty()) {
            return null;
        }

        $items = $teams->map(function ($team) use ($locale) {
            return [
                'id' => $team->id,
                'name' => $this->getTranslatedValue($team, 'name', $locale),
                'title' => $this->getTranslatedValue($team, 'title', $locale),
                'location' => $this->getTranslatedValue($team, 'location', $locale),
                'content' => $this->getTranslatedValue($team, 'content', $locale),
                'phone' => $this->getTranslatedValue($team, 'phone', $locale),
                'address' => $this->getTranslatedValue($team, 'address', $locale),
                'email' => $team->email,
                'website' => $team->website,
                'photo' => $this->imageUrl($team->photo),
                'url' => $team->url ?? null,
                'socials' => $team->socials ?? [],
            ];
        })->values()->toArray();

        return array_merge(
            ['locale' => $locale],
            ['items' => $items]
        );
    }
}