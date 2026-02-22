{{-- @dd($websites) --}}
<div class="container">
    <style>
        .image-scroll-wrapper {
            height: 300px;
            /* chiều cao khung hiển thị ảnh */
            overflow: hidden;
            position: relative;
        }

        .scroll-on-hover {
            display: block;
            transition: transform 8s linear;
            will-change: transform;
        }

        .image-scroll-wrapper:hover .scroll-on-hover {
            transform: translateY(-50%);
            /* tuỳ chỉnh -50% hoặc -100% tuỳ chiều dài ảnh */
        }

        .web {
            padding: 20px !important;
        }
    </style>

    <div class="section-title text-center mb-40 tg-heading-subheading animation-style3 ">
        <span class="sub-title">{{ trans('core/base::layouts.featured_softwares') }}</span>

        <h2 class="title tg-element-title" style="perspective: 400px;">
            <div class="split-line" style="display: block; text-align: center; position: relative;">
                <div style="position:relative;display:inline-block;">
                    <div style="position: relative; display: inline-block; opacity: 1; transform: translate(0px, 0px);">
                        {{ trans('core/base::layouts.featured_software') }}
                    </div>
                </div>
        </h2>
    </div>
    <div class="row justify-content-center">
        {{-- @dd($lang) --}}
        @foreach ($websites as $website)
            @php
                $currentLocale = app()->getLocale();
                $defaultLocale = Botble\Language\Models\Language::where('lang_is_default', 1)->first();

                $routeName = $currentLocale !== $defaultLocale ? 'websites.show.locale' : 'websites.show';

                $routeParams =
                    $routeName === 'websites.show.locale'
                        ? ['locale' => $currentLocale, 'slug' => $website->slug]
                        : ['slug' => $website->slug];
            @endphp
            <div class="col-xl-4 col-lg-6 col-md-10">
                <div class="blog-post-item shine-animate-item web">
                    <div class="image-scroll-wrapper">
                        <img src="{{ url('/') }}/storage/{{ $website->img_full }}" data-bb-lazy="true"
                            loading="lazy" data-src="{{ url('/') }}/storage/news/1411-1-480x480.jpg"
                            alt="{{ $website->translationByLang($lang)->name ?? $website->name }}"
                            data-ll-status="loaded" class="entered loaded scroll-on-hover">
                    </div>
                    <div class="blog-post-content pt-3">
                        <h2 class="title">
                            <a class="truncate-2-custom"
                                title="{{ $website->translationByLang($lang)->name ?? $website->name }}"
                                href="{{ route($routeName, $routeParams) }}">
                                {{ $website->translationByLang($lang)->name ?? $website->name }}
                            </a>
                        </h2>
                        <div class="blog-avatar">
                            <div class="avatar-thumb">
                                <img src="{{ url('/') }}/storage/users/3db8793f-a495-4015-b36c-34c28b64096c.png"
                                    data-bb-lazy="true" loading="lazy"
                                    data-src="{{ url('/') }}/storage/users/3db8793f-a495-4015-b36c-34c28b64096c.png"
                                    alt="thumb" data-ll-status="loaded" class="entered loaded">
                            </div>
                            <div class="avatar-content">
                                {{ trans('core/base::layouts.by') }} <strong>HisoTech</strong>
                            </div>
                        </div>

                        <div class="blog-post-meta">
                            <ul class="list-wrap">
                                <li>
                                    <a href="{{ route($routeName, $routeParams) }}" class="btn">{{ trans('core/base::layouts.detail') }}</a>
                                </li>
                                <li><i class="fas fa-calendar-alt"></i>{{ $website->created_at->format('d-m-Y') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>
