<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Gestion des Sites</h2>
              <Link
                :href="route('sites.create')"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              >
                Nouveau Site
              </Link>
            </div>
            <!-- Barre de recherche -->
            <div class="mb-6 max-w-md">
              <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
              <div class="relative">
                <input
                  id="search"
                  v-model="searchTerm"
                  type="text"
                  placeholder="Nom du site, client, ville..."
                  class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                />
                <button v-if="searchTerm" @click="searchTerm = ''" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600">
                  ×
                </button>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Nom
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Client
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Ville
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="site in filteredSites" :key="site.id" class="hover:bg-gray-50 transition-colors cursor-pointer" @click="router.visit(route('sites.show', site.id))">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ site.nom }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ site.client?.nom || 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ site.ville }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" @click.stop>
                      <div class="flex justify-end gap-2">
                        <Button size="sm" variant="ghost" @click="router.visit(route('sites.show', site.id))">
                          <Icon name="Eye" class="h-4 w-4" />
                        </Button>
                        <Button size="sm" variant="ghost" @click="router.visit(route('sites.edit', site.id))">
                          <Icon name="Edit" class="h-4 w-4" />
                        </Button>
                        <Button size="sm" variant="ghost" @click="deleteSite(site)" class="text-red-600 hover:text-red-700">
                          <Icon name="Trash2" class="h-4 w-4" />
                        </Button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'

defineProps<{
  sites: Array<{
    id: number
    nom: string
    ville: string
    client?: { nom: string }
  }>
}>()

const searchTerm = ref('')

const filteredSites = computed(() => {
  return props.sites.filter(site => {
    const matchesNom = site.nom.toLowerCase().includes(searchTerm.value.toLowerCase())
    const matchesClient = site.client?.nom ? site.client.nom.toLowerCase().includes(searchTerm.value.toLowerCase()) : false
    const matchesVille = site.ville.toLowerCase().includes(searchTerm.value.toLowerCase())
    return matchesNom || matchesClient || matchesVille
  })
})

const deleteSite = (site: any) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer le site "${site.nom}" ?`)) {
    router.delete(route('sites.destroy', site.id))
  }
}
</script>
