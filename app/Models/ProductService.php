<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    use HasFactory;

    protected $attributes = [];
    protected $fillable = [];

    public function __construct() {
        $this->attributes = [
            'id' => null,
            'category_id' => null,
            'type' => null,
            'name' => null
        ];

        $this->fillable = array_keys($this->attributes);
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
