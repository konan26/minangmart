<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'image', 'description', 'stock'];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Check if product is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
