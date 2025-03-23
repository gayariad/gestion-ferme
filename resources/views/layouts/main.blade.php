<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    
    <!-- Inclusion du fichier CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Ajout de Vite pour Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Inclusion de l'en-tête (Sidebar et Navbar) -->

    <!-- Contenu principal (home-content avec dashboard) -->
    <div class="main-container">
        <!-- La sidebar est déjà incluse dans entete -->
        
        <div class="content-container">
            <!-- Ici tu inclues ton contenu (dashboard, etc.) -->
            @yield('content') 
        </div>
    </div>

    <!-- Inclusion du pied de page -->
</body>
</html>
