<!doctype html>
<html {!! Theme::htmlAttributes() !!}>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! Theme::partial('css-variable-declare') !!}

    {!! Theme::header() !!}
    <style>
        .testimonial__item-two {
            color: #fff !important;
        }

        .editor {
            min-height: 500px !important;
            max-width: ;
        }

        @media (max-width: 767.98px) {
            .text-25-years {
                left: -170px !important;
            }

            .choose__box .icon {
                margin: auto !important;
                margin-bottom: 15px !important;
            }

            .choose__box .content .title {
                text-align: center !important;
            }

            .breadcrumb__area {
                padding: 20px 0 20px;
            }

            .breadcrumb__content .title {
                font-size: 20px !important;
            }

            .breadcrumb .breadcrumb-item {
                overflow: hidden !important;
                white-space: nowrap !important;
                text-overflow: ellipsis !important;
            }
        }
    </style>
</head>

<body {!! Theme::bodyAttributes() !!} >

<a href="#" title="{{ __('Back to top') }}" class="scroll__top scroll-to-target" data-target="html">
    <x-core::icon name="ti ti-chevron-up"/>
</a>

{!! apply_filters(THEME_FRONT_BODY, null) !!}

{!! Theme::partial('header') !!}

<main class="fix">
    @yield('content')
</main>

<script>
    'use strict';

    window.siteConfig = {};
</script>

{!! Theme::partial('footer') !!}

{!! Theme::footer() !!}
</body>
</html>

