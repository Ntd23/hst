@php
    SeoHelper::setTitle($title = __($website->name));
    Theme::set('pageTitle', $title);
    Theme::fireEventGlobalAssets();
@endphp
<style>
    .hear-content-web{
        justify-content: center;
        align-items: center;

        img {
            object-fit: cover;
            width: 100%; 
            border-radius: 8px; 
        }

        /* .btn-demo{
            display: flex;
            gap: 30px;
        } */

        .btn-contact{
            background-color: #191D88;
            color: #fff;
        }
        .btn-contact:hover{
            background-color: #00B7FD;
        }
    }

</style>
<div class="hear-content-web row">
    <img src="{{ url('/') }}/storage/{{$website->img_feautrer}}" alt="" class="col-md-12 mt-4">
    <div class="col-md-12">
        <div class="btn-demo mt-3 d-flex flex-wrap gap-2 justify-content-start justify-content-center-sm">
            <a href="{{ $website->url_client }}" class="btn btn-success" target="_blank">{{ trans('core/base::layouts.web_demo') }}</a>
            @if ($website->url_admin)
                <a href="{{ $website->url_admin }}" class="btn border-btn" target="_blank">{{ trans('core/base::layouts.admin_demo') }}</a>
            @endif
            <a href="/contact-us" class="btn btn-contact">{{ trans('core/base::layouts.contact_now') }}</a>
        </div>        
    </div>
</div>
<div class="content-web mt-4">
    {!! $website->description !!}
</div>
