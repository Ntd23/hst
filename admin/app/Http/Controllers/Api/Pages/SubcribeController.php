<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use Botble\Newsletter\Http\Controllers\PublicController as NewsletterPublicController;
use Botble\Newsletter\Http\Requests\NewsletterRequest;

class SubcribeController extends Controller
{
    /**
     * POST /api/pages/subscribe/widget/form
     *
     * Submit newsletter widget form by reusing Botble Newsletter public flow.
     */
    public function submitWidgetFormSubscribe(
        NewsletterRequest $request,
        NewsletterPublicController $publicController
    ) {
        $response = $publicController->postSubscribe($request);

        return method_exists($response, 'toApiResponse')
            ? $response->toApiResponse()
            : $response;
    }
}
