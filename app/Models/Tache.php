<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tache extends Model
{
    use HasFactory;

    protected $table = 'Tache';
    protected $primaryKey = 'id_tache';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'nom_tache', 'date_tache', 'temps_estime'
    ];


    public function woofers(): BelongsToMany
    {
        return $this->belongsToMany(Woofer::class, 'Effectue_Tache', 'id_tache', 'id_woofer');
    }

    
}
