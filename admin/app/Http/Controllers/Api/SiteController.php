<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class SiteController extends Controller
{
    public function headerTopBar()
    {
        /**
         * OPTION A (khuyến nghị): lấy từ Theme Options / settings của Botble
         * Tuỳ project của bố lưu key nào, bố đổi key cho đúng.
         *
         * Thường hay gặp:
         * - theme_option('hotline')
         * - theme_option('contact_phone')
         * - theme_option('contact_email')
         * - theme_option('address')
         */
        $phone   = function_exists('theme_option') ? (string) theme_option('hotline', theme_option('contact_phone')) : '';
        $email   = function_exists('theme_option') ? (string) theme_option('contact_email') : '';
        $address = function_exists('theme_option') ? (string) theme_option('address') : '';

        /**
         * OPTION B (fallback): nếu chưa có theme_option hoặc chưa set key, hardcode tạm để test
         */
        if ($phone === '' && $email === '' && $address === '') {
            $phone = '097 3735 679';
            $email = 'contact@hisotechgroup.com';
            $address = 'TP.HCM, Việt Nam';
        }

        return response()->json([
            'data' => [
                'phone'   => $phone ?: null,
                'email'   => $email ?: null,
                'address' => $address ?: null,
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
