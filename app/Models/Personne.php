<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Personne extends Model
{
    use HasFactory;

    protected $table = 'personne';
    protected $primaryKey = 'id_personne';
    protected $keyType = 'int';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'nom', 'prenom', 'mail', 'adresse', 'tel'
    ];

    public function woofer(): HasOne
    {
        return $this->hasOne(Woofer::class, 'id_woofer', 'id_personne');
    }

    public function responsable(): HasOne
    {
        return $this->hasOne(Responsable::class, 'id_responsable', 'id_personne');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'id_client', 'id_personne');
    }

    public function compte(): HasOne
    {
        return $this->hasOne(Compte::class, 'id_personne', 'id_personne');
    }
}
