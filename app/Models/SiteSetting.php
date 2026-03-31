<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['favicon'];

    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? \App\Helpers\ImageHelper::getUrl($this->favicon) : asset('favicon_io (3)/android-chrome-512x512.png');
    }
}
