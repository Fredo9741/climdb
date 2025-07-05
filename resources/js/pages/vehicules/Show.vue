<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ vehicule.marque }} {{ vehicule.modele }} - {{ vehicule.immatriculation }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Informations principales -->
                    <div class="lg:col-span-2">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du véhicule</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Marque</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ vehicule.marque }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Modèle</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ vehicule.modele }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Immatriculation</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ vehicule.immatriculation }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Année de fabrication</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ vehicule.annee_fabrication }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Kilométrage actuel</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatNumber(vehicule.kilometrage_actuel) }} km</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Type de carburant</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ vehicule.type_carburant }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Date d'acquisition</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(vehicule.date_acquisition) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                                  :class="getStatutClass(vehicule.statut_vehicule?.nom)">
                                                {{ vehicule.statut_vehicule?.nom || 'Non défini' }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>

                                <div v-if="vehicule.notes" class="mt-6">
                                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ vehicule.notes }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Historique des entretiens -->
                        <div v-if="vehicule.entretiens?.length > 0" class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique des entretiens</h3>
                                
                                <div class="space-y-4">
                                    <div v-for="entretien in vehicule.entretiens" :key="entretien.id" 
                                         class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ formatDate(entretien.date_entretien) }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ entretien.type_entretien }}
                                                </p>
                                                <p v-if="entretien.description" class="text-sm text-gray-700 mt-1">
                                                    {{ entretien.description }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ formatNumber(entretien.kilometrage) }} km
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Suivi kilométrage -->
                        <div v-if="vehicule.suivis_kilometrage?.length > 0" class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Suivi du kilométrage</h3>
                                
                                <div class="space-y-4">
                                    <div v-for="suivi in vehicule.suivis_kilometrage" :key="suivi.id" 
                                         class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ formatDate(suivi.date_releve) }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Par {{ suivi.user?.name || 'Inconnu' }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ formatNumber(suivi.kilometrage) }} km
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar avec actions -->
                    <div class="space-y-6">
                        <!-- Actions -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                                
                                <div class="space-y-3">
                                    <Link
                                        :href="route('vehicules.edit', vehicule.id)"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                                    >
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Modifier
                                    </Link>
                                    
                                    <Link
                                        :href="route('vehicules.index')"
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

                        <!-- Affectation actuelle -->
                        <div v-if="vehicule.affectations?.length > 0" class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Affectation actuelle</h3>
                                
                                <div class="space-y-2">
                                    <div v-for="affectation in vehicule.affectations" :key="affectation.id">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ affectation.user?.name || 'Non assigné' }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Depuis {{ formatDate(affectation.date_debut) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Âge</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ new Date().getFullYear() - vehicule.annee_fabrication }} ans
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Entretiens</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ vehicule.entretiens?.length || 0 }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Relevés km</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ vehicule.suivis_kilometrage?.length || 0 }}
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
    vehicule: Object,
})

const formatNumber = (number) => {
    return new Intl.NumberFormat('fr-FR').format(number)
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR')
}

const getStatutClass = (statut) => {
    switch (statut) {
        case 'Actif':
            return 'bg-green-100 text-green-800'
        case 'En maintenance':
            return 'bg-yellow-100 text-yellow-800'
        case 'Hors service':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}
</script>