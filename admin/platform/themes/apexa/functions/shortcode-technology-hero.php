<?php

use Botble\Base\Forms\FieldOptions\ColorFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\ColorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;

app()->booted(function (): void {
    Shortcode::register(
        'technology-hero',
        __('Technology Hero'),
        __('Technology Hero'),
        function (ShortcodeCompiler $shortcode): string {
            // The public site renders this shortcode as a Nuxt component via the API.
            return '';
        }
    );

    Shortcode::setPreviewImage(
        'technology-hero',
        Theme::asset()->url('images/ui-blocks/hero-banner.png')
    );

    Shortcode::setAdminConfig('technology-hero', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'badge',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Badge'))
                    ->defaultValue('Giải pháp công nghệ toàn diện')
                    ->toArray()
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->defaultValue('Kiến tạo giải pháp')
                    ->toArray()
            )
            ->add(
                'highlight_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Highlighted title'))
                    ->defaultValue('công nghệ đột phá')
                    ->toArray()
            )
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Description'))
                    ->defaultValue('Chúng tôi thiết kế và phát triển các nền tảng số giúp doanh nghiệp vận hành hiệu quả, tối ưu trải nghiệm và sẵn sàng tăng trưởng.')
                    ->toArray()
            )
            ->add(
                'primary_button',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Primary button'))
                    ->defaultValue('Khám phá giải pháp')
                    ->toArray()
            )
            ->add(
                'primary_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Primary URL'))
                    ->defaultValue('/giai-phap')
                    ->toArray()
            )
            ->add(
                'secondary_button',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Secondary button'))
                    ->defaultValue('Xem dự án')
                    ->toArray()
            )
            ->add(
                'secondary_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Secondary URL'))
                    ->defaultValue('/du-an')
                    ->toArray()
            )
            ->add(
                'capability_1',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Capability 1'))
                    ->defaultValue('Thiết kế UI/UX')
                    ->toArray()
            )
            ->add(
                'capability_2',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Capability 2'))
                    ->defaultValue('Phát triển website')
                    ->toArray()
            )
            ->add(
                'primary_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(__('Primary color'))
                    ->defaultValue('#0866FF')
                    ->toArray()
            )
            ->add(
                'glow_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(__('Glow color'))
                    ->defaultValue('#35D6FF')
                    ->toArray()
            )
            ->add(
                'enable_3d',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Enable 3D'))
                    ->choices([
                        'yes' => __('Yes'),
                        'no' => __('No'),
                    ])
                    ->defaultValue('yes')
                    ->toArray()
            )
            ->add(
                'poster',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Fallback poster (WebP recommended)'))
                    ->toArray()
            );
    });
});
