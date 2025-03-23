@extends('layouts.app')

@section('title', 'Woofers')

@section('content')
<div class="max-w-7xl mx-auto my-4">
    <!-- Bouton pour ouvrir le modal d'ajout de woofer -->
    <div x-data="{ addOpen: false }">
        <button @click="addOpen = true" type="button" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mb-4">
            Ajouter un Woofer
        </button>

        <!-- Modal d'ajout de woofer -->
        <div x-show="addOpen" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50" style="display: none;">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
                <form action="{{ route('woofers.store') }}" method="POST">
                    @csrf
                    <div class="flex justify-between items-center border-b px-4 py-2">
                        <h5 class="text-xl font-semibold">Ajouter un Woofer</h5>
                        <button type="button" @click="addOpen = false" class="text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
                    </div>
                    <div class="p-4 space-y-4">
                        <!-- Informations personnelles -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input type="text" name="nom" id="nom" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                            <input type="text" name="prenom" id="prenom" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="text" name="telephone" id="telephone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                            <input type="text" name="adresse" id="adresse" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <!-- Informations spécifiques au woofer -->
                        <div>
                            <label for="debut_sejour" class="block text-sm font-medium text-gray-700">Début de séjour</label>
                            <input type="date" name="debut_sejour" id="debut_sejour" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="fin_sejour" class="block text-sm font-medium text-gray-700">Fin de séjour</label>
                            <input type="date" name="fin_sejour" id="fin_sejour" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="competence" class="block text-sm font-medium text-gray-700">Compétences</label>
                            <textarea name="competence" id="competence" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="presence" id="presence" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="presence" class="ml-2 block text-sm text-gray-900">Présent</label>
                        </div>
                    </div>
                    <div class="flex justify-end border-t px-4 py-2">
                        <button type="button" @click="addOpen = false" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                            Annuler
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- En-tête de la page avec barre de recherche -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Woofers</h1>
            <div class="flex items-center space-x-2">
                <input id="searchInput" type="text" placeholder="Recherche" aria-label="Recherche" class="border border-gray-300 rounded-md px-3 py-2">
                <button id="searchBtn" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Réinitialiser
                </button>
            </div>
        </div>
    
        <!-- Grille des cartes "Woofers" -->
        <div id="woofersContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse ($woofers as $woofer)
                <div class="woofer-card bg-white shadow rounded p-4 relative flex flex-col" data-search="{{ strtolower($woofer->personne->nom . ' ' . $woofer->personne->prenom . ' ' . $woofer->personne->mail) }}">
                    <!-- Bouton de suppression -->
                    <form action="{{ route('woofers.destroy', $woofer->id_woofer) }}" method="POST" class="absolute top-2 right-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xl">&times;</button>
                    </form>
                    <!-- Image -->
                    <div class="flex justify-center mt-3">
                        <box-icon name="user-circle" type="solid" flip="horizontal" size="lg"></box-icon>
                    </div>
                    <!-- Nom et prénom -->
                    <div class="mt-3 text-center">
                        <p class="font-bold text-lg">{{ $woofer->personne->nom }} {{ $woofer->personne->prenom }}</p>
                    </div>
                    <!-- Bouton pour ouvrir le modal d'infos -->
                    <div class="mt-auto text-center p-2">
                        <button @click="detailOpen = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            + infos
                        </button>
                        <a href="{{ route('woofers.tasks', $woofer->id_woofer) }}" class="text-blue-600 hover:underline ml-4">
                            Voir le calendrier / Attribuer des tâches
                        </a>
                    </div>

                    <!-- Modal d'infos pour ce woofer -->
                    <div x-data="{ detailOpen: false }" x-show="detailOpen" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50" style="display: none;">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-4">
                            <div class="flex justify-between items-center border-b pb-2">
                                <h2 class="text-xl font-bold">Détails du Woofer</h2>
                                <button type="button" @click="detailOpen = false" class="text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
                            </div>
                            <div class="mt-4 space-y-2">
                                <p><strong>Nom :</strong> {{ $woofer->personne->nom }}</p>
                                <p><strong>Prénom :</strong> {{ $woofer->personne->prenom }}</p>
                                <p><strong>Email :</strong> {{ $woofer->personne->mail }}</p>
                                <p><strong>Téléphone :</strong> {{ $woofer->personne->tel }}</p>
                                <p><strong>Adresse :</strong> {{ $woofer->personne->adresse }}</p>
                                <p><strong>Début de séjour :</strong> {{ $woofer->debut_sejour }}</p>
                                <p><strong>Fin de séjour :</strong> {{ $woofer->fin_sejour }}</p>
                                <p><strong>Compétences :</strong> {{ $woofer->competence }}</p>
                                <p><strong>Présence :</strong> {{ $woofer->presence ? 'Oui' : 'Non' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>Aucun woofer n’a encore été enregistré.</p>
            @endforelse
        </div>
    </div>

    @if(session('user_credentials'))
    <!-- Modal d'affichage des identifiants -->
    <div class="modal fade" id="credentialsModal" tabindex="-1" aria-labelledby="credentialsModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="credentialsModalLabel">Identifiants du compte créé</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <p><strong>Email :</strong> {{ session('user_credentials.email') }}</p>
            <p><strong>Mot de passe temporaire :</strong> {{ session('user_credentials.password') }}</p>
            <p>Veuillez noter ces identifiants pour vous connecter.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function(){
          var credentialsModal = new bootstrap.Modal(document.getElementById('credentialsModal'));
          credentialsModal.show();
      });
    </script>
    @endif

    <!-- Script pour la recherche -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            var query = this.value.toLowerCase();
            var cards = document.querySelectorAll('.woofer-card');
            cards.forEach(function(card) {
                var text = card.getAttribute('data-search');
                card.style.display = text.includes(query) ? "block" : "none";
            });
        });

        document.getElementById('searchBtn').addEventListener('click', function() {
            document.getElementById('searchInput').value = "";
            var cards = document.querySelectorAll('.woofer-card');
            cards.forEach(function(card) {
                card.style.display = "block";
            });
        });
    </script>
</div>
@endsection
