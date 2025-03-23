<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tableau de Bord')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-yellow-200 p-4 min-h-screen fixed">
        <h1 class="text-xl font-bold mb-6">🐔 Cocorico</h1>
        <nav>
            <ul class="space-y-4">
                <li><a href="{{ route('dashboard') }}" class="block p-2 hover:bg-yellow-300">Tableau de bord</a></li>
                <li><a href="{{ route('produits.index') }}" class="block p-2 hover:bg-yellow-300">Produits</a></li>
                <li><a href="{{ route('ateliers.index') }}" class="block p-2 hover:bg-yellow-300">Ateliers</a></li>
                <li><a href="{{ route('woofers.index') }}" class="block p-2 hover:bg-yellow-300">Woofers</a></li>
                <li><a href="{{ route('ventes.index') }}" class="block p-2 hover:bg-yellow-300">Ventes</a></li>
            </ul>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <div class="ml-64 flex-1">
        <!-- Navbar -->
        <nav class="bg-yellow-300 p-4 flex justify-between">
            <h2 class="text-xl font-bold">@yield('title')</h2>
            <div>
                <!-- Lien Profil (optionnel) -->
                {{-- <a href="#" class="p-2 hover:bg-yellow-400">👤 Profil</a> --}}
                
                <!-- Déconnexion (formulaire POST) -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="p-2 hover:bg-yellow-400">🚪 Déconnexion</button>
                </form>
            </div>
        </nav>
        
        <!-- Contenu principal -->
        <main class="p-8">
            @yield('content')
        </main>
    </div>
        <!-- JS Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    @yield('extra-js')
</body>

</body>
</html>
