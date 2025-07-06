<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestion des Véhicules
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Liste des Véhicules
                                </h3>
                                <span class="ml-2 text-sm text-gray-500">
                                    ({{ vehicules.length }} véhicules)
                                </span>
                            </div>
                            <Link
                                :href="route('vehicules.create')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nouveau Véhicule
                            </Link>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Véhicule
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Immatriculation
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kilométrage
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Affectation
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statistiques
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="vehicule in vehicules" :key="vehicule.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ vehicule.marque }} {{ vehicule.modele }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ vehicule.type_carburant }} • {{ vehicule.annee_fabrication }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ vehicule.immatriculation }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ formatNumber(vehicule.kilometrage_actuel) }} km
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                              :class="getStatutClass(vehicule.statut_vehicule?.nom)">
                                            {{ vehicule.statut_vehicule?.nom || 'Non défini' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div v-if="activeAffectation(vehicule)">
                                            {{ activeAffectation(vehicule).user?.name || 'Non assigné' }}
                                        </div>
                                        <div v-else class="text-gray-500">Non assigné</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex space-x-4">
                                            <span>{{ vehicule.entretiens_count || 0 }} entretiens</span>
                                            <span>{{ vehicule.suivis_kilometrage_count || 0 }} relevés</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <Link
                                                :href="route('vehicules.show', vehicule.id)"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded"
                                                title="Voir les détails"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </Link>
                                            <Link
                                                :href="route('vehicules.edit', vehicule.id)"
                                                class="text-yellow-600 hover:text-yellow-900 p-1 rounded"
                                                title="Modifier"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </Link>
                                            <button
                                                @click="deleteVehicule(vehicule)"
                                                class="text-red-600 hover:text-red-900 p-1 rounded"
                                                title="Supprimer"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="vehicules.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun véhicule</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau véhicule.</p>
                        <div class="mt-6">
                            <Link
                                :href="route('vehicules.create')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                            >
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nouveau Véhicule
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    vehicules: Array,
})

const formatNumber = (number) => {
    return new Intl.NumberFormat('fr-FR').format(number)
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

const deleteVehicule = (vehicule) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le véhicule ${vehicule.marque} ${vehicule.modele} ?`)) {
        router.delete(route('vehicules.destroy', vehicule.id))
    }
}

/**
 * Retourne l'affectation active (en cours) du véhicule ou null.
 */
const activeAffectation = (vehicule) => {
    if (!vehicule.affectations || vehicule.affectations.length === 0) {
        return null
    }

    const now = new Date()
    // On cherche une affectation dont la date_debut est passée et la date_fin dans le futur ou nulle.
    const active = vehicule.affectations.find((a) => {
        const debut = new Date(a.date_debut)
        const fin = a.date_fin ? new Date(a.date_fin) : null
        return debut <= now && (!fin || fin >= now)
    })

    // Si aucune affectation courante, on retourne la plus récente (première) pour info.
    return active || vehicule.affectations[0]
}
</script>