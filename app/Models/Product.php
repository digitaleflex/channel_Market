<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'file_path',
        'image',
        'currency',
        'chariow_product_id',
        'testimonials',
    ];

    protected $casts = [
        'testimonials' => 'array',
    ];
}
