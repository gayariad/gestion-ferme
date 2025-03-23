# install.ps1

Write-Host "🚀 Lancement de l'installation du projet Laravel..." -ForegroundColor Cyan

# 1. Vérifie PHP
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "❌ PHP n'est pas installé ou pas dans le PATH." -ForegroundColor Red
    exit 1
}

# 2. Vérifie Composer
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Composer n'est pas installé ou pas dans le PATH." -ForegroundColor Red
    exit 1
}

# 3. Installer les dépendances via Composer
Write-Host "📦 Installation des dépendances via Composer..."
composer install

# 4. Copie du .env
if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "✅ Fichier .env copié depuis .env.example"
}

# 5. Générer la clé de l'application
Write-Host "🔑 Génération de la clé Laravel..."
php artisan key:generate

# 6. Migrer la base de données
Write-Host "🧩 Migration de la base de données..."
php artisan migrate

# 7. Lancer le serveur
Write-Host "🚀 Tout est prêt ! Le serveur Laravel démarre..."
Start-Process "http://localhost:8000"
php artisan serve
