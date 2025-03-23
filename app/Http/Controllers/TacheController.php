<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tache;

class TacheController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_woofer'   => 'required|exists:woofer,id_woofer',
            'nom_tache'   => 'required|string|max:255',
            'date_tache'  => 'required|date',
            'temps_estime'=> 'nullable|numeric|min:0',
        ]);

        $tache = Tache::create([
            'nom_tache'   => $validated['nom_tache'],
            'date_tache'  => $validated['date_tache'],
            'temps_estime'=> $validated['temps_estime'] ?? 0,
        ]);

    $tache->woofers()->attach($validated['id_woofer']);

        return redirect()->back()->with('success', 'Tâche attribuée avec succès.');
    }
}
