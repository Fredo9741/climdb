<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Détails de l'équipement</h2>
              <div class="space-x-2">
                <Link
                  :href="route('equipements.edit', equipement.id)"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Modifier
                </Link>
                <Link
                  :href="route('equipements.index')"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                  Retour
                </Link>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Nom de l'équipement</label>
                  <p class="mt-1 text-sm text-gray-900">{{ equipement.nom }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Numéro de série</label>
                  <p class="mt-1 text-sm text-gray-900">{{ equipement.numero_serie }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Site</label>
                  <p class="mt-1 text-sm text-gray-900">{{ equipement.site?.nom }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Client</label>
                                          <p class="mt-1 text-sm text-gray-900">{{ equipement.site?.client?.nom }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Modèle</label>
                  <p class="mt-1 text-sm text-gray-900">{{ equipement.modele?.nom }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Type d'équipement</label>
                  <p class="mt-1 text-sm text-gray-900">{{ equipement.modele?.type_equipement?.nom }}</p>
                </div>
              </div>

              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Statut</label>
                  <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="{
                          'bg-green-100 text-green-800': equipement.statut === 'actif',
                          'bg-red-100 text-red-800': equipement.statut === 'inactif',
                          'bg-yellow-100 text-yellow-800': equipement.statut === 'maintenance',
                          'bg-gray-100 text-gray-800': equipement.statut === 'remplace'
                        }">
                    {{ equipement.statut }}
                  </span>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Emplacement</label>
                  <p class="mt-1 text-sm text-gray-900">{{ equipement.emplacement || 'Non spécifié' }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Date d'installation</label>
                  <p class="mt-1 text-sm text-gray-900">
                    {{ equipement.date_installation ? new Date(equipement.date_installation).toLocaleDateString('fr-FR') : 'Non spécifiée' }}
                  </p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Date de mise en service</label>
                  <p class="mt-1 text-sm text-gray-900">
                    {{ equipement.date_mise_service ? new Date(equipement.date_mise_service).toLocaleDateString('fr-FR') : 'Non spécifiée' }}
                  </p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Date de création</label>
                  <p class="mt-1 text-sm text-gray-900">{{ new Date(equipement.created_at).toLocaleDateString('fr-FR') }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Dernière modification</label>
                  <p class="mt-1 text-sm text-gray-900">{{ new Date(equipement.updated_at).toLocaleDateString('fr-FR') }}</p>
                </div>
              </div>
            </div>

            <div v-if="equipement.observations" class="mt-6">
              <label class="block text-sm font-medium text-gray-700">Observations</label>
              <div class="mt-2 p-3 bg-gray-50 rounded-md">
                <p class="text-sm text-gray-900">{{ equipement.observations }}</p>
              </div>
            </div>

            <!-- Historique des pannes et interventions -->
            <div class="mt-8 space-y-6">
              <!-- Pannes récentes -->
              <div v-if="equipement.pannes && equipement.pannes.length > 0">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pannes récentes</h3>
                <div class="bg-red-50 rounded-lg p-4">
                  <div class="space-y-3">
                    <div v-for="panne in equipement.pannes.slice(0, 3)" :key="panne.id" class="bg-white p-3 rounded border-l-4 border-red-400">
                      <div class="flex justify-between items-start">
                        <div>
                          <h4 class="font-medium text-red-900">{{ panne.description }}</h4>
                          <p class="text-sm text-red-700">{{ new Date(panne.date_signalement).toLocaleDateString('fr-FR') }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                              :class="{
                                'bg-red-100 text-red-800': panne.statut === 'ouverte',
                                'bg-yellow-100 text-yellow-800': panne.statut === 'en_cours',
                                'bg-green-100 text-green-800': panne.statut === 'resolue'
                              }">
                          {{ panne.statut }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Interventions récentes -->
              <div v-if="equipement.interventions && equipement.interventions.length > 0">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Interventions récentes</h3>
                <div class="bg-blue-50 rounded-lg p-4">
                  <div class="space-y-3">
                    <div v-for="intervention in equipement.interventions.slice(0, 3)" :key="intervention.id" class="bg-white p-3 rounded border-l-4 border-blue-400">
                      <div class="flex justify-between items-start">
                        <div>
                          <h4 class="font-medium text-blue-900">{{ intervention.type_intervention }}</h4>
                          <p class="text-sm text-blue-700">{{ new Date(intervention.date_intervention).toLocaleDateString('fr-FR') }}</p>
                          <p class="text-sm text-gray-600">{{ intervention.description_travaux }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                              :class="{
                                'bg-yellow-100 text-yellow-800': intervention.statut === 'planifiee',
                                'bg-blue-100 text-blue-800': intervention.statut === 'en_cours',
                                'bg-green-100 text-green-800': intervention.statut === 'terminee'
                              }">
                          {{ intervention.statut }}
                        </span>
                      </div>
                    </div>
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

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps<{
  equipement: any
}>()
</script> 