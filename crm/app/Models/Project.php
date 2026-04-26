<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['company_id', 'law_id', 'name', 'due_at', 'status', 'notes'];

    protected $casts = ['due_at' => 'date'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function law()
    {
        return $this->belongsTo(Law::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
