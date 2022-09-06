<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'user_id', 'category', 'front_url', 'id_image'];

    public static function search($query = '')
    {
        if (!$query) {
            return self::all();
        }
        return self::where('name', 'like', "%$query%")
            ->orWhere('category', 'like', "%$query%")->get();
    }
}
