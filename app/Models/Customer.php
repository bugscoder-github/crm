<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
	use SoftDeletes, HasFactory;
	
	protected $primaryKey = 'customer_id';
	
    protected $guarded = [];
}
