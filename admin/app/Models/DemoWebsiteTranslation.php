<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Botble\Base\Models\BaseModel;

class DemoWebsiteTranslation extends BaseModel
{
    use HasFactory;

    protected $table = 'demo_website_translations';

    protected $fillable = [
        'name',
        'seo_title',
        'seo_description',
        'description',
        'lang_code',
        'demo_website_id',
        'lang_id',
    ];

    public $timestamps = false;

    /**
     * Mối quan hệ: Một bản dịch thuộc về một website demo.
     */
    public function demoWebsite()
    {
        return $this->belongsTo(DemoWebsite::class, 'demo_website_id');
    }
}
