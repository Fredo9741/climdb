<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ client.nom }}</h2>
                <p class="text-gray-600">Détails du client</p>
              </div>
              <div class="flex space-x-2">
                <Link
                  :href="route('clients.edit', client.id)"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Modifier
                </Link>
                <Link
                  :href="route('clients.index')"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                  Retour
                </Link>
              </div>
            </div>

            <!-- Informations du client -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Informations générales</h3>
                <div class="space-y-2">
                  <div>
                    <span class="font-medium text-gray-700">Nom :</span>
                    <span class="ml-2 text-gray-900">{{ client.nom }}</span>
                  </div>
                  <div>
                    <span class="font-medium text-gray-700">Email :</span>
                    <span class="ml-2 text-gray-900">{{ client.email }}</span>
                  </div>
                  <div>
                    <span class="font-medium text-gray-700">Téléphone :</span>
                    <span class="ml-2 text-gray-900">{{ client.telephone }}</span>
                  </div>
                </div>
              </div>

              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Adresse</h3>
                <div class="space-y-2">
                  <div>
                    <span class="font-medium text-gray-700">Adresse :</span>
                    <span class="ml-2 text-gray-900">{{ client.adresse }}</span>
                  </div>
                  <div>
                    <span class="font-medium text-gray-700">Ville :</span>
                    <span class="ml-2 text-gray-900">{{ client.ville }}</span>
                  </div>
                  <div>
                    <span class="font-medium text-gray-700">Code postal :</span>
                    <span class="ml-2 text-gray-900">{{ client.code_postal }}</span>
                  </div>
                  <div>
                    <span class="font-medium text-gray-700">Pays :</span>
                    <span class="ml-2 text-gray-900">{{ client.pays }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sites du client -->
            <div class="mt-8">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Sites associés</h3>
                <Link
                  :href="`${route('sites.create')}?client_id=${client.id}`"
                  class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm"
                >
                  Ajouter un site
                </Link>
              </div>

              <div v-if="client.sites && client.sites.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                  v-for="site in client.sites"
                  :key="site.id"
                  class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                >
                  <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-gray-900">{{ site.nom }}</h4>
                    <Link
                      :href="route('sites.show', site.id)"
                      class="text-blue-500 hover:text-blue-700 text-sm"
                    >
                      Voir
                    </Link>
                  </div>
                  
                  <div class="text-sm text-gray-600 space-y-1">
                    <p>{{ site.adresse }}</p>
                    <p>{{ site.ville }}, {{ site.code_postal }}</p>
                    <div class="mt-2 pt-2 border-t border-gray-100">
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ site.equipements ? site.equipements.length : 0 }} équipement(s)
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="text-center py-8 bg-gray-50 rounded-lg">
                <p class="text-gray-500">Aucun site associé à ce client</p>
                <Link
                  :href="`${route('sites.create')}?client_id=${client.id}`"
                  class="mt-2 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Créer le premier site
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

interface Site {
  id: number
  nom: string
  adresse: string
  ville: string
  code_postal: string
  equipements?: any[]
}

interface Client {
  id: number
  nom: string
  email: string
  telephone: string
  adresse: string
  ville: string
  code_postal: string
  pays: string
  sites?: Site[]
}

defineProps<{
  client: Client
}>()
</script> 