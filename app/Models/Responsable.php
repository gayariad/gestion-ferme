<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Responsable extends Model
{
    use HasFactory;

    protected $table = 'Responsable';
    protected $primaryKey = 'id_responsable';
    public $timestamps = false;

    protected $fillable = [];

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'id_responsable', 'id_personne');
    }

    public function woofers(): HasMany
    {
        return $this->hasMany(Woofer::class, 'id_woofer', 'id_responsable');
    }
    
    public function ateliers(): HasMany
    {
        return $this->hasMany(Atelier::class, 'id_responsable', 'id_responsable');
    }
}
