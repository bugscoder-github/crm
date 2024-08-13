<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class TemplateService extends Model
{
    use HasFactory;

    /**
     * Relationship belongs to team
     */
    public function team() :BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get all of the tags for the post.
     */
    public function services(): MorphToMany
    {
        return $this->morphToMany(Service::class, 'template_taggable');
    }
}
