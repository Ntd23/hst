<?php
$file = 'app/Http/Controllers/Api/Traits/ShortcodeApiTrait.php';
$content = file_get_contents($file);

$search1 = <<<'EOF'
    private function buildMetaPayload(?Page $page, string $locale): array
    {
        $seoTitle = theme_option('seo_title', theme_option('site_title', config('app.name')));
        $seoDescription = theme_option('seo_description', '');
        $seoImage = theme_option('seo_image', '');
        $seoIndex = (bool)theme_option('seo_index', true);
        $ogImage = null;

        if ($page) {
            $meta = $page->getMetaData('seo_meta', true);
            if (!empty($meta['seo_title'])) {
                $seoTitle = $meta['seo_title'];
            }
            if (!empty($meta['seo_description'])) {
                $seoDescription = $meta['seo_description'];
            }
            if (!empty($meta['seo_image'])) {
                $ogImage = $this->imageUrl($meta['seo_image']);
            }
            if (!empty($meta['index'])) {
                $seoIndex = $meta['index'] === 'index';
            }
        }

        if (!$ogImage && $seoImage) {
            $ogImage = $this->imageUrl($seoImage);
        }

        return [
            'locale' => $locale,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'og_image' => $ogImage,
            'seo_index' => $seoIndex,
            'favicon' => theme_option('favicon')
            ? $this->imageUrl(theme_option('favicon'))
            : null,
        ];
    }
EOF;

$replace1 = <<<'EOF'
    private function buildMetaPayload(?Page $page, string $locale): array
    {
        $seoTitle = null;
        $seoDescription = null;
        $seoIndex = true;
        $ogImage = null;

        if ($page) {
            if (method_exists($page, 'getMetaData')) {
                $meta = $page->getMetaData('seo_meta', true);
                if (!empty($meta['seo_title'])) {
                    $seoTitle = $meta['seo_title'];
                }
                if (!empty($meta['seo_description'])) {
                    $seoDescription = $meta['seo_description'];
                }
                if (!empty($meta['seo_image'])) {
                    $ogImage = $this->imageUrl($meta['seo_image']);
                }
                if (isset($meta['index'])) {
                    $seoIndex = $meta['index'] === 'index';
                }
            }

            if (!$seoTitle) {
                $seoTitle = $this->getTranslatedValue($page, 'name', $locale) ?: current(explode('(', $page->name));
            }
            if (!$seoDescription) {
                $seoDescription = $this->getTranslatedValue($page, 'description', $locale) ?: $page->description;
            }
            if (!$ogImage && isset($page->image)) {
                $ogImage = $this->imageUrl($page->image);
            }
        }

        return [
            'locale'          => $locale,
            'seo_title'       => $seoTitle ?: theme_option('seo_title', theme_option('site_title', config('app.name'))),
            'seo_description' => $seoDescription ?: theme_option('seo_description', ''),
            'og_image'        => $ogImage ?: ($this->imageUrl(theme_option('seo_image')) ?: null),
            'seo_index'       => $seoIndex,
            'favicon'         => theme_option('favicon') ? $this->imageUrl(theme_option('favicon')) : null,
        ];
    }
EOF;

$search2 = <<<'EOF'
    protected function buildGenericMetaPayload($model, string $locale): array
    {
        // Fallback từ theme option
        $seoTitle = theme_option('seo_title', theme_option('site_title', config('app.name')));
        $seoDescription = theme_option('seo_description', '');
        $seoImage = theme_option('seo_image', '');
        $seoIndex = (bool)theme_option('seo_index', true);
        $ogImage = null;

        // Lấy SEO meta từ model nếu hỗ trợ getMetaData
        if (method_exists($model, 'getMetaData')) {
            $meta = $model->getMetaData('seo_meta', true);
            if (!empty($meta['seo_title']))
                $seoTitle = $meta['seo_title'];
            if (!empty($meta['seo_description']))
                $seoDescription = $meta['seo_description'];
            if (!empty($meta['seo_image']))
                $ogImage = $this->imageUrl($meta['seo_image']);
            if (!empty($meta['index']))
                $seoIndex = $meta['index'] === 'index';
        }

        // Fallback title từ name/title của model
        if ($seoTitle === theme_option('seo_title', theme_option('site_title', config('app.name')))) {
            $modelName = $this->getTranslatedValue($model, 'name', $locale)
                ?? ($model->name ?? ($model->title ?? null));
            if ($modelName) {
                $seoTitle = $modelName;
            }
        }

        // Fallback description  
        if (!$seoDescription) {
            $seoDescription = $this->getTranslatedValue($model, 'description', $locale)
                ?? ($model->description ?? null);
        }

        // Fallback og:image từ model->image
        if (!$ogImage) {
            $ogImage = isset($model->image) ? $this->imageUrl($model->image) : null;
        }

        return [
            'locale' => $locale,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDescription,
            'og_image' => $ogImage,
            'seo_index' => $seoIndex,
            'favicon' => theme_option('favicon')
            ? $this->imageUrl(theme_option('favicon'))
            : null,
        ];
    }
EOF;

$replace2 = <<<'EOF'
    protected function buildGenericMetaPayload($model, string $locale): array
    {
        $seoTitle = null;
        $seoDescription = null;
        $seoIndex = true;
        $ogImage = null;

        // Lấy SEO meta từ model nếu hỗ trợ getMetaData
        if (method_exists($model, 'getMetaData')) {
            $meta = $model->getMetaData('seo_meta', true);
            if (!empty($meta['seo_title'])) {
                $seoTitle = $meta['seo_title'];
            }
            if (!empty($meta['seo_description'])) {
                $seoDescription = $meta['seo_description'];
            }
            if (!empty($meta['seo_image'])) {
                $ogImage = $this->imageUrl($meta['seo_image']);
            }
            if (isset($meta['index'])) {
                $seoIndex = $meta['index'] === 'index';
            }
        }

        // Fallback title từ name/title của model
        if (!$seoTitle) {
            $seoTitle = $this->getTranslatedValue($model, 'name', $locale) 
                     ?? ($model->name ?? ($model->title ?? null));
        }

        // Fallback description  
        if (!$seoDescription) {
            $seoDescription = $this->getTranslatedValue($model, 'description', $locale)
                           ?? ($model->description ?? null);
        }

        // Fallback og:image từ model->image
        if (!$ogImage) {
            $ogImage = isset($model->image) ? $this->imageUrl($model->image) : null;
        }

        return [
            'locale'          => $locale,
            'seo_title'       => $seoTitle ?: theme_option('seo_title', theme_option('site_title', config('app.name'))),
            'seo_description' => $seoDescription ?: theme_option('seo_description', ''),
            'og_image'        => $ogImage ?: ($this->imageUrl(theme_option('seo_image')) ?: null),
            'seo_index'       => $seoIndex,
            'favicon'         => theme_option('favicon') ? $this->imageUrl(theme_option('favicon')) : null,
        ];
    }
EOF;

$content = str_replace($search1, $replace1, $content);
$content = str_replace($search2, $replace2, $content);
file_put_contents($file, $content);
echo "Replaced properly\n";
