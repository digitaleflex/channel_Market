<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'category',
        'format',
        'pages_count',
        'language',
        'publication_year',
        'description',
        'price',
        'file_path',
        'sample_file',
        'image',
        'currency',
        'chariow_product_id',
        'isbn',
        'testimonials',
    ];

    protected $casts = [
        'testimonials' => 'array',
        'pages_count' => 'integer',
        'publication_year' => 'integer',
        'price' => 'float',
    ];

    /**
     * Scope for searching books by title, author, or description.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (! empty($term)) {
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('author', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        return $query;
    }

    /**
     * Scope for filtering by category.
     */
    public function scopeCategory($query, ?string $category)
    {
        if (! empty($category) && $category !== 'all') {
            $query->where('category', $category);
        }

        return $query;
    }
}
