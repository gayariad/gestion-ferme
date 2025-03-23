@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h1 class="fw-bold mb-4">Tableau de bord</h1>

    <!-- Indicateurs -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Chiffre d'affaires hebdomadaire</h5>
                    <p class="display-5">{{ number_format($chiffreAffaireHebdo, 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Chiffre d'affaires mensuel</h5>
                    <p class="display-5">{{ number_format($chiffreAffaireMensuel, 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Objectif mensuel</h5>
                    <p class="display-5">{{ number_format($objectifMensuel, 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des ventes récentes -->
    <div class="table-responsive mb-4">
        <h2 class="fw-bold mb-3">Ventes récentes</h2>
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Numéro de vente</th>
                    <th>Date</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $vente)
                    <tr>
                        <td>{{ $vente->id_vente }}</td>
                        <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</td>
                        <td>{{ $vente->quantite_produit }}</td>
                        <td>{{ number_format($vente->total_vente, 2, ',', ' ') }} €</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Aucune vente enregistrée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Graphique des ventes par produit -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2 class="fw-bold mb-3">Produits vendus</h2>
            <canvas id="ventesParProduitChart" height="200"></canvas>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
    <!-- Importation de Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique des ventes par produit
        const ctxProduit = document.getElementById('ventesParProduitChart').getContext('2d');
        const ventesParProduitChart = new Chart(ctxProduit, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($ventesParProduit as $vpp)
                        '{{ $vpp->nom_produit }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Quantité vendue',
                    data: [
                        @foreach($ventesParProduit as $vpp)
                            {{ $vpp->total_quantite }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
    </script>
@endsection
