<template>
    <AuthenticatedLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ technicien.name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">Détails du technicien</p>
                    </div>
                    <div class="flex space-x-2">
                        <Link 
                            :href="route('techniciens.edit', technicien.id)"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </Link>
                        <Link 
                            :href="route('techniciens.index')"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Retour
                        </Link>
                    </div>
                </div>

                <!-- Informations du technicien -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations personnelles</h2>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Nom :</span>
                                <span class="ml-2 text-sm text-gray-900">{{ technicien.name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Email :</span>
                                <span class="ml-2 text-sm text-gray-900">{{ technicien.email }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Téléphone :</span>
                                <span class="ml-2 text-sm text-gray-900">{{ technicien.telephone || 'Non renseigné' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Spécialité :</span>
                                <span class="ml-2 text-sm text-gray-900">{{ technicien.specialite || 'Généraliste' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Statut :</span>
                                <span :class="technicien.actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                      class="ml-2 px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ technicien.actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistiques</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ stats.interventions_total }}</div>
                                <div class="text-sm text-gray-600">Interventions totales</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ stats.interventions_en_cours }}</div>
                                <div class="text-sm text-gray-600">En cours</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ stats.interventions_ce_mois }}</div>
                                <div class="text-sm text-gray-600">Ce mois</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ stats.bouteilles_affectees }}</div>
                                <div class="text-sm text-gray-600">Bouteilles affectées</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interventions récentes -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Interventions récentes</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Équipement</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Site</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="intervention in technicien.interventions" :key="intervention.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ new Date(intervention.created_at).toLocaleDateString('fr-FR') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ intervention.equipement?.nom || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ intervention.site?.nom || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="getStatusColor(intervention.statut)" 
                                              class="px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ intervention.statut }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="technicien.interventions.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Aucune intervention récente
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bouteilles de gaz affectées -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Bouteilles de gaz affectées</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="bouteille in technicien.bouteilles_gaz" :key="bouteille.id" 
                             class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ bouteille.numero_serie }}</h3>
                                    <p class="text-sm text-gray-600">{{ bouteille.type_gaz?.nom || 'N/A' }}</p>
                                </div>
                                <span :class="bouteille.statut_bouteille?.couleur || 'bg-gray-100 text-gray-800'" 
                                      class="px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ bouteille.statut_bouteille?.nom || 'N/A' }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between text-sm">
                                    <span>Niveau</span>
                                    <span>{{ bouteille.niveau_gaz }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div :class="getNiveauColor(bouteille.niveau_gaz)" 
                                         class="h-2 rounded-full" 
                                         :style="{ width: bouteille.niveau_gaz + '%' }">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="technicien.bouteilles_gaz.length === 0" 
                             class="col-span-full text-center text-gray-500 py-8">
                            Aucune bouteille de gaz affectée
                        </div>
                    </div>
                </div>

                <!-- Véhicules affectés -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Véhicules affectés</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="affectation in technicien.affectations_vehicules" :key="affectation.id" 
                             class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ affectation.vehicule?.marque }} {{ affectation.vehicule?.modele }}</h3>
                                    <p class="text-sm text-gray-600">{{ affectation.vehicule?.immatriculation }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Affecté le {{ new Date(affectation.date_debut).toLocaleDateString('fr-FR') }}
                                    </p>
                                </div>
                                <span :class="affectation.statut_affectation?.couleur || 'bg-green-100 text-green-800'" 
                                      class="px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ affectation.statut_affectation?.nom || 'Actif' }}
                                </span>
                            </div>
                        </div>
                        <div v-if="technicien.affectations_vehicules.length === 0" 
                             class="col-span-full text-center text-gray-500 py-8">
                            Aucun véhicule affecté
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

export default {
    name: 'TechniciensShow',
    components: {
        AuthenticatedLayout,
        Link,
    },
    props: {
        technicien: {
            type: Object,
            required: true,
        },
        stats: {
            type: Object,
            required: true,
        },
    },
    methods: {
        getStatusColor(statut) {
            const colors = {
                'en_cours': 'bg-yellow-100 text-yellow-800',
                'terminee': 'bg-green-100 text-green-800',
                'en_attente': 'bg-blue-100 text-blue-800',
                'annulee': 'bg-red-100 text-red-800',
            };
            return colors[statut] || 'bg-gray-100 text-gray-800';
        },
        getNiveauColor(niveau) {
            if (niveau > 70) return 'bg-green-500';
            if (niveau > 30) return 'bg-yellow-500';
            return 'bg-red-500';
        },
    },
};
</script>