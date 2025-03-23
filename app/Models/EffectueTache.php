<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EffectueTache extends Pivot
{
    use HasFactory;

    protected $table = 'Effectue_Tache';
    public $timestamps = false;

    protected $fillable = [
        'id_woofer', 'id_tache'
    ];
}
