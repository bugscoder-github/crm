<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class LeadComment extends Model
{
    use SoftDeletes, HasFactory;
	
	protected $primaryKey = 'leadComment_id';
	
    protected $guarded = [];
}
