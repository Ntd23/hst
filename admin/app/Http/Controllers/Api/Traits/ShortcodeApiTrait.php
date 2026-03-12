<?php

namespace App\Http\Controllers\Api\Traits;

use Botble\Media\Facades\RvMedia;
use Botble\Page\Models\Page;
use Botble\Shortcode\Compilers\ShortcodeCompiler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Trait ShortcodeApiTrait
 *
 * Cung cấp các helper dùng chung cho mọi Page API Controller
 * (HomeController, ContactController, AboutController...).
 *
 * Controller chỉ cần: use ShortcodeApiTrait;
 */
trait ShortcodeApiTrait
{
    // ──────────────────────────────────────────────
    //  SHORTCODE PARSING
    // ──────────────────────────────────────────────

    /**
     * Tìm shortcode theo tên trong content và trả về mảng attributes.
     * Dùng Botble's ShortcodeCompiler::parseAttributes() thay vì regex tự viết.
     *
     * @return array|null  Mảng attributes hoặc null nếu không tìm thấy
     */
    protected function getShortcodeAttributes(string $content, string $shortcodeName): ?array
    {
        // Tìm [shortcode-name ...attrs...] hoặc [shortcode-name ...attrs...][/shortcode-name]
        $pattern = '/\[' . preg_quote($shortcodeName, '/') . '\s+([^\]]*)\]/';

        if (!preg_match($pattern, $content, $matches)) {
            return null;
        }

        // Dùng Botble's parser để parse attribute string → mảng key-value
        $compiler = app(ShortcodeCompiler::class);
        $attributes = $this->parseShortcodeAttributeString($matches[1]);

        return !empty($attributes) ? $attributes : null;
    }

    /**
     * Parse TẤT CẢ shortcodes từ content → mảng ['shortcode-name' => [attrs], ...]
     * Dùng cho pattern dynamic dispatch (giống BlogController).
     *
     * @return array<string, array>
     */
    protected function getAllShortcodeAttributes(string $content): array
    {
        // Cho phép shortcode có tham số, không có tham số, và có nội dung lồng nhau
        preg_match_all('/\[([a-zA-Z0-9\-_]+)(?:\s+([^\]]*?))?\](?:(.*?)\[\/\1\])?/s', $content, $matches, PREG_SET_ORDER);

        $result = [];
        foreach ($matches as $match) {
            $name = $match[1];
            $attrString = $match[2] ?? '';
            $innerContent = $match[3] ?? '';
            
            $attrs = $this->parseShortcodeAttributeString($attrString);
            
            // Nếu có nội dung lồng nhau, thêm vào attributes
            if ($innerContent !== '') {
                $attrs['content'] = trim($innerContent);
            }
            
            // Trả về mảng tuần tự để giữ thứ tự shortcode và không bị ghi đè trùng tên
            $result[] = [
                'name' => $name,
                'attrs' => $attrs,
            ];
        }

        return $result;
    }

    /**
     * Parse attribute string thành mảng key-value
     * Sử dụng cùng regex pattern mà Botble's ShortcodeCompiler dùng.
     */
    private function parseShortcodeAttributeString(string $text): array
    {
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        $attributes = [];

        // Pattern giống hệt ShortcodeCompiler::parseAttributes()
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';

        if (preg_match_all($pattern, preg_replace('/[\x{00a0}\x{200b}]+/u', ' ', $text), $match, PREG_SET_ORDER)) {
            foreach ($match as $item) {
                if (!empty($item[1])) {
                    $attributes[strtolower($item[1])] = stripcslashes($item[2]);
                } elseif (!empty($item[3])) {
                    $attributes[strtolower($item[3])] = stripcslashes($item[4]);
                } elseif (!empty($item[5])) {
                    $attributes[strtolower($item[5])] = stripcslashes($item[6]);
                } elseif (isset($item[7]) && strlen($item[7])) {
                    $attributes[] = stripcslashes($item[7]);
                } elseif (isset($item[8])) {
                    $attributes[] = stripcslashes($item[8]);
                }
            }
        }

        return $attributes;
    }

    /**
     * Parse các tabs (numbered attributes) từ shortcode attributes.
     *
     * Ví dụ: quantity="2" title_1="A" description_1="B" title_2="C" description_2="D"
     *   → [['title' => 'A', 'description' => 'B'], ['title' => 'C', 'description' => 'D']]
     *
     * @param array    $attrs       Mảng attributes từ getShortcodeAttributes()
     * @param array    $fields      Danh sách field name cần lấy, vd ['title', 'description', 'icon']
     * @param string[] $imageFields Các field là ảnh (sẽ được convert qua RvMedia::getImageUrl)
     */
    protected function parseShortcodeTabs(array $attrs, array $fields, array $imageFields = []): array
    {
        $quantity = isset($attrs['quantity']) ? (int) $attrs['quantity'] : 0;
        $tabs = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $tab = [];
            foreach ($fields as $field) {
                $key = "{$field}_{$i}";
                $value = $attrs[$key] ?? null;

                if ($value && in_array($field, $imageFields)) {
                    $value = $this->imageUrl($value);
                }

                $tab[$field] = $value;
            }
            $tabs[] = $tab;
        }

        return $tabs;
    }

    /**
     * Lấy data shortcode (attributes + content) từ content.
     *
     * @return array|null ['attrs' => array, 'content' => ?string]
     */
    protected function getShortcodeData(string $content, string $shortcodeName): ?array
    {
        $name = preg_quote($shortcodeName, '/');
        $pattern = '/\[' . $name . '(?:\s+([^\]]*))?\](?:([\s\S]*?)\[\/' . $name . '\])?/i';

        if (!preg_match($pattern, $content, $matches)) {
            return null;
        }

        $attrs = [];
        if (!empty($matches[1])) {
            $attrs = $this->parseShortcodeAttributeString($matches[1]);
        }

        $inner = $matches[2] ?? null;

        return [
            'attrs' => $attrs,
            'content' => $inner,
        ];
    }

    // ──────────────────────────────────────────────
    //  PAGE & CONTENT
    // ──────────────────────────────────────────────

    /**
     * Lấy Page model theo theme_option key.
     *  - 'homepage_id'     → Trang chủ
     *  - 'contact_page_id' → Liên hệ
     *  - etc.
     */
    protected function getPageByThemeOption(string $optionKey): ?Page
    {
        $pageId = theme_option($optionKey);
        if (!$pageId) {
            return null;
        }

        return Page::with('translations')->find($pageId);
    }

    /**
     * Shortcut: lấy homepage (dùng cho HomeController).
     */
    protected function getPageById(string $pageId): ?Page
    {
        return $this->getPageByThemeOption($pageId);
    }

    /**
     * Combo: lấy shortcode attributes từ 1 page (theo theme_option).
     *
     * @return array|null  attributes hoặc null
     */
    protected function getPageShortcode(string $themeOptionKey, string $shortcodeName, string $locale): ?array
    {
        $page = $this->getPageByThemeOption($themeOptionKey);
        if (!$page) {
            return null;
        }

        $content = $this->getTranslatedValue($page, 'content', $locale);
        if (!$content) {
            return null;
        }

        return $this->getShortcodeAttributes($content, $shortcodeName);
    }

    /**
     * Tìm shortcode trong bất kỳ page publish nào (không cần theme_option page_id).
     *
     * @return array|null  attributes hoặc null
     */
    protected function getShortcodeFromAnyPage(string $shortcodeName, string $locale): ?array
    {
        $page = $this->getPageByShortcode($shortcodeName, $locale);
        if (!$page) {
            return null;
        }

        $content = $this->getTranslatedValue($page, 'content', $locale) ?: $page->content;
        if (!$content) {
            return null;
        }

        return $this->getShortcodeAttributes($content, $shortcodeName);
    }

    /**
     * Lấy data shortcode (attributes + content) từ bất kỳ page publish.
     *
     * @return array|null ['attrs' => array, 'content' => ?string]
     */
    protected function getShortcodeDataFromAnyPage(string $shortcodeName, string $locale): ?array
    {
        $like = "%[{$shortcodeName}%";

        $pages = Page::query()
            ->with('translations')
            ->wherePublished()
            ->where('content', 'like', $like)
            ->get();

        $data = $this->findShortcodeDataInPages($pages, $shortcodeName, $locale);
        if ($data) {
            return $data;
        }

        $pages = Page::query()
            ->with('translations')
            ->wherePublished()
            ->get();

        return $this->findShortcodeDataInPages($pages, $shortcodeName, $locale);
    }

    /**
     * Tìm page chứa shortcode bất kỳ (không cần theme_option page_id).
     */
    protected function getPageByShortcode(string $shortcodeName, string $locale): ?Page
    {
        $like = "%[{$shortcodeName}%";

        $pages = Page::query()
            ->with('translations')
            ->wherePublished()
            ->where('content', 'like', $like)
            ->get();

        $page = $this->findPageByShortcodeInPages($pages, $shortcodeName, $locale);
        if ($page) {
            return $page;
        }

        $pages = Page::query()
            ->with('translations')
            ->wherePublished()
            ->get();

        return $this->findPageByShortcodeInPages($pages, $shortcodeName, $locale);
    }

    private function findPageByShortcodeInPages($pages, string $shortcodeName, string $locale): ?Page
    {
        foreach ($pages as $page) {
            $content = $this->getTranslatedValue($page, 'content', $locale) ?: $page->content;
            if (!$content) {
                continue;
            }

            if ($this->getShortcodeAttributes($content, $shortcodeName)) {
                return $page;
            }
        }

        return null;
    }

    private function findShortcodeDataInPages($pages, string $shortcodeName, string $locale): ?array
    {
        foreach ($pages as $page) {
            $content = $this->getTranslatedValue($page, 'content', $locale) ?: $page->content;
            if (!$content) {
                continue;
            }

            $data = $this->getShortcodeData($content, $shortcodeName);
            if ($data) {
                return $data;
            }
        }

        return null;
    }

    // ──────────────────────────────────────────────
    //  TRANSLATION
    // ──────────────────────────────────────────────

    /**
     * Lấy giá trị đã dịch từ relation translations.
     *
     * LanguageAdvancedManager bind locale lúc boot → accessor không phản ánh
     * locale mới set qua API → phải tự đọc từ relation translations.
     *
     * API nhận locale ngắn (en, vi) nhưng bảng translations
     * lưu lang_code đầy đủ (en_US, vi). Cần map qua bảng languages.
     */
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

    /**
     * Map locale ngắn (en, vi) → lang_code trong DB (en_US, vi).
     */
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

    // ──────────────────────────────────────────────
    //  MEDIA
    // ──────────────────────────────────────────────

    /**
     * Trả về image URL đầy đủ, hoặc null nếu path rỗng.
     */
    protected function imageUrl(?string $path): ?string
    {
        return $path ? RvMedia::getImageUrl($path) : null;
    }

    // ──────────────────────────────────────────────
    //  RESPONSE HELPERS
    // ──────────────────────────────────────────────

    /**
     * Wrapper chuẩn cho meta page:
     *   - Lấy locale
     *   - Cache
     *   - Merge SEO meta từ theme option + page meta
     *   - Trả JSON
     */
    protected function metaResponse(Request $request, string $pageOptionKey, string $cacheKeyPrefix): JsonResponse
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:pages:{$cacheKeyPrefix}:meta:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale, $pageOptionKey) {
            $page = $this->getPageByThemeOption($pageOptionKey);

            return $this->buildMetaPayload($page, $locale);
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Wrapper chuẩn cho meta page theo shortcode:
     *   - Lấy locale
     *   - Cache
     *   - Merge SEO meta từ theme option + page meta
     *   - Trả JSON
     */
    protected function metaResponseFromShortcode(Request $request, string $shortcodeName, string $cacheKeyPrefix): JsonResponse
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:pages:{$cacheKeyPrefix}:meta:{$locale}";

        $payload = Cache::remember($cacheKey, 300, function () use ($locale, $shortcodeName) {
            $page = $this->getPageByShortcode($shortcodeName, $locale);

            return $this->buildMetaPayload($page, $locale);
        });

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    private function buildMetaPayload(?Page $page, string $locale): array
    {
        $seoTitle = theme_option('seo_title', theme_option('site_title', config('app.name')));
        $seoDescription = theme_option('seo_description', '');
        $seoImage = theme_option('seo_image', '');
        $seoIndex = (bool) theme_option('seo_index', true);
        $ogImage = null;

        if ($page) {
            $meta = $page->getMetaData('seo_meta', true);
            if (!empty($meta['seo_title'])) {
                $seoTitle = $meta['seo_title'];
            }
            if (!empty($meta['seo_description'])) {
                $seoDescription = $meta['seo_description'];
            }
            if (!empty($meta['seo_image'])) {
                $ogImage = $this->imageUrl($meta['seo_image']);
            }
            if (!empty($meta['index'])) {
                $seoIndex = $meta['index'] === 'index';
            }
        }

        if (!$ogImage && $seoImage) {
            $ogImage = $this->imageUrl($seoImage);
        }

        return [
            'locale' => $locale,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'og_image' => $ogImage,
            'seo_index' => $seoIndex,
            'favicon' => theme_option('favicon')
                ? $this->imageUrl(theme_option('favicon'))
                : null,
        ];
    }

    /**
     * Wrapper chuẩn cho mọi section API:
     *   - Lấy locale
     *   - Cache
     *   - Gọi callback
     *   - Trả JSON (hoặc 404 nếu null)
     *
     * @param string   $sectionName  Tên section dùng cho cache key, vd 'contact-block'
     * @param callable $callback     fn(string $locale): ?array → trả mảng data hoặc null
     * @param int      $cacheTtl     Thời gian cache (giây), mặc định 300
     */
     protected function sectionResponse(
         Request $request,
         string $sectionName,
         callable $callback,
         int $cacheTtl = 300,
         ?string $cacheKeySuffix = null
     ): JsonResponse
    {
        $locale = $this->getApiLocale($request);
        $cacheKey = "api:pages:{$sectionName}:{$locale}";
        if ($cacheKeySuffix) {
            $cacheKey .= ':' . $cacheKeySuffix;
        }

        $payload = Cache::remember($cacheKey, $cacheTtl, fn() => $callback($locale));

        if (!$payload) {
            return response()->json([
                'message' => ucfirst(str_replace('-', ' ', $sectionName)) . ' not found',
                'locale' => $locale,
                'data' => null,
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($payload, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Helper: trích xuất các attributes cơ bản mà hầu hết shortcode đều có.
     *
     * @return array  Mảng chứa style, title, subtitle, description, button, background
     */
    protected function commonSectionAttributes(array $attrs): array
    {
        return [
            'style' => $attrs['style'] ?? 'style-1',
            'title' => $attrs['title'] ?? null,
            'subtitle' => $attrs['subtitle'] ?? null,
            'description' => $attrs['description'] ?? null,
            'button' => [
                'label' => $attrs['button_label'] ?? null,
                'url' => $attrs['button_url'] ?? null,
            ],
            'background_color' => $attrs['background_color'] ?? null,
            'background_image' => $this->imageUrl($attrs['background_image'] ?? null),
        ];
    }
}
