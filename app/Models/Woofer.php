<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Personne;
use App\Models\Atelier;

class Woofer extends Model
{
    use HasFactory;

    protected $table = 'woofer';
    protected $primaryKey = 'id_woofer';
    public $timestamps = false;

    protected $fillable = [
        'id_woofer', 'debut_sejour', 'fin_sejour', 'photo', 'competence', 'presence'
    ];

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'id_woofer', 'id_personne');
    }

    public function taches()
    {
        return $this->belongsToMany(\App\Models\Tache::class, 'Effectue_Tache', 'id_woofer', 'id_tache');
    }
    
    public function ateliers(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Atelier::class, 'anime', 'id_woofer', 'id_atelier');
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class, 'id_woofer', 'id_woofer');
    }
}

