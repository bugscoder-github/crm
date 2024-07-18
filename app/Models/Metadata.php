<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use SoftDeletes, HasFactory;

    protected $primaryKey = "metadata_id";
    const CREATED_AT = "metadata_createdAt";
    const UPDATED_AT = "metadata_updatedAt";

    protected $guarded = [];
}
