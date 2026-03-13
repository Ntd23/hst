<?php 
namespace App\Services;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Facades\DB;

class PageService
{
    public function getPage($slug, $locale){
        $pageSlug = Slug::where('key', $slug)
            ->where('reference_type', Page::class)
            ->first();

        if (!$pageSlug || !$pageSlug->reference) {
            return null;
        }

        // lấy thông tin page.
        $page = $pageSlug->reference;
        $page->loadMissing('translations');

        $content = $this->getTranslatedValue($page, 'content', $locale) ?: $page->content;
        $isBlogPage = (string) $page->id === (string) theme_option('blog_page_id');
        if (!$content && !$isBlogPage) {
            return null;
        }
        return $content;
    }


    protected function getTranslatedValue($model, string $column, string $locale): ?string
    {
        if ($model->relationLoaded('translations')) {
            $langCode = $this->getLangCode($locale);

            $translated = $model->translations
                ->where('lang_code', $langCode)
                ->first();

            if ($translated && isset($translated->{$column})) {
                return $translated->{$column};
            }
        }

        return $model->getAttributes()[$column] ?? null;
    }

     protected function getLangCode(string $locale): string
    {
        static $map = null;
        if ($map === null) {
            $map = DB::table('languages')
                ->pluck('lang_code', 'lang_locale')
                ->toArray();
        }

        return $map[$locale] ?? $locale;
    }
}

