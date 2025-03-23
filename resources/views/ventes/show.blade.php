@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h1 class="fw-bold mb-4">Détails de la vente n°{{ $vente->id_vente }}</h1>
    <p><strong>Date de vente :</strong> {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</p>
    <p><strong>Quantité totale :</strong> {{ $vente->quantite_produit }}</p>
    <p><strong>Total :</strong> {{ number_format($vente->total_vente, 2, ',', ' ') }} €</p>

    <!-- Liste des produits achetés -->
    <h2 class="fw-bold mb-3">Produits achetés</h2>
    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Prix total</th>
                    <th>Stock restant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vente->produits as $prod)
                    <tr>
                        <td>{{ $prod->nom_produit }}</td>
                        <td>{{ $prod->pivot->quantite_produit }}</td>
                        <td>{{ number_format($prod->pivot->prix_unitaire, 2, ',', ' ') }} €</td>
                        <td>
                            {{ number_format($prod->pivot->quantite_produit * $prod->pivot->prix_unitaire, 2, ',', ' ') }}
                            €
                        </td>
                        <td>{{ $prod->quantite_stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Boutons d'action -->
    @if(auth()->user()->role_id == 1)
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editVenteModal">
        Modifier la vente
    </button>
    @endif
    <a href="{{ route('ventes.index') }}" class="btn btn-secondary">Retour aux ventes</a>
    
</div>

<!-- Modal de modification de la vente -->
<div class="modal fade" id="editVenteModal" tabindex="-1" aria-labelledby="editVenteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('ventes.update', $vente->id_vente) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editVenteModalLabel">Modifier la vente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <!-- Affichage de la date de vente (non modifiable) -->
          <div class="mb-3">
            <label class="form-label">Date de vente</label>
            <input type="date" class="form-control" value="{{ $vente->date_vente }}" disabled>
          </div>

          <!-- Section produits déjà associés -->
          <h6 class="fw-bold">Produits déjà ajoutés</h6>
          <div id="existingProducts">
            @foreach ($vente->produits as $prod)
              <div class="row mb-3 align-items-end existing-product-row">
                <div class="col-6">
                  <label class="form-label">{{ $prod->nom_produit }}</label>
                  <input type="hidden" name="produits[{{ $prod->id_produit }}][produit_id]" value="{{ $prod->id_produit }}">
                </div>
                <div class="col-3">
                  <label class="form-label">Quantité</label>
                  <input type="number" class="form-control"
                         name="produits[{{ $prod->id_produit }}][quantite]"
                         value="{{ $prod->pivot->quantite_produit }}"
                         min="1" required>
                </div>
                <div class="col-3">
                  <button type="button" class="btn btn-danger" onclick="removeExistingProduct(this)">Supprimer</button>
                </div>
              </div>
            @endforeach
          </div>

          <hr>

          <!-- Section pour ajouter de nouveaux produits -->
          <h6 class="fw-bold">Ajouter de nouveaux produits</h6>
          <div id="newProducts">
            <!-- Ligne par défaut (facultative, vous pouvez partir de vide) -->
            <div class="row mb-3 align-items-end new-product-row">
              <div class="col-6">
                <label class="form-label">Produit</label>
                <select class="form-select" name="nouveaux_produits[][produit_id]" required>
                  <option value="">Sélectionner un produit</option>
                  @foreach ($produits as $p)
                    <option value="{{ $p->id_produit }}">{{ $p->nom_produit }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-3">
                <label class="form-label">Quantité</label>
                <input type="number" class="form-control" name="nouveaux_produits[][quantite]" value="1" min="1" required>
              </div>
              <div class="col-3">
                <button type="button" class="btn btn-danger" onclick="removeNewProduct(this)">Supprimer</button>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-secondary" onclick="addNewProductRow()">+ Ajouter un nouveau produit</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-success">Enregistrer</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  // Supprimer une ligne de produit existant (la ligne n'étant plus soumise, le produit sera détaché côté serveur)
  function removeExistingProduct(button) {
    button.closest('.existing-product-row').remove();
  }

  // Supprimer une ligne de nouveau produit
  function removeNewProduct(button) {
    button.closest('.new-product-row').remove();
  }

  // Ajouter une nouvelle ligne pour un nouveau produit
  function addNewProductRow() {
    const container = document.getElementById('newProducts');
    const newRow = document.createElement('div');
    newRow.classList.add('row', 'mb-3', 'align-items-end', 'new-product-row');
    newRow.innerHTML = `
      <div class="col-6">
        <label class="form-label">Produit</label>
        <select class="form-select" name="nouveaux_produits[][produit_id]" required>
          <option value="">Sélectionner un produit</option>
          @foreach ($produits as $p)
            <option value="{{ $p->id_produit }}">{{ $p->nom_produit }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-3">
        <label class="form-label">Quantité</label>
        <input type="number" class="form-control" name="nouveaux_produits[][quantite]" value="1" min="1" required>
      </div>
      <div class="col-3">
        <button type="button" class="btn btn-danger" onclick="removeNewProduct(this)">Supprimer</button>
      </div>
    `;
    container.appendChild(newRow);
    console.log("Nouvelle ligne ajoutée", newRow);
  }
</script>


@endsection
