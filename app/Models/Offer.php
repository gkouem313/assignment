<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'description',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
