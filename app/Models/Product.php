<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function getImageUrlAttribute() {
        if (!empty($this->image_key)) {
            return Storage::disk('public')->url($this->image_key);
        }
    }
}
