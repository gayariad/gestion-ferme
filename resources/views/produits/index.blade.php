@extends('layouts.app')

@section('title', 'Stock des produits')

@section('content')
<link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet"/>
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Stock des produits</h2>
        <div>
            <button class="bg-red-200 px-4 py-2 rounded mr-2" onclick="toggleRemoveProductModal()">Retirer produit</button>
            <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="toggleModal(), showNewProductForm()">Ajouter produit</button>
        </div>
    </div>
    
    <div class="grid grid-cols-4 gap-4">
        @foreach ($produits as $produit)
        <div class="border p-4 rounded-lg shadow-md" onclick="openProductDetailModal({{ $produit->id_produit }}, '{{ $produit->nom_produit }}', '{{ $produit->categorie_produit }}', {{ $produit->prix_produit }}, {{ $produit->quantite_stock }})">
            <div class="h-32 bg-gray-300 flex items-center justify-center mb-2">[Image]</div>
            <h3 class="font-bold">{{ $produit->nom_produit }}</h3>
            <p class="text-sm text-gray-600">Prix unitaire: {{ $produit->prix_produit }} €</p>
            @if(isset($produit->categorie_produit))
                <p class="text-sm text-gray-600">Type: {{ $produit->categorie_produit }}</p>
            @endif
            <h3 class="font-bold">En Stock: {{ $produit->quantite_stock }}</h3>
            
            @if($produit->quantite_stock > 0)
                <p class="text-green-600 font-bold mt-2">✔️ En stock</p>
            @else
                <p class="text-red-600 font-bold mt-2">Stock epuisé</p>
            @endif
        </div>
        @endforeach
    </div>
    
    
</div>

<!-- Modal d'ajout de produit -->
<div id="productModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-1/3 relative">
        <button class="absolute top-2 right-2 text-gray-600" onclick="toggleModal()">✖</button>
        <h3 class="text-lg font-bold mb-4">Ajouter un produit</h3>
        <p>Ajoutez un nouveau produit : </p>
        
        <!-- Formulaire d'ajout d'un nouveau produit -->
        <div id="newProductForm" class="hidden mt-4">
            <h4 class="text-md font-bold">Nouveau produit</h4>
            <form action="{{ route('produits.ajouter') }}" method="POST">
            @csrf
            <input type="text" name="nom_produit" class="border p-2 w-full mb-2" placeholder="Nom du produit" required>
            <select name="categorie_produit" class="border p-2 w-full mb-2" required>
                <option value="">Sélectionner une catégorie</option>
                <option value="laitier">Laitier</option>
                <option value="agricole">Agricole</option>
                <option value="animalier">Animalier</option>
            </select>
            <input type="number" name="prix_produit" class="border p-2 w-full mb-2" placeholder="Prix" step="0.01" required>
            <input type="number" name="quantite_stock" class="border p-2 w-full mb-2" placeholder="Quantité initiale" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Ajouter</button>
            </form>
        </div>
        
    </div>
</div>

<div id="productDetailModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-1/3 relative">
        <button class="absolute top-2 right-2 text-gray-600" onclick="closeProductDetailModal()">✖</button>
        <h3 class="text-lg font-bold mb-4">Détails du produit</h3>
        <p><strong>Nom :</strong> <span id="modalProductName"></span></p>
        <p><strong>Catégorie :</strong> <span id="modalProductCategory"></span></p>
        <p><strong>Prix :</strong> <span id="modalProductPrice"></span> €</p>
        <p><strong>Stock actuel :</strong> <span id="modalProductStock"></span></p>
        
        <!-- Formulaire de modification du stock -->
        <h4 class="text-md font-bold mt-4">Modifier le stock</h4>
        <form action="{{ route('produits.modifierStock') }}" method="POST">
            @csrf
            <input type="hidden" name="id_produit" id="modalProductId">
            <input type="number" name="quantite_stock" id="stockUpdateValue" class="border p-2 w-full mb-2" placeholder="Quantité à ajouter ou retirer">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full">Mettre à jour</button>
        </form>
    </div>
</div>

<div id="removeProductModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-1/3 relative">
        <button class="absolute top-2 right-2 text-gray-600" onclick="toggleRemoveProductModal()">✖</button>
        <h3 class="text-lg font-bold mb-4">Retirer un produit</h3>
        <p>Sélectionnez le produit que vous souhaitez retirer :</p>
        <form action="{{ route('produits.supprimer') }}" method="POST">
            @csrf
            <select name="id_produit" class="border p-2 w-full mb-2" required>
                <option value="">Sélectionner un produit</option>
                @foreach ($produits as $produit)
                    <option value="{{ $produit->id_produit }}">{{ $produit->nom_produit }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded w-full">Supprimer</button>
        </form>
    </div>
</div>


<script>
    function toggleModal() {
        document.getElementById('productModal').classList.toggle('hidden');
    }
    function showNewProductForm() {
        document.getElementById('newProductForm').classList.remove('hidden');
    }
    function openProductDetailModal(id, name, category, price, stock) {
        document.getElementById('modalProductId').value = id;
        document.getElementById('modalProductName').innerText = name;
        document.getElementById('modalProductCategory').innerText = category;
        document.getElementById('modalProductPrice').innerText = price;
        document.getElementById('modalProductStock').innerText = stock;
        document.getElementById('productDetailModal').classList.remove('hidden');
    }
    
    function closeProductDetailModal() {
        document.getElementById('productDetailModal').classList.add('hidden');
    }
    function toggleRemoveProductModal() {
        document.getElementById('removeProductModal').classList.toggle('hidden');
    }
</script>

@endsection