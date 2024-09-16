<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductServiceInOut extends Model
{
    use HasFactory;

    protected $attributes = [];
    protected $fillable = [];

    public function __construct() {
        $this->attributes = [
            'id' => null,
            'type' => null,
            'product_service_id' => null,
            'qty' => null,
            'unitPrice' => null,
            'supplier_id' => 0,
            'location_id' => 0
        ];

        $this->fillable = array_keys($this->attributes);
    }

    public function ProductService() {
        return $this->belongsTo(ProductService::class);
    }
}
