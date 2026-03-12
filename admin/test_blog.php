<?php
$json = @file_get_contents('http://127.0.0.1:8000/api/pages/blog/sections?locale=vi');
$data = json_decode($json, true);

if(isset($data['sections'])) {
    echo "Blog Sections: " . count($data['sections']) . PHP_EOL;
} else {
    echo "Failed Blog: " . $json . PHP_EOL;
}
