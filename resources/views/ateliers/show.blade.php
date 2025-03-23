@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h1 class="fw-bold mb-4">Détails de l'atelier : {{ $atelier->thematique_atelier }}</h1>

    <div class="mb-4">
        <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($atelier->jour_atelier)->format('d/m/Y') }}</p>
        <p><strong>État :</strong> {{ $atelier->etat_atelier }}</p>
        <p><strong>Tarif :</strong> {{ $atelier->tarif_atelier }} €</p>
        <p><strong>Woofer en charge :</strong>
            @if ($atelier->woofers->isNotEmpty())
                {{ $atelier->woofers->first()->personne->nom }} {{ $atelier->woofers->first()->personne->prenom }}
            @else
                ---
            @endif
        </p>
    </div>

    <!-- Boutons d'action qui ouvrent les modals -->
    <div class="mb-4">
        <!-- Bouton pour ouvrir le modal d'ajout de participant -->
        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
            Ajouter des participants
        </button>
        <!-- Bouton pour ouvrir le modal de modification de l'atelier -->
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editAtelierModal">
            Modifier l'atelier
        </button>
    </div>

    <h2 class="fw-bold mb-3">Liste des participants</h2>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nom & Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($atelier->clients as $client)
                    <tr>
                        <td>{{ $client->personne->nom ?? '---' }} {{ $client->personne->prenom ?? '' }}</td>
                        <td>{{ $client->personne->mail ?? '---' }}</td>
                        <td>{{ $client->personne->tel ?? '---' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Aucun participant enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('ateliers.index') }}" class="btn btn-secondary mt-4">Retour à la liste</a>
</div>

<!-- Modal pour ajouter un participant -->
<div class="modal fade" id="addParticipantModal" tabindex="-1" aria-labelledby="addParticipantModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('ateliers.addParticipant', $atelier->id_atelier) }}" method="POST">
          @csrf
          <div class="modal-header">
              <h5 class="modal-title" id="addParticipantModalLabel">Ajouter un participant</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <!-- Champs pour créer un participant (informations dans la table personne) -->
              <div class="mb-3">
                  <label for="nom" class="form-label">Nom</label>
                  <input type="text" name="nom" id="nom" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label for="prenom" class="form-label">Prénom</label>
                  <input type="text" name="prenom" id="prenom" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label for="mail" class="form-label">Email</label>
                  <input type="email" name="mail" id="mail" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label for="tel" class="form-label">Téléphone</label>
                  <input type="text" name="tel" id="tel" class="form-control">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary">Ajouter</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal pour modifier l'atelier -->
<div class="modal fade" id="editAtelierModal" tabindex="-1" aria-labelledby="editAtelierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Notez que le formulaire n'essaiera pas de mettre à jour un champ id_woofer dans la table atelier,
           il synchronisera la relation dans la table pivot "anime". -->
      <form action="{{ route('ateliers.update', $atelier->id_atelier) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-header">
              <h5 class="modal-title" id="editAtelierModalLabel">Modifier l'atelier</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label for="jour_atelier_edit" class="form-label">Date de l'atelier</label>
                  <input type="date" name="jour_atelier" id="jour_atelier_edit" class="form-control" value="{{ $atelier->jour_atelier }}" required>
              </div>
              <div class="mb-3">
                  <label for="thematique_atelier_edit" class="form-label">Thématique</label>
                  <input type="text" name="thematique_atelier" id="thematique_atelier_edit" class="form-control" value="{{ $atelier->thematique_atelier }}" required>
              </div>
              <div class="mb-3">
                  <label for="etat_atelier_edit" class="form-label">État</label>
                  <select name="etat_atelier" id="etat_atelier_edit" class="form-select" required>
                      <option value="validé" {{ $atelier->etat_atelier == 'validé' ? 'selected' : '' }}>Validé</option>
                      <option value="reprogrammé" {{ $atelier->etat_atelier == 'reprogrammé' ? 'selected' : '' }}>Reprogrammé</option>
                      <option value="annulé" {{ $atelier->etat_atelier == 'annulé' ? 'selected' : '' }}>Annulé</option>
                      <option value="en attente" {{ $atelier->etat_atelier == 'en attente' ? 'selected' : '' }}>En attente</option>
                  </select>
              </div>
              <div class="mb-3">
                  <label for="tarif_atelier_edit" class="form-label">Tarif</label>
                  <input type="number" name="tarif_atelier" id="tarif_atelier_edit" class="form-control" value="{{ $atelier->tarif_atelier }}" step="0.01" min="0" max="20" required>
              </div>
              <!-- Nouveau champ pour sélectionner le woofer via la table pivot "anime" -->
              <div class="mb-3">
                  <label for="id_woofer_edit" class="form-label">Woofer en charge</label>
                  <select name="id_woofer" id="id_woofer_edit" class="form-select" required>
                      <option value="">Sélectionnez un woofer</option>
                      @foreach ($woofers as $woofer)
                          @if ($woofer->personne)
                              <option value="{{ $woofer->id_woofer }}"
                                  {{ $atelier->woofers->isNotEmpty() && $atelier->woofers->first()->id_woofer == $woofer->id_woofer ? 'selected' : '' }}>
                                  {{ $woofer->personne->nom }} {{ $woofer->personne->prenom }}
                              </option>
                          @endif
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection
