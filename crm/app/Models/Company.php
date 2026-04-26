<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'inn', 'contact_person', 'email', 'phone', 'notes'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
