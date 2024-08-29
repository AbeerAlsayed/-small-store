<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable=['name','slug','description','price','category_id'];

    protected $appends = ['created_from'];

    public function getCreatedFromAttribute()
    {
        $twoHoursAgo = Carbon::now()->subHours(2);
        return $twoHoursAgo->diffForHumans();
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
