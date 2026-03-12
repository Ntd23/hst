<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use Botble\Contact\Http\Controllers\PublicController as ContactPublicController;
use Botble\Contact\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    /**
     * POST /api/pages/contact/section/form
     *
     * Submit contact form (reuse PublicController::postSendContact).
     */
    public function submitSectionFormContact(ContactRequest $request, ContactPublicController $publicController)
    {
        $response = $publicController->postSendContact($request);

        return method_exists($response, 'toApiResponse')
            ? $response->toApiResponse()
            : $response;
    }
}
