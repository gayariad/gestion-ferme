<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Participation extends Pivot
{
    use HasFactory;

    protected $table = 'Participation';
    public $timestamps = false;

    protected $fillable = [
        'id_client', 'id_atelier'
    ];
}
