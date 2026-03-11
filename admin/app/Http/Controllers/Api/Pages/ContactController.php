<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\ShortcodeApiTrait;
use Botble\Contact\Http\Controllers\PublicController as ContactPublicController;
use Botble\Contact\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use ShortcodeApiTrait;

    /**
     * GET /api/pages/contact/meta?locale=vi
     * Trả về SEO meta cho trang liên hệ.
     */
    public function getMeta(Request $request)
    {
        return $this->metaResponseFromShortcode($request, 'contact-form', 'contact');
    }

    /**
     * GET /api/pages/contact/section/google-map?locale=vi
     *
     * Shortcode [google-map ...]address[/google-map] → data.
     */
    public function getSectionGoogleMap(Request $request)
    {
        return $this->sectionResponse($request, 'contact:google-map', function ($locale) {
            $data = $this->getShortcodeDataFromAnyPage('google-map', $locale);
            if (!$data) {
                return null;
            }

            $attrs = $data['attrs'] ?? [];
            $address = trim((string) ($data['content'] ?? ($attrs['address'] ?? '')));

            if ($address === '') {
                return null;
            }

            return [
                'locale' => $locale,
                'data' => [
                    'address' => $address,
                    'width' => $attrs['width'] ?? '100%',
                    'height' => $attrs['height'] ?? '500',
                ],
            ];
        });
    }

    /**
     * GET /api/pages/contact/section/form?locale=vi
     *
     * Shortcode [contact-form ...] → data + tabs.
     */
    public function getSectionFormContact(Request $request)
    {
        return $this->sectionResponse($request, 'contact:contact-form', function ($locale) {
            $attrs = $this->getShortcodeFromAnyPage('contact-form', $locale);
            if (!$attrs) {
                return null;
            }

            $tabs = $this->parseShortcodeTabs($attrs, ['title', 'description', 'icon', 'icon_image'], ['icon_image']);

            return [
                'locale' => $locale,
                'data' => [
                    'title' => $attrs['title'] ?? null,
                    'description' => $attrs['description'] ?? null,
                    'tabs' => $tabs,
                    'form' => [
                        'title' => $attrs['form_title'] ?? null,
                        'description' => $attrs['form_description'] ?? null,
                        'button_label' => $attrs['form_button_label'] ?? null,
                    ],
                ],
            ];
        });
    }

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
