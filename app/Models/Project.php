<?php

namespace App\Models;

use App\Models\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Project extends Model
{
    use Notifiable;
    protected $fillable = [
        'client_id',
        'user_id',
        'project_status_id',
        'payment_method_id',
        'payment_status_id',
        'title',
        'brief',
        'reffile_image',
        'image_name',
        'file_location',
        'deadline',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->user_id = app('auth')->id();
            $query->project_status_id = ProjectStatus::where('name', 'Open Project')->first()->id;
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    public function projectStatus()
    {
        return $this->belongsTo(ProjectStatus::class, 'project_status_id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
