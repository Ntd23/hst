@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
<div class="table-wrapper">
    <div class="card mb-3 table-configuration-wrap" style="display: none;">
        <div class="card-body">
            <button class="btn btn-icon  btn-sm btn-show-table-options rounded-pill" type="button">
                <svg class="icon icon-sm icon-left svg-icon-ti-ti-x" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M18 6l-12 12"></path>
                    <path d="M6 6l12 12"></path>
                </svg>

            </button>

            <div class="wrapper-filter">
                <p>{{ trans('core/base::layouts.filter_data') }}</p>

                <input type="hidden" class="filter-data-url" value="{{ url('/') }}/admin/tables/filters">

                <div class="sample-filter-item-wrap hidden">
                    <div class="row filter-item form-filter">
                        <div class="col-auto w-50 w-sm-auto">
                            <div class="mb-3 position-relative">
                                <select class="form-select filter-column-key" name="filter_columns[]"
                                    id="filter_columns[]">
                                    <option value="name">{{ trans('core/base::layouts.name') }}</option>
                                    <option value="created_at">{{ trans('core/base::layouts.created_at') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-auto w-50 w-sm-auto">
                            <div class="mb-3 position-relative">
                                <select class="form-select filter-operator filter-column-operator"
                                    name="filter_operators[]" id="filter_operators[]">
                                    <option value="like">{{ trans('core/base::layouts.includes') }}</option>
                                    <option value="=">{{ trans('core/base::layouts.equals') }}</option>
                                    <option value=">">{{ trans('core/base::layouts.greater_than') }}</option>
                                    <option value="<">{{ trans('core/base::layouts.less_than') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-auto w-100 w-sm-25">
                            <span class="filter-column-value-wrap">
                                <input class="form-control filter-column-value" type="text" placeholder="{{ trans('core/base::layouts.value') }}"
                                    name="filter_values[]">
                            </span>
                        </div>

                        <div class="col">
                            <button class="btn btn-icon   btn-remove-filter-item mb-3 text-danger" type="button"
                                data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Xoá"
                                data-bs-original-title="Xoá">
                                <svg class="icon icon-left svg-icon-ti-ti-trash" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 7l16 0"></path>
                                    <path d="M10 11l0 6"></path>
                                    <path d="M14 11l0 6"></path>
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                </svg>

                            </button>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ url('/') }}/admin/portfolio/projects" accept-charset="UTF-8"
                    class="filter-form">
                    <input type="hidden" name="filter_table_id" class="filter-data-table-id"
                        value="botble-portfolio-tables-project-table">
                    <input type="hidden" name="class" class="filter-data-class"
                        value="Botble\Portfolio\Tables\ProjectTable">
                    <div class="filter_list inline-block filter-items-wrap">
                        <div class="row filter-item form-filter filter-item-default">
                            <div class="col-auto w-50 w-sm-auto">
                                <div class="mb-3 position-relative">
                                    <select class="form-select filter-column-key" name="filter_columns[]"
                                        id="filter_columns[]">
                                        <option value="" selected="">Chọn trường</option>
                                        <option value="name">{{ trans('core/base::layouts.name') }}</option>
                                        <option value="created_at">{{ trans('core/base::layouts.created_at') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-auto w-50 w-sm-auto">
                                <div class="mb-3 position-relative">
                                    <select class="form-select filter-operator filter-column-operator"
                                        name="filter_operators[]" id="filter_operators[]">
                                        <option value="like">{{ trans('core/base::layouts.includes') }}</option>
                                        <option value="=" selected="">{{ trans('core/base::layouts.equals') }}</option>
                                        <option value=">">{{ trans('core/base::layouts.greater_than') }}</option>
                                        <option value="<">{{ trans('core/base::layouts.less_than') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto w-100 w-sm-25">
                                <div class="filter-column-value-wrap mb-3">
                                    <input class="form-control filter-column-value" type="text"
                                        placeholder="{{ trans('core/base::layouts.value') }}" name="filter_values[]" value="">
                                </div>
                            </div>
                            <div class="col">
                            </div>
                        </div>
                    </div>
                    <div class="btn-list">
                        <button class="btn   add-more-filter" type="button">
                            {{ trans('core/base::layouts.add_more_filter') }}
                        </button>
                        <button class="btn btn-primary  btn-apply" type="submit">
                            {{ trans('core/base::layouts.apply') }}
                        </button>
                        <a class="btn btn-icon" style="display: none;" type="button"
                            href="{{ url('/') }}/admin/portfolio/projects"
                            data-bb-toggle="datatable-reset-filter">
                            <svg class="icon icon-left svg-icon-ti-ti-refresh" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"></path>
                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
                            </svg>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card has-actions has-filter">
        <div class="card-header">
            <div class="w-100 justify-content-between d-flex flex-wrap align-items-center gap-1">
                <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-1">
                    {{-- <button class="btn   btn-show-table-options" type="button">
                        Lọc dữ liệu
                    </button> --}}

                    <div class="table-search-input">
                        <label>
                            <input type="search" class="form-control input-sm" placeholder="{{ trans('core/base::layouts.search') }}..."
                                style="min-width: 120px">
                        </label>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <a href="{{route('portfolio.websites.create')}}" class="btn action-item btn-primary">
                        <span><svg
                                class="icon  svg-icon-ti-ti-plus" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>{{ trans('core/base::layouts.create_new') }}</span>
                    </a>

                    <button class="btn" type="button" data-bb-toggle="dt-buttons" data-bb-target=".buttons-reload"
                        tabindex="0" aria-controls="botble-portfolio-tables-project-table">
                        <svg class="icon icon-left svg-icon-ti-ti-refresh" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"></path>
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
                        </svg>
                        {{ trans('core/base::layouts.reload') }}

                    </button>
                </div>
            </div>
        </div>

        <div class="card-table">
            <div class="table-responsive table-has-actions table-has-filter">
                <div id="botble-portfolio-tables-project-table_wrapper"
                    class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <table
                        class="table card-table table-vcenter table-striped table-hover dataTable no-footer dtr-inline"
                        id="botble-portfolio-tables-project-table"
                        aria-describedby="botble-portfolio-tables-project-table_info">
                        <thead>
                            <tr>
                                {{-- <th class="w-1 text-start no-column-visibility sorting_disabled" rowspan="1"
                                    colspan="1"><input class="form-check-input m-0 align-middle table-check-all"
                                        data-set=".dataTable .checkboxes" name="" type="checkbox"></th> --}}
                                <th title="ID" width="20"
                                    class="text-center no-column-visibility column-key-0 sorting" tabindex="0"
                                    aria-controls="botble-portfolio-tables-project-table" rowspan="1" colspan="1"
                                    style="width: 20px;">{{ trans('core/base::layouts.id') }}</th>
                                <th title="Hình ảnh" width="50" class="column-key-1 sorting_disabled" rowspan="1"
                                    colspan="1" style="width: 50px;">{{ trans('core/base::layouts.image') }}</th>
                                <th title="Tên" class="text-start column-key-2 sorting" tabindex="0"
                                    aria-controls="botble-portfolio-tables-project-table" rowspan="1" colspan="1">
                                    {{ trans('core/base::layouts.name') }}</th>
                                <th title="Số web con" width="100" class="column-key-3 sorting" tabindex="0"
                                    aria-controls="botble-portfolio-tables-project-table" rowspan="1" colspan="1"
                                    style="width: 100px;">{{ trans('core/base::layouts.child_websites') }}</th>
                                <th title="Ngày tạo" width="100" class="column-key-4 sorting" tabindex="0"
                                    aria-controls="botble-portfolio-tables-project-table" rowspan="1" colspan="1"
                                    style="width: 100px;">{{ trans('core/base::layouts.created_at') }}</th>
                                <th title="Trạng thái" width="100" class="text-center column-key-5 sorting"
                                    tabindex="0" aria-controls="botble-portfolio-tables-project-table" rowspan="1"
                                    colspan="1" style="width: 100px;">{{ trans('core/base::layouts.status') }}</th>
                                <th title="Ngôn ngữ"
                                    class="text-nowrap language-header text-center sorting_disabled" rowspan="1"
                                    colspan="1"><img
                                        src="{{ url('/') }}/vendor/core/core/base/img/flags/us.svg"
                                        title="English" class="flag" style="height: 16px" loading="lazy"
                                        alt="English flag"><img
                                        src="{{ url('/') }}/vendor/core/core/base/img/flags/vn.svg"
                                        title="Tiếng Việt" class="flag" style="height: 16px" loading="lazy"
                                        alt="Tiếng Việt flag"></th>
                                <th title="Tác vụ"
                                    class="text-center no-column-visibility text-nowrap sorting_disabled"
                                    rowspan="1" colspan="1">{{ trans('core/base::layouts.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($websiteDemos as $websiteDemo)
                            <tr class="odd">
                                {{-- <td class="w-1 text-start no-column-visibility dtr-control"><input
                                        class="form-check-input m-0 align-middle checkboxes" type="checkbox" name="id[]" value="9"></td> --}}
                                <td class="text-center no-column-visibility column-key-0 sorting_1">{{$websiteDemo->id}}</td>
                                <td class="   column-key-1"><img src="{{ url('/') }}/storage/{{$websiteDemo->img_feautrer}}"
                                        width="50" alt="Hình ảnh"></td>
                                <td class="  text-start  column-key-2"><a href="{{route('portfolio.websites.edit', $websiteDemo->id)}}"
                                        title="Lập kế hoạch chiến lược">{{$websiteDemo->name}}</a></td>
                                <td class="column-key-3">{{$websiteDemo->children_count }}</td>
                                <td class="  text-nowrap column-key-4">{{ $websiteDemo->created_at->format('d-m-Y') }}</td>
                                <td class="text-center column-key-5">
                                    <span class="badge 
                                        {{ $websiteDemo->status == 1 ? 'bg-success text-success-fg' : 'bg-danger text-danger-fg' }}">
                                        {{ $websiteDemo->status == 1 ? trans('core/base::layouts.active') : trans('core/base::layouts.inactive') }}
                                    </span>
                                </td>                                
                                <td class="  text-nowrap language-header text-center">
                                    <div class="text-center language-column">
                                        <a data-bs-toggle="tooltip" href=""
                                        {{-- <a data-bs-toggle="tooltip" href="{{ url('/') }}/admin/portfolio/projects/edit/9?ref_lang=en_US" --}}
                                            aria-label="Sửa bản ngôn ngữ khác của bản ghi này"
                                            data-bs-original-title="Sửa bản ngôn ngữ khác của bản ghi này">
                                            <svg class="icon  svg-icon-ti-ti-edit" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                <path d="M16 5l3 3"></path>
                                            </svg> </a>
                                        <a href="">
                                        {{-- <a href="{{ url('/') }}/admin/portfolio/projects/edit/9"> --}}
                                            <svg class="icon text-success svg-icon-ti-ti-check" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M5 12l5 5l10 -10"></path>
                                            </svg> </a>
                                    </div>
                                </td>
                                <td class="  text-center no-column-visibility text-nowrap">
                                    <div class="table-actions">
                                        <a href="{{route('portfolio.websites.edit', $websiteDemo->id)}}" class="btn btn-sm btn-icon btn-primary">
                                            <svg class="icon  svg-icon-ti-ti-edit" data-bs-toggle="tooltip" data-bs-title="Sửa"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                <path d="M16 5l3 3"></path>
                                            </svg>
                                            <span class="sr-only">Sửa</span>
                                        </a>
                        
                                        <a href="#" class="btn btn-danger btn-delete" data-url="{{ route('portfolio.websites.destroy', $websiteDemo->id) }}">
                                            <svg class="icon  svg-icon-ti-ti-trash" data-bs-toggle="tooltip" data-bs-title="Xóa bản ghi"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M4 7l16 0"></path>
                                                <path d="M10 11l0 6"></path>
                                                <path d="M14 11l0 6"></path>
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                            </svg>
                                            <span class="sr-only">Xóa bản ghi</span>
                                        </a>
                                        <form id="delete-form" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <script>
                                            document.querySelectorAll('.btn-delete').forEach(function (btn) {
                                                btn.addEventListener('click', function (e) {
                                                    e.preventDefault();
                                                    if (confirm('Bạn có chắc chắn muốn xóa?')) {
                                                        const form = document.getElementById('delete-form');
                                                        form.setAttribute('action', this.getAttribute('data-url'));
                                                        form.submit();
                                                    }
                                                });
                                            });
                                        </script>                                       
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    
                    <div class="card-footer d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" style="">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <div class="dataTables_length" id="botble-ecommerce-tables-product-table_length"><label><span
                                        class="dt-length-style"><select name="botble-ecommerce-tables-product-table_length"
                                            aria-controls="botble-ecommerce-tables-product-table" class="form-select form-select-sm">
                                            <option value="10">10</option>
                                            <option value="30">30</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="-1">{{ trans('core/base::layouts.all') }}</option>
                                        </select></span></label></div>
                            <div class="m-0 text-muted">
                                <div class="dataTables_info" id="botble-ecommerce-tables-product-table_info" role="status"
                                    aria-live="polite"><span class="dt-length-records">
                                        <svg class="icon  svg-icon-ti-ti-world" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                            <path d="M3.6 9h16.8"></path>
                                            <path d="M3.6 15h16.8"></path>
                                            <path d="M11.5 3a17 17 0 0 0 0 18"></path>
                                            <path d="M12.5 3a17 17 0 0 1 0 18"></path>
                                        </svg> <span class="d-none d-sm-inline">{{ trans('core/base::layouts.showing_from') }}</span>
                                        {{ $websiteDemos->firstItem() }}
                                        đến 
                                        {{$websiteDemos->lastItem()}}
                                         trong tổng số
                                        <span class="badge bg-secondary text-secondary-fg">
                
                                            {{$websiteDemos->total()}}
                                        </span>
                                        <span class="hidden-xs">{{ trans('core/base::layouts.records') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="dataTables_paginate paging_simple_numbers" id="botble-ecommerce-tables-product-table_paginate">
                                {{ $websiteDemos->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @include('admin.demo_website.delete') --}}
@endsection