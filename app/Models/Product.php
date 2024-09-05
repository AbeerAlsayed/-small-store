<?php

namespace App\Models;

use App\Traits\CreateFromTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Product extends Model
{
    use HasFactory,CreateFromTrait;

    protected $fillable=['name','slug','description','price','category_id','quantity'];

    protected $appends = ['created_from'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
