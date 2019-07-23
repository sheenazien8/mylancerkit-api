<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->user_id = app('auth')->id();
        });
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
