<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $attributes = [];
    protected $fillable = [];

    public function __construct() {
        $this->attributes = [
            'id' => null,
            'name' => null
        ];

        $this->fillable = array_keys($this->attributes);
    }
}
