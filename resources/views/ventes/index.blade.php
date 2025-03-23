@extends('layouts.app')

@section('content')
<div class="container my-4" x-data="venteData()">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Enregistrement des ventes</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVenteModal">
            + Nouvelle vente
        </button>
    </div>

    <!-- Tableau des ventes existantes -->
    <div class="table-responsive mb-5">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Numéro de vente</th>
                    <th>Date</th>
                    <th>Quantité totale</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $vente)
                    <tr style="cursor:pointer">
                        <td>{{ $vente->id_vente }}</td>
                        <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</td>
                        <td>{{ $vente->quantite_produit }}</td>
                        <td>{{ number_format($vente->total_vente, 2, ',', ' ') }} €</td>
                        <td>
                            <!-- Afficher Détail commande et la modifié  -->
                            <a href="{{ route('ventes.show', $vente->id_vente) }}" class="btn btn-sm btn-warning">Details</a>
                            <!-- Bouton Annuler (supprimer) -->
                            <form action="{{ route('ventes.destroy', $vente->id_vente) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment annuler cette vente ?');">
                                @csrf
                                @method('DELETE')
                                @if(auth()->user()->role_id == 1)
                                <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucune vente enregistrée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal d'ajout de vente -->
    <div class="modal fade" id="addVenteModal" tabindex="-1" aria-labelledby="addVenteModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form action="{{ route('ventes.store') }}" method="POST">
              @csrf
              <div class="modal-header">
                  <h5 class="modal-title" id="addVenteModalLabel">Créer une nouvelle vente</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <!-- Date de vente -->
                <<div class="mb-3">
                    <label for="date_vente" class="form-label">Date de la vente</label>
                    <input type="text" name="date_vente" id="date_vente" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                </div>
                  <!-- Lignes de produits -->
                  <div class="mb-3">
                      <h6>Produits achetés</h6>
                      <template x-for="(row, index) in rows" :key="index">
                          <div class="row g-2 mb-2 align-items-end">
                              <div class="col-6">
                                  <label class="form-label">Produit</label>
                                  <select class="form-select" 
                                          x-model="row.id_produit" 
                                          :name="'produits[' + index + '][id_produit]'" 
                                          @change="updateLine(index, $event)">
                                      <option value="">Choisir un produit</option>
                                      @foreach($produits as $prod)
                                          <option value="{{ $prod->id_produit }}" data-prix="{{ $prod->prix_produit }}" data-stock="{{ $prod->quantite_stock }}">
                                              {{ $prod->nom_produit }} (Stock: {{ $prod->quantite_stock }})
                                          </option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="col-3">
                                  <label class="form-label">Quantité</label>
                                  <input type="number" min="1" class="form-control" 
                                         x-model.number="row.quantite" 
                                         :name="'produits[' + index + '][quantite]'" 
                                         @input="updateLine(index, $event)">
                              </div>
                              <div class="col-2">
                                  <label class="form-label">Prix</label>
                                  <input type="text" class="form-control" 
                                         :value="row.prix.toFixed(2) + ' €'" 
                                         readonly>
                              </div>
                              <div class="col-1 text-end">
                                  <button type="button" class="btn btn-danger" @click="removeRow(index)">-</button>
                              </div>
                          </div>
                      </template>
                      <button type="button" class="btn btn-secondary mt-2" @click="addRow">+ Ajouter un produit</button>
                  </div>

                  <!-- Total de la vente -->
                  <div class="mt-3">
                      <h5>Total : <span x-text="total.toFixed(2)"></span> €</h5>
                  </div>
                  <input type="hidden" name="total_vente" :value="total.toFixed(2)">
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" class="btn btn-primary">Enregistrer la vente</button>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.3/dist/cdn.min.js"></script>
<script>
function venteData() {
    return {
        rows: [
            { id_produit: '', quantite: 1, prix: 0 }
        ],
        get total() {
            return this.rows.reduce((sum, row) => sum + row.prix, 0);
        },
        addRow() {
            this.rows.push({ id_produit: '', quantite: 1, prix: 0 });
        },
        removeRow(index) {
            this.rows.splice(index, 1);
        },
        updateLine(index, event) {
            let row = this.rows[index];
            let select = event.target.closest('.row').querySelector('select');
            let option = select.options[select.selectedIndex];
            let prixProduit = parseFloat(option.getAttribute('data-prix') || 0);
            let stockProduit = parseInt(option.getAttribute('data-stock') || 0);
            if (row.quantite > stockProduit) {
                alert('Stock insuffisant !');
                row.quantite = stockProduit;
            }
            row.prix = prixProduit * row.quantite;
        }
    }
}
</script>
@endsection
