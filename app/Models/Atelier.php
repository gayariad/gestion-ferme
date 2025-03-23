<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Woofer;
use App\Models\Client;

class Atelier extends Model
{
    use HasFactory;

    protected $table = 'atelier'; 
    protected $primaryKey = 'id_atelier';
    public $timestamps = false;

    protected $fillable = [
        'jour_atelier',
        'thematique_atelier',
        'etat_atelier',
        'tarif_atelier',
        'id_woofer' 
    ];


    public function woofers(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Woofer::class, 'anime', 'id_atelier', 'id_woofer');
    }


    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'participation', 'id_atelier', 'id_client');
    }
}
