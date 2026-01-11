<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'image', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all of the groceries for the Category
     *
     */
    public function groceries(): HasMany
    {
        return $this->hasMany(Grocery::class);
    }
}
