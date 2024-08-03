<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    use SoftDeletes, HasFactory;

    protected $primaryKey = "productService_id";
    protected $guarded = [];
}
