<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Mouvement de gaz du {{ formatDateTime(mouvement.date_mouvement) }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Informations principales -->
                    <div class="lg:col-span-2">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Détails du mouvement</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Type de mouvement</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full"
                                                  :class="mouvement.type_mouvement === 'entrée' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                                {{ mouvement.type_mouvement_libelle || mouvement.type_mouvement }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Quantité</dt>
                                        <dd class="mt-1 text-lg font-medium"
                                            :class="mouvement.type_mouvement === 'entrée' ? 'text-green-600' : 'text-red-600'">
                                            {{ mouvement.type_mouvement === 'entrée' ? '+' : '-' }}{{ mouvement.quantite_kg }} kg
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Date et heure</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(mouvement.date_mouvement) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Type de gaz</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ mouvement.type_gaz?.nom || 'Non défini' }}</dd>
                                    </div>
                                </div>

                                <div v-if="mouvement.observations" class="mt-6">
                                    <dt class="text-sm font-medium text-gray-500">Observations</dt>
                                    <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                        {{ mouvement.observations }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <!-- Informations équipement -->
                        <div class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Équipement concerné</h3>
                                
                                <div v-if="mouvement.equipement" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nom de l'équipement</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ mouvement.equipement.nom }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Numéro de série</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ mouvement.equipement.numero_serie || 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Client</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ mouvement.equipement.client?.nom || 'Client non défini' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Localisation</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ mouvement.equipement.localisation_precise || 'Non précisée' }}
                                        </dd>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500 italic">
                                    Aucune information d'équipement disponible
                                </div>
                            </div>
                        </div>

                        <!-- Intervention associée -->
                        <div v-if="mouvement.intervention" class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Intervention associée</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Numéro d'intervention</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <Link :href="route('interventions.show', mouvement.intervention.id)" 
                                                  class="text-blue-600 hover:text-blue-900">
                                                #{{ mouvement.intervention.id }}
                                            </Link>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Site</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ mouvement.intervention.site?.nom || 'Site non défini' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Date prévue</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ formatDate(mouvement.intervention.date_prevue) }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Type d'intervention</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ mouvement.intervention.type_intervention || 'Non précisé' }}
                                        </dd>
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
                                        :href="route('mouvements-gaz.edit', mouvement.id)"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                                    >
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Modifier
                                    </Link>
                                    
                                    <Link
                                        :href="route('mouvements-gaz.index')"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                                    >
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        Retour à la liste
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Technicien responsable -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Technicien responsable</h3>
                                
                                <div v-if="mouvement.user" class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ mouvement.user.name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ mouvement.user.email || 'Email non renseigné' }}
                                        </p>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500 italic">
                                    Aucun technicien assigné
                                </div>
                            </div>
                        </div>

                        <!-- Résumé du mouvement -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Résumé</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">ID du mouvement</span>
                                        <span class="text-sm font-medium text-gray-900">#{{ mouvement.id }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Quantité signée</span>
                                        <span class="text-sm font-medium" 
                                              :class="mouvement.type_mouvement === 'entrée' ? 'text-green-600' : 'text-red-600'">
                                            {{ mouvement.quantite_signee || (mouvement.type_mouvement === 'entrée' ? '+' : '-') + mouvement.quantite_kg }} kg
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Date d'enregistrement</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ formatDate(mouvement.created_at) }}
                                        </span>
                                    </div>
                                    <div v-if="mouvement.updated_at !== mouvement.created_at" class="flex justify-between">
                                        <span class="text-sm text-gray-500">Dernière modification</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ formatDate(mouvement.updated_at) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Liens rapides -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Liens rapides</h3>
                                
                                <div class="space-y-2">
                                    <Link v-if="mouvement.equipement" 
                                          :href="route('equipements.show', mouvement.equipement.id)"
                                          class="block text-sm text-blue-600 hover:text-blue-900">
                                        → Voir l'équipement
                                    </Link>
                                    <Link v-if="mouvement.intervention" 
                                          :href="route('interventions.show', mouvement.intervention.id)"
                                          class="block text-sm text-blue-600 hover:text-blue-900">
                                        → Voir l'intervention
                                    </Link>
                                    <Link :href="route('mouvements-gaz.create')"
                                          class="block text-sm text-blue-600 hover:text-blue-900">
                                        → Créer un nouveau mouvement
                                    </Link>
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
    mouvement: Object,
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR')
}

const formatDateTime = (date) => {
    return new Date(date).toLocaleString('fr-FR')
}
</script>