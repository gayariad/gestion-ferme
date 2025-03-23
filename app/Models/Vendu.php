<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Vendu extends Pivot
{
    use HasFactory;

    protected $table = 'vendu'; 
    public $timestamps = false;

    protected $fillable = [
        'id_vente', 'id_produit', 'quantite_produit', 'prix_unitaire'
    ];

    protected $casts = [
        'quantite_produit' => 'integer',
        'prix_unitaire'   => 'float',
    ];
}
