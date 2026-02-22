<div class="modal fade modal-blur single-action-confirm-modal show" tabindex="-1" role="dialog"
data-select2-dropdown-parent="true" aria-modal="true" style="display: block;">
<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-status bg-danger"></div>

        <div class="modal-body text-center py-4">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>

            <div class="mb-2">
                <svg class="icon icon-lg text-danger svg-icon-ti-ti-alert-triangle"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 9v4"></path>
                    <path
                        d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
                    </path>
                    <path d="M12 16h.01"></path>
                </svg>
            </div>

            <h4 class="card-title">{{ trans('core/base::layouts.confirm_delete') }}</h4>

            <p>{{ trans('core/base::layouts.delete_confirm_message') }}</p>
        </div>

        <div class="modal-footer">
            <div class="w-100">
                <div class="row">
                    <div class="col">
                        <button type="button"
                            class="w-100 btn btn-danger confirm-trigger-single-action-button">{{ trans('core/base::layouts.delete') }}</button>
                    </div>
                    <div class="col">
                        <button type="button" class="w-100 btn btn-secondary" data-bs-dismiss="modal">{{ trans('core/base::layouts.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>