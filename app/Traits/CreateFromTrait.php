<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait CreateFromTrait
{
    public function getCreatedFromAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
