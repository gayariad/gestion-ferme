<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vente extends Model
{
    use HasFactory;

    protected $table = 'Vente';
    protected $primaryKey = 'id_vente';
    public $timestamps = false;
    protected $keyType = 'int';   

    protected $fillable = [
        'date_vente', 'quantite_produit', 'total_vente'
    ];

   
    public function woofer(): BelongsTo
    {
        return $this->belongsTo(Woofer::class, 'id_woofer', 'id_woofer');
    }

  
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'vendu', 'id_vente', 'id_produit')
                    ->withPivot(['quantite_produit', 'prix_unitaire']);
    }


    public function show($id)
    {
        
        $vente = \App\Models\Vente::with('produits')->findOrFail($id);
        return view('ventes.show', compact('vente'));
    }
}
