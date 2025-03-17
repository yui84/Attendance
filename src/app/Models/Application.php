<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'rest_id',
        'correction_id',
        'start',
        'end'
    ];

    public function rests()
    {
        return $this->belongsTo(Rest::class);
    }

    public function correction()
    {
        return $this->belongsTo(Correction::class);
    }
}
