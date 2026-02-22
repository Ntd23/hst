<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DemoWebsite;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;

class ClDemoWebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Theme::scope('website-detaisl')->render();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug, ?string $locale = null)
    {
        $locale = $locale ?? 'vi';
        // dd($locale);

        // dd("Locale: {$locale}, Slug: {$slug}");
        // dd("Vào controller với locale: {$locale}, slug: {$slug}");
        $website = DemoWebsite::where('slug', $slug)->firstOrFail();
        if ($locale != 'vi') {
            // dd('locale=vi');
            $translation = $website->translationId($locale)->first();

            if ($translation) {
                $website->name = $translation->name;
                $website->seo_title = $translation->seo_title;
                $website->seo_description = $translation->seo_description;
                $website->description = $translation->description;
                // $website->ref_lang = $$locale;
            }
        }


        return Theme::scope('website-detaisl', [
            'website' => $website,
        ])->render();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
