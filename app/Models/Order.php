<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
use HasFactory;

protected $fillable = [
'order_number', 'user_id', 'status', 'grand_total', 'item_count', 'is_paid', 'payment_method', 'notes'
];

public function user()
{
return $this->belongsTo(User::class);
}

public function items()
{
return $this->hasMany(OrderItem::class);
}
}
