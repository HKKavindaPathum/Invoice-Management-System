<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'unit_price',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
