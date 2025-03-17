<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'total'
    ];

    public function works()
    {
        return $this->belongsTo(Work::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'rest_id');
    }

}
