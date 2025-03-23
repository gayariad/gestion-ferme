<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produit extends Model
{
    use HasFactory;

 
    protected $table = 'produit';
    protected $primaryKey = 'id_produit';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['nom_produit', 'categorie_produit', 'prix_produit', 'quantite_stock'];

    public function ventes()
    {
        return $this->belongsToMany(Vente::class, 'vendu', 'id_produit', 'id_vente')
                    ->withPivot(['quantite_produit', 'prix_unitaire']);
    }

}
