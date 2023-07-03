<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'buffer_time',
        'duration',
        'scheduling_window',
        'max_appointments_per_slot',
    ];

    public function weeklyBreaks()
    {
        return $this->hasMany(WeeklyBreak::class);
    }

    public function oneTimeBreaks()
    {
        return $this->hasMany(OneTimeBreak::class);
    }
}
