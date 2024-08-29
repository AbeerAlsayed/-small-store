<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Category extends Model
{
    use HasFactory;

    protected $fillable=['name','slug','description','parent_id'];

    protected $appends = ['created_from'];

    public function getCreatedFromAttribute()
    {
        $twoHoursAgo = Carbon::now()->subHours(2);
        return $twoHoursAgo->diffForHumans();
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}