<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoWebsite;
use App\Models\DemoWebsiteTranslation;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Language\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DemoWebsiteController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->pageTitle(trans('core/dashboard::dashboard.title_webdemo'));

        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // $query = DemoWebsite::query()->where('web_id', null);
        $query = DemoWebsite::query()->orderBy('created_at', 'DESC');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%');
        }

        $websiteDemos = $query->paginate($perPage)->withQueryString();


        return view('admin.demo_website.index', compact('websiteDemos', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $websites = DemoWebsite::where('web_id', null)->get();
        return view('admin.demo_website.create', compact('websites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:demo_websites,slug',
            'url_client' => 'nullable|url',
            'url_admin' => 'nullable|url',
            'web_id' => 'nullable|integer',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'img_full' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'img_feautrer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'status' => 'nullable|in:0,1',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        if ($request->hasFile('img_full')) {
            $validated['img_full'] = $request->file('img_full')->store('demo-websites', 'public');
        }

        if ($request->hasFile('img_feautrer')) {
            $validated['img_feautrer'] = $request->file('img_feautrer')->store('demo-websites', 'public');
        }

        DemoWebsite::create($validated);

        return $this
            ->httpResponse()
            ->setNextRoute('portfolio.websites.index')
            ->setMessage(__('Created form'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        // dd($request->all());

        $website = DemoWebsite::findOrFail($id);
        $websites = DemoWebsite::where('web_id', null)->get();
        $lang_list = Language::where('lang_is_default', 0)->get();
        $current_lang = Language::where('lang_is_default', 1)->first();
        $lang_default = Language::where('lang_is_default', 1)->first();
        $lang = request()->query('ref_lang');

        if ($lang) {
            $lang_list = Language::where('lang_code', '!=', $lang)->get();
            $current_lang = Language::where('lang_code', $lang)->first();
            $translation = $website->translation($lang)->first();

            if ($translation) {
                $website->name = $translation->name;
                $website->seo_title = $translation->seo_title;
                $website->seo_description = $translation->seo_description;
                $website->description = $translation->description;
                $website->ref_lang = $lang;
            }
        }
        return view('admin.demo_website.edit', compact('website', 'websites', 'lang_list', 'current_lang', 'lang_default'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        if (!$request->lang_code) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:demo_websites,slug,' . $id,
                'url_client' => 'nullable|url',
                'url_admin' => 'nullable|url',
                'web_id' => 'nullable|integer',
                'seo_title' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:255',
                'img_full' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'img_feautrer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'status' => 'nullable|in:0,1',
                'description' => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'seo_title' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:255',
                'status' => 'nullable|in:0,1',
                'description' => 'nullable|string',
                "lang_code" => "required|string|max:255"
            ]);
        }

        $langDefault = Language::where('lang_is_default', 1)->first();

        // dd($request->all());

        $demoWebsite = DemoWebsite::findOrFail($id);

        // dd($id);

        if ($langDefault->lang_code != $request->language) {
            $demoWebsite = DemoWebsiteTranslation::where('lang_code', $request->language)
                ->where('demo_website_id', $id)->first();
        }
        // dd($demoWebsite);
        // Auto tạo slug nếu chưa có
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        // Lưu ảnh mới nếu có upload, nếu không giữ ảnh cũ
        if ($request->hasFile('img_full')) {
            $validated['img_full'] = $request->file('img_full')->store('demo-websites', 'public');
        } else {
            unset($validated['img_full']); // tránh ghi đè null
        }

        if ($request->hasFile('img_feautrer')) {
            $validated['img_feautrer'] = $request->file('img_feautrer')->store('demo-websites', 'public');
        } else {
            unset($validated['img_feautrer']);
        }

        if (!$demoWebsite) {
            $validated['demo_website_id'] = $id;
            $validated['lang_id'] = substr($validated['lang_code'], 0, 2);
            DemoWebsiteTranslation::create($validated);
            return $this
                ->httpResponse()
                // ->setNextRoute('portfolio.websites.index')
                ->setMessage(__('Updated form'));
        } else {
            $demoWebsite->update($validated);
            return $this
                ->httpResponse()
                // ->setNextRoute('portfolio.websites.index')
                ->setMessage(__('Updated form'));
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $demoWebsite = DemoWebsite::findOrFail($id);
        DemoWebsiteTranslation::where('demo_website_id', $id)->delete();
        $demoWebsite->delete();
        return $this
            ->httpResponse()
            // ->setNextRoute('portfolio.websites.index')
            ->setMessage(__('Deleted form'));
    }
}
