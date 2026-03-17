<?php

namespace App\Services;

use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;

class PageService
{
    use ShortcodeApiTrait;

    /**
     * Tìm Slug record theo key (dùng chung cho meta, sections, details).
     */
    public function resolveSlug(string $slug): ?Slug
    {
        return Slug::where('key', $slug)->first();
    }

    /**
     * Tìm Slug record chỉ cho Page.
     */
    public function resolvePageSlug(string $slug): ?Slug
    {
        return Slug::where('key', $slug)
            ->where('reference_type', Page::class)
            ->first();
    }

    /**
     * Lấy thông tin page + shortcode content (dùng cho getSections).
     */
    public function getPage(string $slug, string $locale): ?array
    {
        $pageSlug = $this->resolvePageSlug($slug);
        if (!$pageSlug || !$pageSlug->reference) {
            return null;
        }

        $page = $pageSlug->reference;

        // Load translations TRƯỚC khi đọc content
        $page->loadMissing('translations');

        // Đọc content TRƯỚC khi makeHidden (makeHidden có thể chặn accessor trên một số model)
        $shortcode = $this->getTranslatedValue($page, 'content', $locale) ?: $page->content;
        // Sau khi đọc xong thì ẩn content khỏi JSON trả về (tránh gửi raw content lớn)
        $page->makeHidden('content');

        // Trang Blog trong Botble thường có content rỗng — Botble dùng loop.blade.php để render.
        // Với API, ta inject virtual shortcode [blog-posts] để ShortcodeManager dispatch BlogPostsShortcode.
        $isBlogPage = (string)$page->id === (string)theme_option('blog_page_id');

        if (!$shortcode && !$isBlogPage) {
            return null;
        }

        if ($isBlogPage && !str_contains((string)$shortcode, '[blog-posts')) {
            $shortcode .= ' [blog-posts limit="10"][/blog-posts]';
        }

        return [
            'page'      => $page,
            'shortcode' => $shortcode,
        ];
    }
}
