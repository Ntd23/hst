<?php
$file = __DIR__ . '/app/Http/Controllers/Api/Pages/HomeController.php';
$content = file_get_contents($file);

$methods = <<<'PHP'

    // ──────────────────────────────────────────────
    //  SECTION HANDLERS
    //  Mỗi handler nhận ($attrs, $locale) → return array|null
    // ──────────────────────────────────────────────

    private function getSimpleSlider(array $attrs, string $locale): ?array
    {
        $sliderKey = $attrs['key'] ?? null;
        if (!$sliderKey) {
            return null;
        }

        $slider = SimpleSlider::query()
            ->wherePublished()
            ->where('key', $sliderKey)
            ->first();

        if (!$slider || $slider->sliderItems->isEmpty()) {
            return null;
        }

        $slider->sliderItems->loadMissing('metadata');

        $items = $slider->sliderItems->map(function ($item) use ($locale) {
            return [
                'id' => $item->id,
                'locale' => $locale,
                'title' => (string) $item->title,
                'description' => (string) $item->description,
                'link' => (string) $item->link,
                'order' => (int) $item->order,
                'image' => $this->imageUrl($item->image),
                'tablet_image' => $this->imageUrl($item->getMetaData('tablet_image', true) ?: null),
                'mobile_image' => $this->imageUrl($item->getMetaData('mobile_image', true) ?: null),
                'subtitle' => $item->getMetaData('subtitle', true) ?: null,
                'button_label' => $item->getMetaData('button_label', true) ?: null,
                'data_count' => $item->getMetaData('data_count', true) ?: null,
                'data_count_description' => $item->getMetaData('data_count_description', true) ?: null,
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'slider_id' => $slider->id,
            'slider_key' => $slider->key,
            'slider_name' => (string) $slider->name,
            'items' => $items,
        ];
    }

    private function getSiteStatistics(array $attrs, string $locale): ?array
    {
        $tabs = $this->parseShortcodeTabs($attrs, ['title', 'data', 'unit', 'image'], ['image']);

        return [
            'locale' => $locale,
            'data' => array_merge(
                $this->commonSectionAttributes($attrs),
                ['tabs' => $tabs]
            ),
        ];
    }

    private function getServices(array $attrs, string $locale): ?array
    {
        $serviceIds = isset($attrs['service_ids'])
            ? array_filter(explode(',', $attrs['service_ids']))
            : [];

        if (empty($serviceIds)) {
            return null;
        }

        $services = \Botble\Portfolio\Models\Service::query()
            ->with(['metadata', 'slugable', 'translations'])
            ->wherePublished()
            ->whereIn('id', $serviceIds)
            ->get();

        if ($services->isEmpty()) {
            return null;
        }

        $items = $services->map(function ($service) use ($locale) {
            return [
                'id' => $service->id,
                'locale' => $locale,
                'name' => (string) $this->getTranslatedValue($service, 'name', $locale),
                'description' => (string) $this->getTranslatedValue($service, 'description', $locale),
                'image' => $this->imageUrl($service->image),
                'slug' => $service->slug,
                'icon' => $service->getMetaData('icon', true) ?: null,
                'icon_image' => $this->imageUrl($service->getMetaData('icon_image', true) ?: null),
            ];
        })->values()->toArray();

        return [
            'shortcode' => $this->commonSectionAttributes($attrs),
            'services' => $items,
        ];
    }

    private function getIncludeWebdemo(array $attrs, string $locale): ?array
    {
        $websites = \App\Models\DemoWebsite::query()
            ->whereNull('web_id')
            ->with('translations')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        if ($websites->isEmpty()) {
            return null;
        }

        $items = $websites->map(function ($website) use ($locale) {
            $translation = $website->translationByLang($locale);

            return [
                'id' => $website->id,
                'name' => (string) ($translation->name ?? $website->name),
                'slug' => $website->slug,
                'description' => (string) ($translation->description ?? $website->description),
                'seo_title' => (string) ($translation->seo_title ?? $website->seo_title),
                'seo_description' => (string) ($translation->seo_description ?? $website->seo_description),
                'url_client' => $website->url_client,
                'url_admin' => $website->url_admin,
                'img_full' => $this->imageUrl($website->img_full),
                'img_featured' => $this->imageUrl($website->img_feautrer),
                'created_at' => $website->created_at?->toIso8601String(),
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'items' => $items,
        ];
    }

    private function getAboutUsInformation(array $attrs, string $locale): ?array
    {
        $tabs = $this->parseShortcodeTabs($attrs, ['title', 'description', 'icon', 'icon_image'], ['icon_image']);

        return [
            'locale' => $locale,
            'data' => array_merge(
                $this->commonSectionAttributes($attrs),
                [
                    'image' => $this->imageUrl($attrs['image'] ?? null),
                    'image_1' => $this->imageUrl($attrs['image_1'] ?? null),
                    'image_2' => $this->imageUrl($attrs['image_2'] ?? null),
                    'data_count' => $attrs['data_count'] ?? null,
                    'data_count_description' => $attrs['data_count_description'] ?? null,
                    'author' => [
                        'name' => $attrs['author_name'] ?? null,
                        'title' => $attrs['author_title'] ?? null,
                        'avatar' => $this->imageUrl($attrs['author_avatar'] ?? null),
                        'signature' => $this->imageUrl($attrs['author_signature'] ?? null),
                    ],
                    'contact' => [
                        'title' => $attrs['contact_title'] ?? null,
                        'subtitle' => $attrs['contact_subtitle'] ?? null,
                        'url' => $attrs['contact_url'] ?? null,
                        'icon' => $attrs['contact_icon'] ?? null,
                        'icon_image' => $this->imageUrl($attrs['contact_icon_image'] ?? null),
                    ],
                    'tabs' => $tabs,
                ]
            ),
        ];
    }

    private function getContactBlock(array $attrs, string $locale): ?array
    {
        return [
            'locale' => $locale,
            'data' => array_merge(
                $this->commonSectionAttributes($attrs),
                ['phone_number' => $attrs['phone_number'] ?? null]
            ),
        ];
    }

    private function getTestimonials(array $attrs, string $locale): ?array
    {
        $testimonialIds = isset($attrs['testimonial_ids'])
            ? array_filter(explode(',', $attrs['testimonial_ids']))
            : [];

        if (empty($testimonialIds)) {
            return null;
        }

        $testimonials = \Botble\Testimonial\Models\Testimonial::query()
            ->with(['translations', 'metadata'])
            ->whereIn('id', $testimonialIds)
            ->wherePublished()
            ->get();

        if ($testimonials->isEmpty()) {
            return null;
        }

        $items = $testimonials->map(function ($testimonial) use ($locale) {
            return [
                'id' => $testimonial->id,
                'name' => $this->getTranslatedValue($testimonial, 'name', $locale),
                'company' => $this->getTranslatedValue($testimonial, 'company', $locale),
                'content' => $this->getTranslatedValue($testimonial, 'content', $locale),
                'image' => $this->imageUrl($testimonial->image),
                'rating_star' => (int) $testimonial->getMetaData('rating_star', true),
            ];
        })->values()->toArray();

        return array_merge(
            ['locale' => $locale],
            $this->commonSectionAttributes($attrs),
            [
                'image' => $this->imageUrl($attrs['image'] ?? null),
                'items' => $items,
            ]
        );
    }

    private function getTeam(array $attrs, string $locale): ?array
    {
        $teamIds = isset($attrs['team_ids'])
            ? array_filter(explode(',', $attrs['team_ids']))
            : [];

        if (empty($teamIds)) {
            return null;
        }

        $teams = \Botble\Team\Models\Team::query()
            ->with(['translations', 'metadata', 'slugable'])
            ->whereIn('id', $teamIds)
            ->wherePublished()
            ->get();

        if ($teams->isEmpty()) {
            return null;
        }

        $items = $teams->map(function ($team) use ($locale) {
            return [
                'id' => $team->id,
                'name' => $this->getTranslatedValue($team, 'name', $locale),
                'title' => $this->getTranslatedValue($team, 'title', $locale),
                'location' => $this->getTranslatedValue($team, 'location', $locale),
                'content' => $this->getTranslatedValue($team, 'content', $locale),
                'phone' => $this->getTranslatedValue($team, 'phone', $locale),
                'address' => $this->getTranslatedValue($team, 'address', $locale),
                'email' => $team->email,
                'website' => $team->website,
                'photo' => $this->imageUrl($team->photo),
                'url' => $team->url ?? null,
                'socials' => $team->socials ?? [],
            ];
        })->values()->toArray();

        return array_merge(
            ['locale' => $locale],
            $this->commonSectionAttributes($attrs),
            ['items' => $items]
        );
    }

    private function getFaqs(array $attrs, string $locale): ?array
    {
        $query = \Botble\Faq\Models\Faq::query()
            ->with('translations')
            ->wherePublished()
            ->orderByDesc('created_at');

        $categoryIds = isset($attrs['faq_category_ids'])
            ? array_filter(explode(',', $attrs['faq_category_ids']))
            : [];

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        $limit = isset($attrs['limit']) ? (int) $attrs['limit'] : 4;
        $faqs = $query->limit($limit)->get();

        if ($faqs->isEmpty()) {
            return null;
        }

        $items = $faqs->map(function ($faq) use ($locale) {
            return [
                'id' => $faq->id,
                'question' => $this->getTranslatedValue($faq, 'question', $locale),
                'answer' => $this->getTranslatedValue($faq, 'answer', $locale),
            ];
        })->values()->toArray();

        return [
            'locale' => $locale,
            'title' => $attrs['title'] ?? null,
            'description' => $attrs['description'] ?? null,
            'image' => $this->imageUrl($attrs['image'] ?? null),
            'floating_block' => [
                'icon' => $attrs['floating_block_icon'] ?? null,
                'title' => $attrs['floating_block_title'] ?? null,
                'description' => $attrs['floating_block_description'] ?? null,
            ],
            'items' => $items,
        ];
    }

    private function getBlogPosts(array $attrs, string $locale): ?array
    {
        $limit = isset($attrs['limit']) ? (int) $attrs['limit'] : 4;

        $query = \Botble\Blog\Models\Post::query()
            ->with(['slugable', 'translations', 'categories'])
            ->wherePublished()
            ->latest();

        $categoryIds = isset($attrs['category_ids'])
            ? array_filter(explode(',', $attrs['category_ids']))
            : [];

        if (!empty($categoryIds)) {
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        $posts = $query->limit($limit)->get();

        if ($posts->isEmpty()) {
            return null;
        }

        $items = $posts->map(function ($post) use ($locale) {
            return [
                'id' => $post->id,
                'name' => $this->getTranslatedValue($post, 'name', $locale),
                'description' => $this->getTranslatedValue($post, 'description', $locale),
                'image' => $this->imageUrl($post->image),
                'url' => $post->url ?? null,
                'created_at' => $post->created_at?->toIso8601String(),
                'author' => $post->author?->name ?? null,
                'categories' => $post->categories->map(fn($cat) => [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ])->values()->toArray(),
            ];
        })->values()->toArray();

        return array_merge(
            ['locale' => $locale],
            $this->commonSectionAttributes($attrs),
            [
                'image' => $this->imageUrl($attrs['image'] ?? null),
                'items' => $items,
            ]
        );
    }
}
PHP;

$content = preg_replace('/}\s*$/', $methods, $content);
file_put_contents($file, $content);
echo "HomeController fixed successfully.\n";
