<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correction extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'start',
        'end',
        'note',
        'status'
    ];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function application()
    {
        return $this->hasOne(Application::class);
    }
}
