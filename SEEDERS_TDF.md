# Seeders TDF - Documentation

## 📋 Vue d'ensemble

Les seeders TDF permettent d'insérer toutes les données TDF (Télédiffusion de France) dans la base de données Laravel de manière structurée et cohérente.

## 🗂️ Structure des Seeders

### 1. TdfSeeder
**Fichier:** `database/seeders/TdfSeeder.php`
**Responsabilité:** 
- Création du client TDF
- Création des types de gaz (R410A, R407C, R32) avec leurs valeurs GWP
- Création du type d'équipement "Climatiseur"

### 2. TdfSitesSeeder
**Fichier:** `database/seeders/TdfSitesSeeder.php`
**Responsabilité:**
- Création des 33 sites TDF à travers La Réunion
- Chaque site est lié au client TDF

### 3. TdfModelesSeeder
**Fichier:** `database/seeders/TdfModelesSeeder.php`
**Responsabilité:**
- Création des modèles d'équipements de climatisation
- Marques: AIRWELL, CARRIER, WESTPOINT, MDV, ZENITH AIR, GALAXIE, etc.
- Association avec les types de gaz appropriés

### 4. TdfEquipementsSeeder
**Fichier:** `database/seeders/TdfEquipementsSeeder.php`
**Responsabilité:**
- Création d'équipements d'exemple
- 1 à 3 équipements par site (premiers 10 sites)
- Numéros de série automatiques
- États variés (actif, maintenance, panne)

## 🚀 Utilisation

### Méthode 1: Seeders individuels
```bash
# 1. Client TDF et types de base
php artisan db:seed --class=TdfSeeder

# 2. Sites TDF
php artisan db:seed --class=TdfSitesSeeder

# 3. Modèles d'équipements
php artisan db:seed --class=TdfModelesSeeder

# 4. Équipements d'exemple
php artisan db:seed --class=TdfEquipementsSeeder
```

### Méthode 2: Script automatique
```bash
# Exécuter le script de seeding complet
./seed_tdf.sh
```

### Méthode 3: Seeding complet de la base
```bash
# Inclut tous les seeders (y compris TDF)
php artisan db:seed
```

## 📊 Données créées

Après exécution complète des seeders TDF :

- **1 Client** : TDF
- **33 Sites** : Répartis à travers La Réunion
- **3 Types de gaz** : R410A (GWP: 2088), R407C (GWP: 1774), R32 (GWP: 675)
- **15+ Modèles** : Diverses marques et références
- **17+ Équipements** : Exemples répartis sur 10 sites

## 🔧 Prérequis

1. **Migration GWP** : La colonne `gwp` doit être ajoutée à la table `types_gaz`
   ```bash
   php artisan migrate
   ```

2. **Modèles Laravel** : Tous les modèles doivent être présents :
   - Client
   - Site
   - TypeGaz
   - TypeEquipement
   - Modele
   - Equipement

## 🎯 Utilisation dans l'interface

Une fois les seeders exécutés, vous pouvez :

1. **Voir le client TDF** dans la liste des clients avec ses 33 sites
2. **Explorer les sites TDF** et leurs équipements
3. **Consulter les modèles** créés avec leurs spécifications techniques
4. **Gérer les équipements** avec leurs données complètes

## 🔄 Re-seeding

Les seeders utilisent `updateOrCreate()` pour éviter les doublons. Vous pouvez les exécuter plusieurs fois sans problème.

## 📝 Personnalisation

Pour ajouter plus de données TDF :

1. **Modifiez les arrays** dans les seeders correspondants
2. **Ajoutez de nouveaux modèles** dans `TdfModelesSeeder`
3. **Créez plus d'équipements** en modifiant `TdfEquipementsSeeder`

## ⚠️ Notes importantes

- Les données sont basées sur le document TDF réel fourni
- Les numéros de série sont générés automatiquement (format: TDF0001, TDF0002, etc.)
- Les dates d'installation sont simulées (entre 6 mois et 3 ans dans le passé)
- Les états des équipements sont randomisés pour les tests
