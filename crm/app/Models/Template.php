<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['law_id', 'category', 'title', 'doc_type', 'source', 'status', 'repo_path', 'comment'];

    public function law()
    {
        return $this->belongsTo(Law::class);
    }

    public function versions()
    {
        return $this->hasMany(TemplateVersion::class)->orderByDesc('version_number');
    }

    public function latestVersion()
    {
        return $this->hasOne(TemplateVersion::class)->latestOfMany('version_number');
    }
}
