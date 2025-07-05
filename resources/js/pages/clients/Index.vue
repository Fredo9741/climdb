<template>
  <AppLayout>
    <!-- Header avec bouton dans le contenu principal -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Clients</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
          Gérez vos clients et leurs informations
        </p>
      </div>
      <Button @click="router.visit('/clients/create')" class="bg-blue-600 hover:bg-blue-700 text-white">
        <Icon name="Plus" class="h-4 w-4 mr-2" />
        Nouveau client
      </Button>
    </div>

    <!-- Filtres et recherche -->
    <Card class="mb-6">
      <CardContent class="p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row gap-4">
          <!-- Barre de recherche -->
          <div class="flex-1">
            <div class="relative">
              <Icon name="Search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
              <Input
                v-model="searchTerm"
                placeholder="Rechercher un client..."
                class="pl-10"
              />
            </div>
          </div>
          
          <!-- Bouton reset -->
          <div class="flex gap-2">
            <Button variant="outline" @click="resetFilters">
              <Icon name="X" class="h-4 w-4 mr-2" />
              Reset
            </Button>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Table responsive -->
    <Card>
      <CardContent class="p-0">
        <!-- Vue desktop/tablet -->
        <div class="hidden sm:block overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Client
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Adresse
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Contact
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Sites
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="client in filteredClients" :key="client.id" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                        <Icon name="User" class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ client.nom }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  <div>{{ client.ville }}, {{ client.code_postal }}</div>
                  <div class="text-gray-500 dark:text-gray-400">{{ client.pays }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  <div>{{ client.email }}</div>
                  <div class="text-gray-500 dark:text-gray-400">{{ client.telephone }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    {{ client.sites_count || 0 }} sites
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end gap-2">
                    <Button size="sm" variant="ghost" @click="router.visit('/clients/' + client.id)">
                      <Icon name="Eye" class="h-4 w-4" />
                    </Button>
                    <Button size="sm" variant="ghost" @click="router.visit('/clients/' + client.id + '/edit')">
                      <Icon name="Edit" class="h-4 w-4" />
                    </Button>
                    <Button size="sm" variant="ghost" @click="deleteClient(client)" class="text-red-600 hover:text-red-700">
                      <Icon name="Trash2" class="h-4 w-4" />
                    </Button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </CardContent>
    </Card>

    <!-- Message si aucun résultat -->
    <div v-if="filteredClients.length === 0" class="text-center py-12">
      <Icon name="Users" class="h-12 w-12 mx-auto text-gray-400 mb-4" />
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun client trouvé</h3>
      <p class="text-gray-500 dark:text-gray-400 mb-4">
        {{ searchTerm ? 'Aucun client ne correspond à votre recherche.' : 'Commencez par ajouter votre premier client.' }}
      </p>
      <Button @click="router.visit('/clients/create')" v-if="!searchTerm">
        <Icon name="Plus" class="h-4 w-4 mr-2" />
        Ajouter un client
      </Button>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import Icon from '@/components/Icon.vue'

// Interface pour TypeScript
interface Client {
  id: number
  nom: string
  adresse: string
  ville: string
  code_postal: string
  pays: string
  telephone?: string
  email?: string
  sites_count?: number
}

interface Props {
  clients: Client[]
}

const props = defineProps<Props>()

// État local
const searchTerm = ref('')

// Clients filtrés
const filteredClients = computed(() => {
  let filtered = props.clients

  // Filtre par terme de recherche
  if (searchTerm.value) {
    const search = searchTerm.value.toLowerCase()
    filtered = filtered.filter(client => 
      client.nom.toLowerCase().includes(search) ||
      client.email?.toLowerCase().includes(search) ||
      client.ville.toLowerCase().includes(search)
    )
  }

  return filtered
})

// Fonctions
const resetFilters = () => {
  searchTerm.value = ''
}

const deleteClient = (client: Client) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer le client "' + client.nom + '" ?')) {
    router.delete('/clients/' + client.id)
  }
}
</script>
