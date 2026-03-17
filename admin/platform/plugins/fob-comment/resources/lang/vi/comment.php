<?php

return [
    'common' => [
        'name' => 'Tên',
        'email' => 'Email',
        'website' => 'Website',
        'comment' => 'Bình luận',
    ],

    'title' => 'Bình luận',
    'author' => 'Tác giả',
    'responsed_to' => 'Trả lời tới',
    'permalink' => 'Liên kết',
    'url' => 'URL',
    'submitted_on' => 'Gửi vào lúc',
    'edit_comment' => 'Chỉnh sửa bình luận',
    'reply' => 'Trả lời',
    'in_reply_to' => 'Trả lời :name',

    'reply_modal' => [
        'title' => 'Trả lời :comment',
        'cancel' => 'Hủy',
    ],

    'allow_comments' => 'Cho phép bình luận',

    'front' => [
        'admin_badge' => 'Quản trị',

        'list' => [
            'title' => ':count bình luận|:count bình luận',
            'reply' => 'Trả lời',
            'reply_to' => 'Trả lời :name',
            'cancel_reply' => 'Hủy trả lời',
            'waiting_for_approval_message' => 'Bình luận của bạn đang chờ duyệt. Đây là bản xem trước, bình luận sẽ hiển thị sau khi được phê duyệt.',
        ],

        'form' => [
            'title' => 'Để lại bình luận',
            'description' => 'Email của bạn sẽ không được hiển thị công khai. Các trường bắt buộc được đánh dấu *',
            'cookie_consent' => 'Lưu tên, email và website của tôi trên trình duyệt này cho lần bình luận tiếp theo.',
            'submit' => 'Gửi bình luận',
        ],

        'comment_success_message' => 'Bình luận của bạn đã được gửi thành công.',
    ],

    'enums' => [
        'statuses' => [
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'spam' => 'Spam',
            'trash' => 'Thùng rác',
        ],
    ],

    'settings' => [
        'title' => 'FOB Comment',
        'description' => 'Cấu hình cho FOB Comment',

        'form' => [
            'enable_recaptcha' => 'Bật reCAPTCHA',
            'enable_recaptcha_help' => 'Bạn cần bật reCAPTCHA tại :url để sử dụng tính năng này.',
            'captcha_setting_label' => 'Cài đặt Captcha',
            'comment_moderation' => 'Bình luận cần được duyệt thủ công',
            'comment_moderation_help' => 'Tất cả bình luận phải được admin duyệt trước khi hiển thị ngoài frontend.',
            'show_comment_cookie_consent' => 'Hiển thị checkbox lưu thông tin người bình luận',
            'auto_fill_comment_form' => 'Tự động điền thông tin cho người dùng đã đăng nhập',
            'auto_fill_comment_form_help' => 'Form bình luận sẽ tự động điền tên, email,... nếu người dùng đã đăng nhập.',
            'comment_order' => 'Sắp xếp bình luận theo',
            'comment_order_help' => 'Chọn thứ tự hiển thị bình luận.',
            'comment_order_choices' => [
                'asc' => 'Cũ nhất',
                'desc' => 'Mới nhất',
            ],
            'display_admin_badge' => 'Hiển thị badge admin',
            'show_admin_role_name_for_admin_badge' => 'Hiển thị tên role admin trong badge',
            'show_admin_role_name_for_admin_badge_helper' => 'Nếu bật, badge admin sẽ hiển thị tên role thay vì "Quản trị". Nếu role trống sẽ dùng mặc định. Nếu có nhiều role, sẽ lấy role đầu tiên.',
        ],
    ],
];