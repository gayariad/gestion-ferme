<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'client';
    protected $primaryKey = 'id_client';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
    ];

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'id_client', 'id_personne');
    }

    public function ateliers(): BelongsToMany
    {
        return $this->belongsToMany(Atelier::class, 'participation', 'id_client', 'id_atelier');
    }
}
