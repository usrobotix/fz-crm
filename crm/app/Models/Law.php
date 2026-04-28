<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Law extends Model
{
    protected $fillable = [
        'code',
        'country',
        'type',
        'status',
        'published_at',
        'name',
        'description',
        'official_url',
        'word_url',
        'consultant_url',
        'tags',
        'comment',
    ];

    protected $casts = [
        'published_at' => 'date',
        'tags' => 'array',
    ];

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function expertOpinions()
    {
        return $this->hasMany(LawExpertOpinion::class)->orderBy('sort_order');
    }
}