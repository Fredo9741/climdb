<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ site.nom }}</h2>
                <p class="text-gray-600">{{ site.client?.nom }} - {{ site.ville }}</p>
              </div>
              <div class="flex space-x-2">
                <Link
                  :href="route('sites.edit', site.id)"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Modifier
                </Link>
                <Link
                  :href="route('sites.index')"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                  Retour
                </Link>
              </div>
            </div>

            <!-- Informations du site -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
              <!-- Informations générales -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                  <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  Informations générales
                </h3>
                <div class="space-y-3">
                  <div>
                    <span class="font-medium text-gray-700">Nom du site :</span>
                    <span class="ml-2 text-gray-900">{{ site.nom }}</span>
                  </div>
                  <div>
                    <span class="font-medium text-gray-700">Client propriétaire :</span>
                    <Link 
                      :href="route('clients.show', site.client.id)"
                      class="ml-2 text-blue-600 hover:text-blue-800"
                    >
                      {{ site.client?.nom }}
                    </Link>
                  </div>
                  <div v-if="site.type_site">
                    <span class="font-medium text-gray-700">Type de site :</span>
                    <span class="ml-2 text-gray-900 capitalize">{{ site.type_site.replace('_', ' ') }}</span>
                  </div>
                  <div v-if="site.surface">
                    <span class="font-medium text-gray-700">Surface :</span>
                    <span class="ml-2 text-gray-900">{{ site.surface }} m²</span>
                  </div>
                  <div>
                    <span class="font-medium text-gray-700">Créé le :</span>
                    <span class="ml-2 text-gray-900">{{ formatDate(site.created_at) }}</span>
                  </div>
                </div>
              </div>

              <!-- Adresse -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                  <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  Adresse
                </h3>
                <div class="space-y-2">
                  <div class="text-gray-900">
                    {{ site.adresse }}
                  </div>
                  <div class="text-gray-900">
                    {{ site.code_postal }} {{ site.ville }}
                  </div>
                  <div class="text-gray-900">
                    {{ site.pays }}
                  </div>
                  <div v-if="site.latitude && site.longitude" class="mt-3 pt-3 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                      GPS : {{ site.latitude }}, {{ site.longitude }}
                    </div>
                    <a 
                      :href="`https://www.google.com/maps/search/?api=1&query=${site.latitude},${site.longitude}`"
                      target="_blank"
                      class="text-blue-600 hover:text-blue-800 text-sm"
                    >
                      Voir sur Google Maps
                    </a>
                  </div>
                </div>
              </div>

              <!-- Contact sur site -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                  <svg class="h-5 w-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  Contact sur site
                </h3>
                <div v-if="site.contact_nom || site.contact_telephone || site.contact_email" class="space-y-2">
                  <div v-if="site.contact_nom">
                    <span class="font-medium text-gray-700">Nom :</span>
                    <span class="ml-2 text-gray-900">{{ site.contact_nom }}</span>
                  </div>
                  <div v-if="site.contact_fonction">
                    <span class="font-medium text-gray-700">Fonction :</span>
                    <span class="ml-2 text-gray-900">{{ site.contact_fonction }}</span>
                  </div>
                  <div v-if="site.contact_telephone">
                    <span class="font-medium text-gray-700">Téléphone :</span>
                    <a :href="`tel:${site.contact_telephone}`" class="ml-2 text-blue-600 hover:text-blue-800">
                      {{ site.contact_telephone }}
                    </a>
                  </div>
                  <div v-if="site.contact_email">
                    <span class="font-medium text-gray-700">Email :</span>
                    <a :href="`mailto:${site.contact_email}`" class="ml-2 text-blue-600 hover:text-blue-800">
                      {{ site.contact_email }}
                    </a>
                  </div>
                </div>
                <div v-else class="text-gray-500 text-sm">
                  Aucun contact défini
                </div>
              </div>
            </div>

            <!-- Notes -->
            <div v-if="site.notes" class="mb-8">
              <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-2 text-yellow-800 flex items-center">
                  <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Notes et observations
                </h3>
                <p class="text-yellow-700 whitespace-pre-wrap">{{ site.notes }}</p>
              </div>
            </div>

            <!-- Équipements du site -->
            <div class="mt-8">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                  <svg class="h-6 w-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                  </svg>
                  Équipements ({{ site.equipements ? site.equipements.length : 0 }})
                </h3>
                <Link
                  :href="`${route('equipements.create')}?site_id=${site.id}`"
                  class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm"
                >
                  + Ajouter un équipement
                </Link>
              </div>

              <div v-if="site.equipements && site.equipements.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                  v-for="equipement in site.equipements"
                  :key="equipement.id"
                  class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                >
                  <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                      <h4 class="font-semibold text-gray-900">{{ equipement.numero_serie }}</h4>
                      <p class="text-sm text-gray-600">{{ equipement.modele?.nom }}</p>
                    </div>
                    <Link
                      :href="route('equipements.show', equipement.id)"
                      class="text-blue-500 hover:text-blue-700 text-sm font-medium"
                    >
                      Voir
                    </Link>
                  </div>
                  
                  <div class="text-sm text-gray-600 space-y-1">
                    <div v-if="equipement.modele?.type_equipement">
                      <span class="font-medium">Type :</span> 
                      {{ equipement.modele.type_equipement.nom }}
                    </div>
                    <div v-if="equipement.date_installation">
                      <span class="font-medium">Installé le :</span> 
                      {{ formatDate(equipement.date_installation) }}
                    </div>
                    <div class="mt-3 pt-2 border-t border-gray-100">
                      <div class="flex justify-between items-center">
                        <span :class="getStatutClass(equipement.statut)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                          {{ getStatutLabel(equipement.statut) }}
                        </span>
                        <div class="flex space-x-1">
                          <Link
                            :href="`${route('interventions.create')}?equipement_id=${equipement.id}`"
                            class="text-green-600 hover:text-green-800 text-xs"
                            title="Nouvelle intervention"
                          >
                            Intervention
                          </Link>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
                <div class="text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun équipement</h3>
                  <p class="mt-1 text-sm text-gray-500">Aucun équipement n'est installé sur ce site</p>
                  <div class="mt-6">
                    <Link
                      :href="`${route('equipements.create')}?site_id=${site.id}`"
                      class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                    >
                      <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      Ajouter le premier équipement
                    </Link>
                  </div>
                </div>
              </div>
            </div>

            <!-- Interventions récentes -->
            <div v-if="site.interventions_recentes && site.interventions_recentes.length > 0" class="mt-8">
              <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="h-6 w-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Interventions récentes
              </h3>
              <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Équipement</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Technicien</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="intervention in site.interventions_recentes" :key="intervention.id">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ formatDate(intervention.date_intervention) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                        {{ intervention.type_intervention }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ intervention.equipement?.numero_serie }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ intervention.user?.name }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="getInterventionStatutClass(intervention.statut)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                          {{ getInterventionStatutLabel(intervention.statut) }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
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

interface Client {
  id: number
  nom: string
}

interface TypeEquipement {
  nom: string
}

interface Modele {
  nom: string
  type_equipement?: TypeEquipement
}

interface Equipement {
  id: number
  numero_serie: string
  statut: string
  date_installation?: string
  modele?: Modele
}

interface Intervention {
  id: number
  date_intervention: string
  type_intervention: string
  statut: string
  equipement?: { numero_serie: string }
  user?: { name: string }
}

interface Site {
  id: number
  nom: string
  type_site?: string
  surface?: number
  adresse: string
  code_postal: string
  ville: string
  pays: string
  latitude?: number
  longitude?: number
  contact_nom?: string
  contact_fonction?: string
  contact_telephone?: string
  contact_email?: string
  notes?: string
  created_at: string
  client: Client
  equipements?: Equipement[]
  interventions_recentes?: Intervention[]
}

defineProps<{
  site: Site
}>()

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('fr-FR')
}

const getStatutClass = (statut: string) => {
  switch (statut) {
    case 'actif':
      return 'bg-green-100 text-green-800'
    case 'inactif':
      return 'bg-red-100 text-red-800'
    case 'maintenance':
      return 'bg-yellow-100 text-yellow-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getStatutLabel = (statut: string) => {
  switch (statut) {
    case 'actif':
      return 'Actif'
    case 'inactif':
      return 'Inactif'
    case 'maintenance':
      return 'Maintenance'
    default:
      return 'Inconnu'
  }
}

const getInterventionStatutClass = (statut: string) => {
  switch (statut) {
    case 'programmee':
      return 'bg-blue-100 text-blue-800'
    case 'en_cours':
      return 'bg-yellow-100 text-yellow-800'
    case 'terminee':
      return 'bg-green-100 text-green-800'
    case 'annulee':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getInterventionStatutLabel = (statut: string) => {
  switch (statut) {
    case 'programmee':
      return 'Programmée'
    case 'en_cours':
      return 'En cours'
    case 'terminee':
      return 'Terminée'
    case 'annulee':
      return 'Annulée'
    default:
      return 'Inconnu'
  }
}
</script> 