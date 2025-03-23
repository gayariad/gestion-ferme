<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vente;
use App\Models\Produit;
use Carbon\Carbon;
use DB;

class VenteController extends Controller
{
    public function index()
    {
        $ventes = Vente::with('produits')->orderBy('date_vente', 'desc')->get();

        $produits = Produit::all();

        return view('ventes.index', compact('ventes', 'produits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_vente' => 'required|date',
            'produits'   => 'required|array',
            'total_vente'=> 'required|numeric|min:0',
        ]);

        $vente = new Vente();
        $vente->date_vente = $validated['date_vente'];
        $vente->quantite_produit = 0;
        $vente->total_vente = 0;
        $vente->save();

        $totalCalc = 0;
        $quantiteTotale = 0;

        foreach ($validated['produits'] as $p) {
            $id_produit = $p['id_produit'];
            $qte = $p['quantite'];

            $produit = Produit::find($id_produit);
            if (!$produit) {
                return redirect()->back()->withErrors("Produit introuvable.");
            }
            if ($produit->quantite_stock < $qte) {
                return redirect()->back()->withErrors("Stock insuffisant pour le produit {$produit->nom_produit}.");
            }

            DB::table('vendu')->insert([
                'id_vente' => $vente->id_vente,
                'id_produit' => $id_produit,
                'quantite_produit' => $qte,
                'prix_unitaire' => $produit->prix_produit,
            ]);

            $produit->quantite_stock -= $qte;
            $produit->save();

            $totalCalc += $qte * $produit->prix_produit;
            $quantiteTotale += $qte;
        }

        $vente->total_vente = $totalCalc * 1.2;
        $vente->quantite_produit = $quantiteTotale;
        $vente->save();

        return redirect()->route('ventes.index')->with('success', 'Vente enregistrée avec succès.');
    }

    public function show($id)
    {
        $vente = Vente::with('produits')->findOrFail($id);
        $produits = Produit::all();
        return view('ventes.show', compact('vente', 'produits'));
    }
        

    public function edit($id)
    {
        $vente = Vente::with('produits')->findOrFail($id);
        $produits = Produit::all();
        
        return view('ventes.show', compact('vente', 'produits'));
    }

    public function update(Request $request, $id_vente)
{
    dd($request->input('nouveaux_produits'));
    
    $vente = Vente::with('produits')->findOrFail($id_vente);

    $request->validate([
        'produits.*.quantite' => 'required|integer|min:1',
        'nouveaux_produits.*.quantite' => 'nullable|integer|min:1',
    ]);


    $submittedProducts = $request->input('produits', []);


    $originalProductIds = $vente->produits->pluck('id_produit')->toArray();
    foreach ($originalProductIds as $prodId) {
        if (!isset($submittedProducts[$prodId])) {
            $vente->produits()->detach($prodId);
        }
    }

    foreach ($submittedProducts as $prodId => $data) {
        if (isset($data['quantite'])) {
            $vente->produits()->updateExistingPivot($prodId, [
                'quantite_produit' => $data['quantite']
            ]);
        }
    }


    $newProducts = $request->input('nouveaux_produits', []);
    foreach ($newProducts as $item) {
        if (!empty($item['produit_id']) && !empty($item['quantite'])) {

            if (!$vente->produits->contains($item['produit_id'])) {
                $vente->produits()->attach($item['produit_id'], [
                    'quantite_produit' => $item['quantite'],
                    'prix_unitaire' => Produit::find($item['produit_id'])->prix_produit
                ]);
            }
        }
    }

    
    $vente->load('produits');
    $totalCalc = 0;
    $totalQuantity = 0;
    foreach ($vente->produits as $produit) {
        $qte = $produit->pivot->quantite_produit;
        $totalCalc += $qte * $produit->prix_produit;
        $totalQuantity += $qte;
    }
    $vente->total_vente = $totalCalc * 1.2;
    $vente->quantite_produit = $totalQuantity;
    $vente->save();

    return redirect()->route('ventes.show', $vente->id_vente)
                     ->with('success', 'Vente mise à jour avec succès.');
}


    public function destroy($id)
    {
        $vente = Vente::findOrFail($id);
        DB::table('vendu')->where('id_vente', $id)->delete();
        $vente->delete();
        return redirect()->route('ventes.index')->with('success', 'Vente annulée avec succès.');
    }
}
