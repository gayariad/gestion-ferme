@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Gestion des ateliers</h1>
        <!-- Bouton pour ouvrir le modal d'ajout d'atelier -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAtelierModal">
            Ajouter un atelier
        </button>
    </div>

    <!-- Modal d'ajout d'atelier -->
    <div class="modal fade" id="addAtelierModal" tabindex="-1" aria-labelledby="addAtelierModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('ateliers.store') }}" method="POST">
              @csrf
              <div class="modal-header">
                  <h5 class="modal-title" id="addAtelierModalLabel">Ajouter un atelier</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="jour_atelier" class="form-label">Date de l'atelier</label>
                      <input type="date" name="jour_atelier" id="jour_atelier" class="form-control" required>
                  </div>
                  <div class="mb-3">
                      <label for="thematique_atelier" class="form-label">Thématique</label>
                      <input type="text" name="thematique_atelier" id="thematique_atelier" class="form-control" required>
                  </div>
                  <div class="mb-3">
                      <label for="etat_atelier" class="form-label">État</label>
                      <select name="etat_atelier" id="etat_atelier" class="form-select" required>
                          <option value="validé">Validé</option>
                          <option value="reprogrammé">Reprogrammé</option>
                          <option value="annulé">Annulé</option>
                          <option value="en attente">En attente</option>
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="tarif_atelier" class="form-label">Tarif</label>
                      <input type="number" name="tarif_atelier" id="tarif_atelier" class="form-control" step="0.01" min="0" max="20" required>
                  </div>
                  <div class="mb-3">
                      <label for="id_woofer" class="form-label">Woofer en charge</label>
                      <select name="id_woofer" id="id_woofer" class="form-select" required>
                          <option value="">Sélectionnez un woofer</option>
                          @foreach ($woofers as $woofer)
                              @if ($woofer->personne)
                                  <option value="{{ $woofer->id_woofer }}">
                                      {{ $woofer->personne->nom }} {{ $woofer->personne->prenom }}
                                  </option>
                              @endif
                          @endforeach
                      </select>
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

    <!-- Tableau listant les ateliers -->
    <div class="table-responsive mb-5">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Thématique</th>
                    <th>Woofer en charge</th>
                    <th>État</th>
                    <th>Tarif</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ateliers as $atelier)
                    <tr style="cursor:pointer" onclick="window.location='{{ route('ateliers.show', $atelier->id_atelier) }}'">
                        <td>{{ \Carbon\Carbon::parse($atelier->jour_atelier)->format('d/m/Y') }}</td>
                        <td>{{ $atelier->thematique_atelier }}</td>
                        <td>
                            @if($atelier->woofers->isNotEmpty())
                                {{ $atelier->woofers->first()->personne->nom }} {{ $atelier->woofers->first()->personne->prenom }}
                            @else
                                ---
                            @endif
                        </td>
                        <td>{{ $atelier->etat_atelier }}</td>
                        <td>{{ $atelier->tarif_atelier }} €</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucun atelier enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Calendrier affichant les ateliers du mois -->
    <h2 class="fw-bold mb-3">Ateliers du mois</h2>
    <div id="calendar" style="min-height: 500px;"></div>
</div>
@endsection

@section('extra-head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
@endsection

@section('extra-js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                events: [
                    @foreach ($ateliers as $atelier)
                    {
                        title: '{{ $atelier->thematique_atelier }}',
                        start: '{{ $atelier->jour_atelier }}',
                    },
                    @endforeach
                ]
            });
            calendar.render();
        });
    </script>
@endsection
