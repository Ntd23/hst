<?php

$traitFile = __DIR__ . '/app/Http/Controllers/Api/Traits/ShortcodeApiTrait.php';
$traitContent = file_get_contents($traitFile);

$oldTraitMethod = <<<'PHP'
    protected function getAllShortcodeAttributes(string $content): array
    {
        preg_match_all('/\[([a-zA-Z0-9\-_]+)\s+([^\]]*)\]/', $content, $matches, PREG_SET_ORDER);

        $result = [];
        foreach ($matches as $match) {
            $name = $match[1];
            $attrString = $match[2];
            $attrs = $this->parseShortcodeAttributeString($attrString);
            if (!empty($attrs)) {
                $result[$name] = $attrs;
            }
        }

        return $result;
    }
PHP;

$newTraitMethod = <<<'PHP'
    protected function getAllShortcodeAttributes(string $content): array
    {
        // Cho phép shortcode không có params (VD: [include-webdemo])
        preg_match_all('/\[([a-zA-Z0-9\-_]+)(?:\s+([^\]]*))?\]/', $content, $matches, PREG_SET_ORDER);

        $result = [];
        foreach ($matches as $match) {
            $name = $match[1];
            $attrString = $match[2] ?? '';
            $attrs = $this->parseShortcodeAttributeString($attrString);
            
            // Trả về mảng tuần tự để giữ thứ tự shortcode và không bị ghi đè trùng tên
            $result[] = [
                'name' => $name,
                'attrs' => $attrs,
            ];
        }

        return $result;
    }
PHP;

// normalize newlines for replace
$oldTraitMethod = str_replace("\r\n", "\n", $oldTraitMethod);
$traitContent = str_replace("\r\n", "\n", $traitContent);
$traitContent = str_replace($oldTraitMethod, $newTraitMethod, $traitContent);
file_put_contents($traitFile, $traitContent);

$homeFile = __DIR__ . '/app/Http/Controllers/Api/Pages/HomeController.php';
$homeContent = file_get_contents($homeFile);

$oldHomeLogic = <<<'PHP'
            // Parse tất cả shortcodes từ content
            $allShortcodes = $this->getAllShortcodeAttributes($content);
            $sections = [];

            foreach ($allShortcodes as $shortcodeName => $attrs) {
                // Convert shortcode-name → getShortcodeName (camelCase handler)
                $method = 'get' . str_replace(' ', '', ucwords(str_replace('-', ' ', $shortcodeName)));

                if (method_exists($this, $method)) {
                    $sections[$shortcodeName] = $this->$method($attrs, $locale);
                } else {
                    // Shortcode không có handler → trả raw attributes + common attributes
                    $sections[$shortcodeName] = [
                        'locale' => $locale,
                        'data' => $this->commonSectionAttributes($attrs),
                    ];
                }
            }
PHP;

$newHomeLogic = <<<'PHP'
            // Parse tất cả shortcodes từ content (dạng danh sách để giữ thứ tự)
            $allShortcodes = $this->getAllShortcodeAttributes($content);
            $sections = [];

            foreach ($allShortcodes as $item) {
                $shortcodeName = $item['name'];
                $attrs = $item['attrs'];
                
                // Convert shortcode-name → getShortcodeName (camelCase handler)
                $method = 'get' . str_replace(' ', '', ucwords(str_replace('-', ' ', $shortcodeName)));

                $sectionData = null;
                if (method_exists($this, $method)) {
                    $sectionData = $this->$method($attrs, $locale);
                } else {
                    // Shortcode không có handler → trả raw attributes + common attributes
                    $sectionData = [
                        'locale' => $locale,
                        'data' => $this->commonSectionAttributes($attrs),
                    ];
                }
                
                $sections[] = [
                    'shortcode' => $shortcodeName,
                    'content' => $sectionData,
                ];
            }
PHP;

$oldHomeLogic = str_replace("\r\n", "\n", $oldHomeLogic);
$homeContent = str_replace("\r\n", "\n", $homeContent);
$homeContent = str_replace($oldHomeLogic, $newHomeLogic, $homeContent);
file_put_contents($homeFile, $homeContent);

echo "Patcher finished\n";
