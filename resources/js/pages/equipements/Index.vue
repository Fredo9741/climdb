<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages de feedback -->
        <div v-if="$page.props.flash?.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash?.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ $page.props.flash.error }}
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Gestion des Équipements</h2>
              <Link
                :href="route('equipements.create')"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              >
                Nouvel Équipement
              </Link>
            </div>

            <!-- Barre de recherche -->
            <div class="mb-6">
              <div class="max-w-md">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                  Rechercher un équipement
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                  <input
                    id="search"
                    v-model="searchTerm"
                    @input="search"
                    type="text"
                    placeholder="Nom, numéro de série, site, client, modèle..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                  >
                  <div v-if="searchTerm" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button
                      @click="clearSearch"
                      class="text-gray-400 hover:text-gray-600"
                    >
                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mb-4 flex justify-between items-center">
              <div class="text-sm text-gray-600">
                {{ equipements.total }} équipement{{ equipements.total > 1 ? 's' : '' }} trouvé{{ equipements.total > 1 ? 's' : '' }}
                <span v-if="searchTerm" class="font-medium">pour "{{ searchTerm }}"</span>
              </div>
              <div v-if="searchTerm" class="text-sm">
                <button @click="clearSearch" class="text-indigo-600 hover:text-indigo-800">
                  Effacer la recherche
                </button>
              </div>
            </div>
            
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Nom & N° Série
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Site
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Client
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Modèle
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Type d'équipement
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      État
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr 
                    v-for="equipement in equipements.data" 
                    :key="equipement.id" 
                    class="hover:bg-gray-50 transition-colors cursor-pointer"
                    @click="router.visit(route('equipements.show', equipement.id))"
                  >
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ equipement.nom }}</div>
                      <div class="text-sm text-gray-500">{{ equipement.numero_serie }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ equipement.site?.nom || 'N/A' }}</div>
                      <div v-if="equipement.localisation_precise" class="text-sm text-gray-500">{{ equipement.localisation_precise }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">
                        {{ equipement.site?.client?.nom || 'N/A' }}
                      </div>
                      <div v-if="equipement.site?.client?.type_client" class="text-sm text-gray-500 capitalize">
                        {{ equipement.site?.client?.type_client }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ equipement.modele?.nom || 'N/A' }}</div>
                      <div v-if="equipement.modele?.marque" class="text-sm text-gray-500">{{ equipement.modele.marque }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ equipement.modele?.type_equipement?.nom || 'N/A' }}</div>
                      <div v-if="equipement.modele?.type_gaz?.nom" class="text-sm text-gray-500">
                        Gaz: {{ equipement.modele.type_gaz.nom }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                            :class="{
                              'bg-green-100 text-green-800': equipement.etat === 'actif',
                              'bg-gray-100 text-gray-800': equipement.etat === 'inactif',
                              'bg-yellow-100 text-yellow-800': equipement.etat === 'maintenance',
                              'bg-red-100 text-red-800': equipement.etat === 'panne'
                            }">
                        {{ getEtatLabel(equipement.etat) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" @click.stop>
                      <div class="flex justify-end gap-2">
                        <Button size="sm" variant="ghost" @click="router.visit(route('equipements.show', equipement.id))">
                          <Icon name="Eye" class="h-4 w-4" />
                        </Button>
                        <Button size="sm" variant="ghost" @click="router.visit(route('equipements.edit', equipement.id))">
                          <Icon name="Edit" class="h-4 w-4" />
                        </Button>
                        <DeleteButton
                          :route="route('equipements.destroy', equipement.id)"
                          :title="`Supprimer l'équipement`"
                          :message="`Êtes-vous sûr de vouloir supprimer cet équipement ?`"
                          :equipment-name="equipement.nom"
                        />
                      </div>
                    </td>
                  </tr>
                  <tr v-if="equipements.data.length === 0">
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                      <div class="text-lg mb-2">
                        {{ searchTerm ? 'Aucun équipement trouvé pour cette recherche' : 'Aucun équipement trouvé' }}
                      </div>
                      <div class="text-sm">
                        <Link v-if="!searchTerm" :href="route('equipements.create')" class="text-blue-600 hover:text-blue-800">
                          Créer votre premier équipement
                        </Link>
                        <button v-else @click="clearSearch" class="text-blue-600 hover:text-blue-800">
                          Effacer la recherche
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <Pagination
              v-if="equipements.data.length > 0"
              :links="equipements.links"
              :from="equipements.from"
              :to="equipements.to" 
              :total="equipements.total"
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import DeleteButton from '@/components/DeleteButton.vue'
import Pagination from '@/components/Pagination.vue'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'

interface Equipement {
  id: number
  nom: string
  numero_serie: string
  etat: string
  localisation_precise?: string
  site?: { 
    nom: string
          client?: {
        nom?: string
        type_client?: string
      }
  }
  modele?: { 
    nom: string
    marque?: string
    type_equipement?: { nom: string }
    type_gaz?: { nom: string }
  }
}

interface PaginatedEquipements {
  data: Equipement[]
  links: Array<{
    url: string | null
    label: string
    active: boolean
  }>
  from: number
  to: number
  total: number
  current_page: number
  last_page: number
}

const props = defineProps<{
  equipements: PaginatedEquipements
  filters: {
    search?: string
  }
}>()

const searchTerm = ref(props.filters.search || '')

// Simple debounce function
let searchTimeout: number | null = null

const search = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }
  
  searchTimeout = setTimeout(() => {
    router.get(route('equipements.index'), 
      searchTerm.value ? { search: searchTerm.value } : {},
      { 
        preserveState: true,
        replace: true 
      }
    )
  }, 300)
}

const clearSearch = () => {
  searchTerm.value = ''
  router.get(route('equipements.index'), {}, { 
    preserveState: true,
    replace: true 
  })
}

const getEtatLabel = (etat: string): string => {
  const labels: Record<string, string> = {
    'actif': 'Actif',
    'inactif': 'Inactif', 
    'maintenance': 'En maintenance',
    'panne': 'En panne'
  }
  return labels[etat] || etat
}
</script>
