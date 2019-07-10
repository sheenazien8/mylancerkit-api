<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'label_color'
    ];

    protected $table = 'payment_status';

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
