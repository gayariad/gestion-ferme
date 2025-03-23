<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Personne;
use App\Models\Compte;
use App\Models\Woofer;
use App\Models\User;

class WooferController extends Controller
{
    public function index()
    {
        
        $woofers = Woofer::with('personne')->get();
        return view('woofers.index', compact('woofers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'          => 'required|string|max:100',
            'prenom'       => 'required|string|max:100',
            'email'        => 'required|email|unique:personne,mail',
            'telephone'    => 'nullable|string|max:20',
            'adresse'      => 'nullable|string',
            'debut_sejour' => 'required|date',
            'fin_sejour'   => 'required|date|after:debut_sejour',
            'competence'   => 'nullable|string',
            'presence'     => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $personne = Personne::create([
                'nom'     => $validated['nom'],
                'prenom'  => $validated['prenom'],
                'mail'    => $validated['email'],
                'adresse' => $validated['adresse'],
                'tel'     => $validated['telephone'],
            ]);

            $woofer = Woofer::create([
                'id_woofer'    => $personne->id_personne,
                'debut_sejour' => $validated['debut_sejour'],
                'fin_sejour'   => $validated['fin_sejour'],
                'competence'   => $validated['competence'] ?? '',
                'presence'     => $request->has('presence') ? 1 : 0,
            ]);

            $temporaryPassword = Str::random(8);

            $user = User::create([
                'name'     => $personne->prenom . ' ' . $personne->nom,
                'email'    => $personne->mail,
                'role_id'  => 2,
                'password' => bcrypt($temporaryPassword),
            ]);

            DB::commit();

            session()->flash('user_credentials', [
                'email'    => $user->email,
                'password' => $temporaryPassword,
            ]);

            return redirect()->route('woofers.index')->with('success', 'Woofer ajouté avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la création du woofer : ' . $e->getMessage());
            return redirect()->route('woofers.index')->with('error', 'Erreur lors de l\'ajout du woofer.');
        }
    }

    public function destroy($id_woofer)
    {
        DB::beginTransaction();
        try {
            $woofer = Woofer::findOrFail($id_woofer);
            User::where('email', $woofer->personne->mail)->delete();
        
            $woofer->delete();
            
            DB::commit();
            return redirect()->route('woofers.index')->with('success', 'Woofer supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la suppression du woofer : ' . $e->getMessage());
            return redirect()->route('woofers.index')->with('error', 'Erreur lors de la suppression du woofer.');
        }
    }

    public function showTasks($id_woofer)
    {
        $woofer = Woofer::with(['personne', 'taches'])->findOrFail($id_woofer);
        return view('woofers.tasks', compact('woofer'));
    }
    
}
