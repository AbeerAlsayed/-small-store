<?php

namespace App\Models;

use App\Traits\CreateFromTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Product extends Model
{
    use HasFactory,CreateFromTrait;

    protected $fillable=['name','slug','description','price','category_id'];

    protected $appends = ['created_from'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
