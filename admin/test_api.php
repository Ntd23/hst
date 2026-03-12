<?php
$json = file_get_contents('http://127.0.0.1:8000/api/pages/home/sections?locale=vi');
$data = json_decode($json, true);

if (isset($data['sections'])) {
    echo "SUCCESS! Found " . count($data['sections']) . " sections.\n";
    foreach ($data['sections'] as $s) {
        $hasContent = $s['content'] !== null ? 'yes' : 'no';
        echo "- " . $s['shortcode'] . ' (has data: ' . $hasContent . ")\n";
    }
} else {
    echo "FAILED: \n";
    print_r($data);
}
