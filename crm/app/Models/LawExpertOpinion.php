<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawExpertOpinion extends Model
{
    protected $fillable = [
        'law_id',
        'expert_name',
        'opinion',
        'video_url',
        'video_transcript',
        'file_path',
        'resource_url',
        'sort_order',
    ];

    public function law()
    {
        return $this->belongsTo(Law::class);
    }
}