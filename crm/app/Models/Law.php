<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Law extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
