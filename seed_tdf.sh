#!/bin/bash

echo "🚀 Seeding données TDF..."

echo "1️⃣  Client TDF et types de base..."
php artisan db:seed --class=TdfSeeder

echo "2️⃣  Sites TDF..."
php artisan db:seed --class=TdfSitesSeeder

echo "3️⃣  Modèles d'équipements TDF..."
php artisan db:seed --class=TdfModelesSeeder

echo "4️⃣  Équipements TDF..."
php artisan db:seed --class=TdfEquipementsSeeder

echo "✅ Seeding TDF terminé !"

echo ""
echo "📊 Résumé des données TDF :"
php artisan tinker --execute="
\$tdf = App\Models\Client::where('nom', 'TDF')->withCount(['sites', 'sites.equipements'])->first();
if (\$tdf) {
    echo 'Client: ' . \$tdf->nom . PHP_EOL;
    echo 'Sites: ' . \$tdf->sites_count . PHP_EOL;
    echo 'Équipements: ' . \$tdf->sites->sum('equipements_count') . PHP_EOL;
} else {
    echo 'Client TDF non trouvé' . PHP_EOL;
}
"
