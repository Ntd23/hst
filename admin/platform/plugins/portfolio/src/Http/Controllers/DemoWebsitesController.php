<?php

namespace Botble\Portfolio\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Portfolio\Forms\DemoWebsiteForm;
use Botble\Portfolio\Http\Requests\DemoWebsiteRequest;
use Botble\Portfolio\Http\Resources\CustomFieldResource;
use Botble\Portfolio\Models\CustomField;
use Botble\Portfolio\Models\DemoWebsite;
use Botble\Portfolio\Tables\DemoWebsitesTable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DemoWebsitesController extends BaseController
{
    public function index(DemoWebsitesTable $table): View|JsonResponse
    {
        PageTitle::setTitle(trans('plugins/portfolio::portfolio.demo-websites.name'));

        return $table->renderTable();
    }

    public function create(): string
    {
        PageTitle::setTitle(trans('plugins/portfolio::portfolio.demo-websites.create'));

        return DemoWebsiteForm::create()->renderForm();
    }

    public function store(DemoWebsiteRequest $request): BaseHttpResponse
    {
        $form = DemoWebsiteForm::create();

        $form->saving(function (DemoWebsiteForm $form) use ($request): void {
            $model = $form->getModel();

            $model->fill([...$request->validated(),
                'author_type' => $request->user()::class,
                'author_id' => $request->user()->getKey(),
            ]);

            $model->save();

            if (! empty($options = $request->input('options', []))) {
                $model->saveOptions($options);
            }
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('portfolio.demo-websites.index'))
            ->setNextUrl(route('portfolio.demo-websites.edit', $form->getModel()->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(DemoWebsite $demoWebsite)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $demoWebsite->name]));

        return DemoWebsiteForm::createFromModel($demoWebsite)->renderForm();
    }

    public function update(DemoWebsite $demoWebsite, DemoWebsiteRequest $request): BaseHttpResponse
    {
        DemoWebsiteForm::createFromModel($demoWebsite)->setRequest($request)
            ->saving(function (DemoWebsiteForm $form) use ($request): void {
                $model = $form->getModel();

                $model->update($request->validated());

                // $model->saveOptions($request->input('options', []));
            });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('portfolio.demo-websites.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(DemoWebsite $demoWebsite): DeleteResourceAction
    {
        return DeleteResourceAction::make($demoWebsite);
    }

    // public function getInfo(Request $request): CustomFieldResource
    // {
    //     $customField = CustomField::query()
    //         ->with(['options'])
    //         ->findOrFail($request->input('id'));

    //     return new CustomFieldResource($customField);
    // }
}
