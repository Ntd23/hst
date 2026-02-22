@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <form method="POST" action="{{ route('portfolio.websites.store') }}" accept-charset="UTF-8" enctype="multipart/form-data"
        id="botble-portfolio-forms-project-form" class="js-base-form dirty-check" novalidate="novalidate">
        @csrf
        @method('POST')
        {{-- <div role="alert" class="alert alert-info">
    <div class="d-flex">
        <div>
            <svg class="icon alert-icon svg-icon-ti-ti-info-circle" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                <path d="M12 9h.01"></path>
                <path d="M11 12h1v4h1"></path>
            </svg>
        </div>
        <div class="w-100">
            Bạn đang chỉnh sửa phiên bản tiếng "<strong class="current_language_text">Tiếng Việt</strong>"
        </div>
    </div>
</div> --}}
        <div class="row">
            <div class="gap-3 col-md-9">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="mb-3 position-relative">
                                <label for="name" class="form-label required">{{ trans('core/base::layouts.name') }}</label>
                                <input class="form-control" data-counter="250" placeholder="{{ trans('core/base::layouts.enter_name') }}" required="required"
                                    name="name" type="text" id="name" aria-required="true"
                                    value="{{ old('name') }}">
                            </div>
                            <div class="mb-3 ">
                                <div class="slug-field-wrapper" data-field-name="name">
                                    <div class="mb-3 position-relative">
                                        <label class="form-label required" for="slug">
                                            {{ trans('core/base::layouts.static_path') }}
                                        </label>
                                        <div class="input-group input-group-flat">
                                            <span class="input-group-text">
                                                {{ url('/') }}/website-demos/
                                            </span>
                                            <input class="form-control ps-0" type="text" name="slug" id="slug"
                                                required="required" aria-required="true" value="{{ old('slug') }}">
                                            <span class="input-group-text slug-actions">
                                                <a href="#" class="link-secondary d-none" data-bs-toggle="tooltip"
                                                    aria-label="Tạo URL" data-bs-original-title="Tạo URL"
                                                    data-bb-toggle="generate-slug">
                                                    <svg class="icon  svg-icon-ti-ti-wand"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M6 21l15 -15l-3 -3l-15 15l3 3"></path>
                                                        <path d="M15 6l3 3"></path>
                                                        <path
                                                            d="M9 3a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2">
                                                        </path>
                                                        <path
                                                            d="M19 13a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2">
                                                        </path>
                                                    </svg> </a>
                                            </span>
                                        </div>
                                    </div>
                                    <small class="form-hint mt-n2 text-truncate">Xem trước: <a href="#"
                                            target="_blank"></a></small>
                                </div>
                            </div>

                            <script>
                                let seoTitleChangedManually = false;

                                function slugify(str) {
                                    return str
                                        .toLowerCase()
                                        .replace(/đ/g, 'd')
                                        .replace(/Đ/g, 'd')
                                        .normalize('NFD')
                                        .replace(/[\u0300-\u036f]/g, '')
                                        .replace(/[^a-z0-9\s-]/g, '')
                                        .trim()
                                        .replace(/\s+/g, '-')
                                        .replace(/-+/g, '-');
                                }

                                function updateSeoPreview(nameValue, slugValue, seoTitleValue = '') {
                                    const previewTitle = document.getElementById('preview-seo-title');
                                    const linkSeo = document.getElementById('link-seo');

                                    previewTitle.textContent = seoTitleValue || nameValue || '';

                                    const baseUrl = "{{ url('/') }}/website-demos/";
                                    linkSeo.textContent = slugValue ? baseUrl + slugValue : '';
                                }

                                document.getElementById('name').addEventListener('input', function() {
                                    const nameValue = this.value;
                                    const slug = slugify(nameValue);
                                    document.getElementById('slug').value = slug;

                                    // Nếu người dùng chưa nhập seo_title thủ công thì sync theo name
                                    if (!seoTitleChangedManually) {
                                        document.getElementById('seo_title').value = nameValue;
                                    }

                                    const seoTitle = document.getElementById('seo_title').value;
                                    updateSeoPreview(nameValue, slug, seoTitle);
                                });

                                document.getElementById('seo_title').addEventListener('input', function() {
                                    seoTitleChangedManually = true;
                                    const seoTitle = this.value;
                                    const nameValue = document.getElementById('name').value;
                                    const slugValue = document.getElementById('slug').value;

                                    updateSeoPreview(nameValue, slugValue, seoTitle);
                                });

                                document.getElementById('slug').addEventListener('input', function() {
                                    const slugValue = this.value;
                                    const nameValue = document.getElementById('name').value;
                                    const seoTitleValue = document.getElementById('seo_title').value;

                                    updateSeoPreview(nameValue, slugValue, seoTitleValue);
                                });

                                document.addEventListener('DOMContentLoaded', function() {
                                    const name = document.getElementById('name').value;
                                    const slug = document.getElementById('slug').value;
                                    const seoTitle = document.getElementById('seo_title').value;

                                    updateSeoPreview(name, slug, seoTitle);
                                });
                            </script>


                            <div class="mb-3 position-relative">
                                <div class="row">
                                    <div class="col">
                                        <label for="url_client" class="form-label required">{{ trans('core/base::layouts.web_link') }}</label>
                                        <input class="form-control" data-counter="250" name="url_client" type="text"
                                            id="url_client" value="{{ old('url_client') }}">
                                    </div>
                                    <div class="col">
                                        <label for="url_admin" class="form-label">{{ trans('core/base::layouts.admin_link') }}</label>
                                        <input class="form-control" data-counter="250" name="url_admin" type="text"
                                            id="url_admin" value="{{ old('url_admin') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="mb-3 position-relative">
                                <label for="web_id" class="form-label">{{ trans('core/base::layouts.main_web') }}</label>
                                <select name="web_id" id="web_id" class="form-control">
                                    <option value="">{{ trans('core/base::layouts.select_main_web') }}</option>
                                    @foreach ($websites as $website_item)
                                        <option {{ old('web_id') == $website_item->id ? 'selected' : '' }}
                                            value="{{ $website_item->id }}">{{ $website_item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 position-relative">
                                <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
                                <label for="description" class="form-label required">{{ trans('core/base::layouts.description') }}</label>
                                <textarea name="description" id="editor" class="form-control editor seo-desc">{{ old('description') }}</textarea>


                            </div>

                        </div>
                    </div>
                </div>

                <style>
                    .page-title-seo {
                        color: #1a0dab;
                        font-size: 18px;
                        font-weight: 400;
                        margin-bottom: 2px;
                    }

                    .page-url-seo p {
                        word-wrap: break-word;
                        color: #006621;
                        display: block;
                        font-size: 13px;
                        line-height: 16px;
                        margin-bottom: 2px;
                    }
                </style>
                <div id="advanced-sortables" class="meta-box-sortables">
                    <div class="card meta-boxes mb-3" id="seo_wrap">
                        <div class="card-header">
                            <h4 class="card-title">
                                Tối ưu hoá bộ máy tìm kiếm (SEO)
                            </h4>
                        </div>

                        <div class="card-body">
                            <div class="seo-preview" v-pre>
                                <p class="default-seo-description hidden">
                                    Thiết lập các thẻ mô tả giúp người dùng dễ dàng tìm
                                    thấy trên công cụ tìm kiếm như Google.
                                </p>
                                <div class="existed-seo-meta">
                                    <h4 class="page-title-seo text-truncate" id="preview-seo-title">
                                    </h4>

                                    <div class="page-url-seo">
                                        <p id="link-seo"></p>
                                    </div>

                                    <div>
                                        <span id="date-seo"
                                            style="color: #70757a; display: none;">{{ now()->format('d-m-Y') }}
                                            - </span>
                                        <span id="preview-seo-description" class="page-description-seo">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="seo-edit-section" v-pre="">
                                <hr class="my-4">

                                <div class="mb-3 position-relative">

                                    <label for="seo_title" class="form-label">Tiêu đề trang</label>

                                    <input class="form-control" data-counter="250" name="seo_title" type="text"
                                        id="seo_title" value="{{ old('seo_title') }}">
                                </div>
                                <script>
                                    function updateSEOPreview() {
                                        const nameVal = document.getElementById('name').value.trim();
                                        const seoTitleVal = document.getElementById('seo_title').value.trim();
                                        const previewEl = document.getElementById('preview-seo-title');

                                        // Ưu tiên seo_title nếu có, nếu không thì dùng name
                                        previewEl.textContent = seoTitleVal || nameVal || '';
                                    }

                                    // Lắng nghe thay đổi trên cả name và seo_title
                                    document.getElementById('name').addEventListener('input', updateSEOPreview);
                                    document.getElementById('seo_title').addEventListener('input', updateSEOPreview);

                                    // Khi trang load lần đầu
                                    document.addEventListener('DOMContentLoaded', updateSEOPreview);
                                </script>


                                <div class="mb-3 position-relative">

                                    <label for="seo_description" class="form-label">Mô tả trang</label>

                                    <textarea class="form-control" data-counter="160" rows="3" placeholder="Mô tả trang" data-allow-over-limit=""
                                        name="seo_description" cols="50" id="seo_description">{{ old('seo_description') }}</textarea>
                                </div>

                                <script>
                                    ClassicEditor
                                        .create(document.querySelector('#editor'), {
                                            language: 'vi'
                                        })
                                        .then(editor => {
                                            editor.ui.view.editable.element.style.minHeight = '500px'; // Chiều cao editor

                                            const preview = document.getElementById('preview-seo-description');
                                            const dateSeo = document.getElementById('date-seo');
                                            const seoDescTextarea = document.getElementById('seo_description');

                                            function updatePreview() {
                                                const content = editor.getData();

                                                // Tạo thẻ tạm để loại bỏ HTML
                                                const tempDiv = document.createElement('div');
                                                tempDiv.innerHTML = content;
                                                const text = tempDiv.textContent || tempDiv.innerText || '';

                                                // Lấy 2 dòng đầu
                                                const lines = text.split('\n').filter(line => line.trim() !== '');
                                                const firstTwoLines = lines.slice(0, 2).join(' ');

                                                const maxLength = 160;
                                                let trimmed = firstTwoLines;

                                                // Nếu dài hơn maxLength thì cắt và thêm "..."
                                                if (firstTwoLines.length > maxLength) {
                                                    trimmed = firstTwoLines.substring(0, maxLength - 3).trim() + '...';
                                                }

                                                // Gán vào textarea và preview
                                                seoDescTextarea.value = trimmed;
                                                preview.textContent = trimmed;


                                                // Hiển thị hoặc ẩn ngày
                                                if (firstTwoLines.trim() !== '') {
                                                    dateSeo.style.display = 'inline';
                                                } else {
                                                    dateSeo.style.display = 'none';
                                                }
                                            }

                                            // Gọi lần đầu khi khởi tạo
                                            updatePreview();

                                            // Gọi lại mỗi khi nội dung thay đổi
                                            editor.model.document.on('change:data', updatePreview);
                                        })
                                        .catch(error => {
                                            console.error(error);
                                        });
                                </script>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const seoDescTextarea = document.getElementById('seo_description');
                                        const preview = document.getElementById('preview-seo-description');
                                        const dateSeo = document.getElementById('date-seo');
                                        const maxLength = 160;

                                        function updatePreviewFromTextarea() {
                                            let value = seoDescTextarea.value.trim();

                                            // Nếu vượt quá maxLength thì cắt và thêm "..."
                                            if (value.length > maxLength) {
                                                value = value.substring(0, maxLength - 3).trim() + '...';
                                            }

                                            preview.textContent = value;

                                            if (value !== '') {
                                                dateSeo.style.display = 'inline';
                                            } else {
                                                dateSeo.style.display = 'none';
                                            }
                                        }

                                        // Gọi ban đầu nếu textarea đã có sẵn giá trị
                                        updatePreviewFromTextarea();

                                        // Lắng nghe khi người dùng nhập
                                        seoDescTextarea.addEventListener('input', updatePreviewFromTextarea);
                                    });
                                </script>


                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-3 gap-3 d-flex flex-column-reverse flex-md-column mb-md-0 mb-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ trans('core/base::layouts.publish') }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="btn-list">
                            <button class="btn btn-primary" type="submit" value="apply" name="submitter">{{ trans('core/base::layouts.save_and_edit') }}</button>
                            <button class="btn" type="submit" name="submitter" value="save">{{ trans('core/base::layouts.save') }}</button>
                        </div>
                    </div>
                </div>

                <div data-bb-waypoint="" data-bb-target="#form-actions"></div>

                <header class="top-0 w-100 position-fixed end-0 z-1000" id="form-actions" style="display: none;">
                    <div class="navbar">
                        <div class="container-xl">
                            <div class="row g-2 align-items-center w-100">
                                <div class="col">
                                    <div class="page-pretitle">
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb">
                                            </ol>
                                        </nav>
                                    </div>
                                </div>
                                <div class="col-auto ms-auto d-print-none">
                                    <div class="btn-list">
                                        <button class="btn btn-primary" type="submit" value="apply" name="submitter">{{ trans('core/base::layouts.save_and_edit') }}</button>
                                        <button class="btn" type="submit" name="submitter" value="save">{{ trans('core/base::layouts.save') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                {{-- <div class="card meta-boxes mb-3" id="language_advanced_wrap">
            <div class="card-header">
                <h4 class="card-title">
                    Ngôn ngữ
                </h4>
            </div>
            <div class="card-body">
                <input name="language" type="hidden" value="vi">
                <div id="list-others-language">
                    <a class="gap-2 d-flex align-items-center text-decoration-none"
                        href="https://hisotechgroup.com/admin/portfolio/projects/edit/9?ref_lang=en_US"
                        target="_blank">
                        <img src="https://hisotechgroup.com/vendor/core/core/base/img/flags/us.svg"
                            title="English" class="flag" style="height: 16px" loading="lazy" alt="English flag">
                        <span>English <svg class="icon  svg-icon-ti-ti-external-link"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6"></path>
                                <path d="M11 13l9 -9"></path>
                                <path d="M15 4h5v5"></path>
                        </svg></span>
                    </a>
                </div>
            </div>
        </div> --}}
                <div class="card meta-boxes">
                    <div class="card-header">
                        <h4 class="card-title">
                            <label for="status" class="form-label required">{{ trans('core/base::layouts.status') }}</label>
                        </h4>
                    </div>
                    <div class=" card-body">
                        <select class="form-control form-select" required="required" id="status" name="status"
                            aria-required="true">
                            <option value="1">{{ trans('core/base::layouts.active') }}</option>
                            <option value="0">{{ trans('core/base::layouts.inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="card meta-boxes">
                    <div class="card-header">
                        <h4 class="card-title">
                            <label for="img_feautrer" class="form-label required">{{ trans('core/base::layouts.image') }}</label>
                        </h4>
                    </div>
                    <div class=" card-body">
                        <div class="image-box image-box-image" action="select-image">
                            <input class="image-data d-none" name="img_feautrer" type="file" id="fileInput">
                            <div style="width: 8rem" class="preview-image-wrapper mb-1">
                                <div class="preview-image-inner">
                                    <a id="chooseImageBtn" class="image-box-actions" data-result="image"
                                        data-action="select-image" data-allow-thumb="1" href="">
                                        <img id="previewImg" class="preview-image default-image"
                                            data-default="{{ url('/') }}/vendor/core/core/base/images/placeholder.png"
                                            src="{{ url('/') }}/vendor/core/core/base/images/placeholder.png"
                                            alt="Ảnh xem trước">
                                        <span class="image-picker-backdrop"></span>
                                    </a>
                                </div>
                            </div>
                            <a href="" id="chooseImageBtnText">{{ trans('core/base::layouts.choose_image') }}</a>
                            <script>
                                document.getElementById('chooseImageBtn').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    document.getElementById('fileInput').click();
                                });
                                document.getElementById('chooseImageBtnText').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    document.getElementById('fileInput').click();
                                });
                                document.getElementById('fileInput').addEventListener('change', function(e) {
                                    const file = e.target.files[0];
                                    const previewImg = document.getElementById('previewImg');

                                    if (file && file.type.startsWith('image/')) {
                                        const reader = new FileReader();

                                        reader.onload = function(e) {
                                            previewImg.src = e.target.result;
                                        }

                                        reader.readAsDataURL(file);
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="card meta-boxes">
                    <div class="card-header">
                        <h4 class="card-title">
                            <label for="img_full" class="form-label required">{{ trans('core/base::layouts.full_web_image') }}</label>
                        </h4>
                    </div>
                    <div class=" card-body">
                        <div class="image-box image-box-image" action="select-image">
                            <input class="image-data d-none" name="img_full" type="file" id="fileFullInput">
                            <div style="width: 8rem" class="preview-image-wrapper mb-1">
                                <div class="preview-image-inner">
                                    <a id="chooseFullImageBtn" class="image-box-actions" data-result="image"
                                        data-action="select-image" data-allow-thumb="1" href="">
                                        <img id="previewImgFull" class="preview-image default-image"
                                            data-default="{{ url('/') }}/vendor/core/core/base/images/placeholder.png"
                                            src="{{ url('/') }}/vendor/core/core/base/images/placeholder.png"
                                            alt="Ảnh xem trước">
                                        <span class="image-picker-backdrop"></span>
                                    </a>

                                </div>
                            </div>
                            <a href="" id="chooseFullImageBtnText">{{ trans('core/base::layouts.choose_image') }}</a>
                            <script>
                                document.getElementById('chooseFullImageBtn').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    document.getElementById('fileFullInput').click();
                                });
                                document.getElementById('chooseFullImageBtnText').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    document.getElementById('fileFullInput').click();
                                });
                                document.getElementById('fileFullInput').addEventListener('change', function(e) {
                                    const file = e.target.files[0];
                                    const previewImgFull = document.getElementById('previewImgFull');

                                    if (file && file.type.startsWith('image/')) {
                                        const reader = new FileReader();

                                        reader.onload = function(e) {
                                            previewImgFull.src = e.target.result;
                                        }

                                        reader.readAsDataURL(file);
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
