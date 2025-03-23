<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Affiche le formulaire de changement de mot de passe pour la première connexion.
     */
    public function showFirstLoginForm()
    {
        return view('auth.change-password'); // Créez la vue auth/change-password.blade.php
    }

    /**
     * Met à jour le mot de passe de l'utilisateur lors de sa première connexion.
     */
    public function updateFirstLogin(Request $request): RedirectResponse
    {
        // Validation sans exiger le mot de passe actuel
        $validated = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($validated['password']),
            // Marquer que le mot de passe n'est plus temporaire
            'password_temporary' => false,
        ]);

        return redirect()->route('dashboard')->with('status', 'Votre mot de passe a été modifié avec succès.');
    }

    /**
     * Met à jour le mot de passe de l'utilisateur (pour les modifications ultérieures).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Mot de passe mis à jour.');
    }
}
