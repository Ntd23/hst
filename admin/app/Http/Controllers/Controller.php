<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Helper get locale từ request, fallback về app locale hiện tại
     * Dùng chung cho các API để tránh lặp code.
     */
    protected function getApiLocale(\Illuminate\Http\Request $request): string
    {
        $locale = $request->input('locale', app()->currentLocale());
        app()->setLocale($locale);

        // Cập nhật session ngôn ngữ cho Laravel và Botble
        \Botble\Language\Facades\Language::setLocale($locale);

        return $locale;
    }
}
