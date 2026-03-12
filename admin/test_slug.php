<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$slugKey = 'trang-chu';
$slug = \Botble\Slug\Models\Slug::where('key', $slugKey)
    ->where('reference_type', \Botble\Page\Models\Page::class)
    ->first();

if ($slug && $slug->reference) {
    echo "Found page: " . $slug->reference->name . "\n";
} else {
    echo "Page not found for slug: $slugKey\n";
}
