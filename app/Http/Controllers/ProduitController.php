<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::all();
        
        return view('produits.index', compact('produits'));
    }

    public function ajouter(Request $request)
    {
        $validatedData = $request->validate([
            'nom_produit' => 'required|string|max:255',
            'categorie_produit' => 'required|string|max:255',
            'prix_produit' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer',
        ]);

        Produit::create($validatedData);

        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès');
    }

    public function modifierStock(Request $request)
    {
    $request->validate([
        'id_produit' => 'required|exists:Produit,id_produit',
        'quantite_stock' => 'required|integer'
    ]);

    $produit = Produit::findOrFail($request->id_produit);
    $produit->quantite_stock += $request->quantite_stock;

    if ($produit->quantite_stock < 0) {
        return redirect()->route('produits.index')->with('error', 'La quantité en stock ne peut pas être négative.');
    }

    $produit->save();

    return redirect()->route('produits.index')->with('success', 'Stock mis à jour avec succès.');
    }

    public function supprimer(Request $request)
    {
    $request->validate([
        'id_produit' => 'required|exists:Produit,id_produit'
    ]);

    Produit::where('id_produit', $request->id_produit)->delete();

    return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }


}
