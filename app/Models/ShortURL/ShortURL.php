<?php

namespace App\Models\ShortURL;

use AshAllenDesign\ShortURL\Models\ShortURL as BaseModel;

class ShortURL extends BaseModel
{
  protected $appends = ["short_url"];
  public function getShortUrlAttribute() {
    return url(config('short-url.prefix')."/". $this->url_key);
  }
}