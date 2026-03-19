<?php

namespace Botble\Portfolio\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Revision\RevisionableTrait;
use Botble\Slug\Models\Slug;

class DemoWebsite extends BaseModel
{

    protected $table = 'demo_websites';

    protected $fillable = [
        'name',
        'url_client',
        'web_id',
        'url_admin',
        'seo_title',
        'seo_description',
        'img_full',
        'img_feautrer',
        'status',
        'content',
    ];
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
    public $timestamps = true;

    public function parent()
    {
        return $this->belongsTo(DemoWebsite::class, 'web_id');
    }

    public function children()
    {
        return $this->hasMany(DemoWebsite::class, 'web_id');
    }

    public function getChildrenCountAttribute()
    {
        return $this->children()->count();
    }
     // Quan hệ morphOne với Slug

    

    // public function translation($lang_code)
    // {
    //     return $this->hasOne(DemoWebsiteTranslation::class)
    //         ->where('lang_code', $lang_code);
    // }

    // public function translationId($lang_code)
    // {
    //     return $this->hasOne(DemoWebsiteTranslation::class)
    //         ->where('lang_id', $lang_code);
    // }

    // public function translations()
    // {
    //     return $this->hasMany(DemoWebsiteTranslation::class);
    // }

    // public function translationByLang($lang = null)
    // {
    //     $lang = $lang ?: app()->getLocale();

    //     return $this->translations->where('lang_id', $lang)->first();
    // }
}
