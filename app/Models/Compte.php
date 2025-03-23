<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compte extends Model
{
    use HasFactory;

    protected $table = 'compte';
    protected $primaryKey = 'id_utilisateur';
    public $timestamps = false;

    protected $fillable = [
        'id_utilisateur', 'id_personne', 'role_utilisateur', 'mot_de_passe', 'permissions_attribue'
    ];
    
    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'id_personne', 'id_personne');
    }
}
