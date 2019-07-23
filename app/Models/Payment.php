<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'amount',
        'payment'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->user_id = app('auth')->id();
        });
    }


    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
