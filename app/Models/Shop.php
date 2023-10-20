<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_category_id',
        'name',
        'description',
        'open_hours',
        'city',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shopCategory()
    {
        return $this->belongsTo(ShopCategory::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
