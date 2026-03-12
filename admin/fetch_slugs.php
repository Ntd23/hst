<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pages = \Botble\Page\Models\Page::with('slugable')->get();
foreach ($pages as $p) {
    $slug = $p->slugable ? $p->slugable->key : 'none';
    echo "ID: {$p->id} | Name: {$p->name} | Slug: {$slug}\n";
}
