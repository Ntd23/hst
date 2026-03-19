<?php

namespace Botble\Portfolio\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Portfolio\Http\Requests\DemoWebsiteRequest;
use Botble\Portfolio\Models\DemoWebsite;

class DemoWebsiteForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(DemoWebsite::class)
            ->setValidatorClass(DemoWebsiteRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('url_client', TextField::class, [
                'label' => trans('plugins/portfolio::portfolio.demo-websites.url_client'),
            ])
            ->add('url_admin', TextField::class, [
                'label' => trans('plugins/portfolio::portfolio.demo-websites.url_admin'),
            ])
            ->add('content', EditorField::class, ContentFieldOption::make()->allowedShortcodes())
            
            ->add('seo_title', TextField::class,[
                'label' => trans('plugins/portfolio::portfolio.demo-websites.seo_title'),
            ])

            ->add('seo_description', EditorField::class, [
                'label' => trans('plugins/portfolio::portfolio.demo-websites.seo_description'),
            ])

            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            
            ->add('img_full', 'mediaImage', [
                'label' => trans('plugins/portfolio::portfolio.demo-websites.img_full'),
            ])
            ->add('img_feautrer', 'mediaImage', [
                'label' => trans('plugins/portfolio::portfolio.demo-websites.img_feautrer'),
            ])
            ->setBreakFieldPoint('status');
    }
}
