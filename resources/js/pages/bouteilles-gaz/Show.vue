<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Bouteille {{ bouteille.numero_serie }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Informations principales -->
                    <div class="lg:col-span-2">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de la bouteille</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Numéro de série</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ bouteille.numero_serie }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Type de gaz</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ bouteille.type_gaz?.nom || 'Non défini' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Capacité</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ bouteille.capacite_kg }} kg</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Poids actuel</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ bouteille.poids_actuel_kg }} kg</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Niveau de remplissage</dt>
                                        <dd class="mt-1">
                                            <div class="flex items-center">
                                                <div class="flex-grow bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="h-2 rounded-full" 
                                                         :class="getNiveauClass(bouteille.pourcentage_remplissage)"
                                                         :style="{ width: bouteille.pourcentage_remplissage + '%' }"></div>
                                                </div>
                                                <span class="text-sm text-gray-900">
                                                    {{ Math.round(bouteille.pourcentage_remplissage || 0) }}%
                                                </span>
                                            </div>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                                  :class="getStatutClass(bouteille.statut_bouteille?.nom)">
                                                {{ bouteille.statut_bouteille?.nom || 'Non défini' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Technicien assigné</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ bouteille.user?.name || 'Aucun' }}
                                        </dd>
                                    </div>
                                </div>

                                <div v-if="bouteille.notes" class="mt-6">
                                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ bouteille.notes }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Historique des mouvements -->
                        <div v-if="bouteille.mouvements?.length > 0" class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique des mouvements</h3>
                                
                                <div class="space-y-4">
                                    <div v-for="mouvement in bouteille.mouvements" :key="mouvement.id" 
                                         class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ formatDate(mouvement.date_mouvement) }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ mouvement.type_mouvement_libelle }} - {{ mouvement.user?.name }}
                                                </p>
                                                <p v-if="mouvement.observations" class="text-sm text-gray-700 mt-1">
                                                    {{ mouvement.observations }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-medium" 
                                                      :class="mouvement.type_mouvement === 'entrée' ? 'text-green-600' : 'text-red-600'">
                                                    {{ mouvement.type_mouvement === 'entrée' ? '+' : '-' }}{{ mouvement.quantite_kg }} kg
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Actions -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                                
                                <div class="space-y-3">
                                    <Link
                                        :href="route('bouteilles-gaz.edit', bouteille.id)"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                                    >
                                        Modifier
                                    </Link>
                                    
                                    <Link
                                        :href="route('bouteilles-gaz.index')"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                                    >
                                        Retour à la liste
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Mouvements</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ bouteille.mouvements?.length || 0 }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Quantité restante</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ bouteille.quantite_restante || bouteille.poids_actuel_kg }} kg
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">État</span>
                                        <span class="text-sm font-medium" 
                                              :class="bouteille.is_vide ? 'text-red-600' : bouteille.is_pleine ? 'text-green-600' : 'text-yellow-600'">
                                            {{ bouteille.is_vide ? 'Vide' : bouteille.is_pleine ? 'Pleine' : 'Partiellement remplie' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    bouteille: Object,
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR')
}

const getNiveauClass = (pourcentage) => {
    if (pourcentage >= 70) return 'bg-green-600'
    if (pourcentage >= 30) return 'bg-yellow-600'
    return 'bg-red-600'
}

const getStatutClass = (statut) => {
    switch (statut) {
        case 'Disponible':
            return 'bg-green-100 text-green-800'
        case 'En service':
            return 'bg-blue-100 text-blue-800'
        case 'Maintenance':
            return 'bg-yellow-100 text-yellow-800'
        case 'Hors service':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}
</script>