<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atelier;
use App\Models\Woofer;

class AtelierController extends Controller
{
    public function index()
    {
        $ateliers = Atelier::with(['woofers.personne', 'clients.personne'])->get();
        $woofers = Woofer::with('personne')->get();
        return view('ateliers.index', compact('ateliers', 'woofers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jour_atelier'       => 'required|date',
            'thematique_atelier' => 'required|string|max:255',
            'etat_atelier'       => 'required|in:validé,reprogrammé,annulé,en attente',
            'tarif_atelier'      => 'required|numeric|min:0|max:20',
            'id_woofer'          => 'required|exists:woofer,id_woofer',
        ]);

        $atelier = Atelier::create($validated);

        $atelier->woofers()->attach($request->id_woofer);

        return redirect()->route('ateliers.index')->with('success', 'Atelier ajouté avec succès.');
    }

    public function show($id)
    {
        $atelier = Atelier::with(['clients.personne', 'woofers.personne'])->findOrFail($id);
        $woofers = Woofer::with('personne')->get();
        return view('ateliers.show', compact('atelier', 'woofers'));
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'etat_atelier'       => 'required|in:validé,reprogrammé,annulé,en attente',
        'jour_atelier'       => 'sometimes|date',
        'thematique_atelier' => 'sometimes|string|max:255',
        'tarif_atelier'      => 'sometimes|numeric|min:0|max:20',
    ]);

    $atelier = Atelier::findOrFail($id);
    $atelier->update($validated);

    $woofer_id = $request->input('id_woofer');
    if ($woofer_id) {
        $atelier->woofers()->sync([$woofer_id]);
    }

    return redirect()->route('ateliers.show', $id)->with('success', 'Atelier mis à jour.');
}

    public function destroy($id)
    {
        Atelier::destroy($id);
        return redirect()->route('ateliers.index')->with('success', 'Atelier supprimé.');
    }

    public function addParticipant(Request $request, $id)
    {
        $validated = $request->validate([
            'nom'    => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'mail'   => 'required|email',
            'tel'    => 'nullable|string|max:20',
        ]);

        $personne = \App\Models\Personne::where('mail', $validated['mail'])->first();
        if (!$personne) {
            $personne = \App\Models\Personne::create([
                'nom'     => $validated['nom'],
                'prenom'  => $validated['prenom'],
                'mail'    => $validated['mail'],
                'tel'     => $validated['tel'] ?? '',
                'adresse' => '',
            ]);
        }

        $client = \App\Models\Client::find($personne->id_personne);
        if (!$client) {
            $client = new \App\Models\Client();
            $client->id_client = $personne->id_personne;
            $client->save();
        }

        $atelier = Atelier::findOrFail($id);
        $atelier->clients()->syncWithoutDetaching([$client->id_client]);

        return redirect()->route('ateliers.show', $id)->with('success', 'Participant ajouté avec succès.');
    }
}
