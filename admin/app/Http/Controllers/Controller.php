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
        return $request->input('locale', app()->getLocale());
    }
}
