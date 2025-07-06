<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Devis #{{ devis.numero_devis }}</h2>
              <div class="space-x-2">
                <Link
                  :href="route('devis.edit', devis.id)"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Modifier
                </Link>
                <Link
                  :href="route('devis.index')"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                  Retour
                </Link>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Client</label>
                  <p class="mt-1 text-sm text-gray-900">{{ devis.client?.nom }}</p>
                </div>

                <div v-if="devis.site">
                  <label class="block text-sm font-medium text-gray-700">Site</label>
                  <p class="mt-1 text-sm text-gray-900">{{ devis.site.nom }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Description</label>
                  <p class="mt-1 text-sm text-gray-900">{{ devis.description }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Statut</label>
                  <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="{
                          'bg-yellow-100 text-yellow-800': devis.statut === 'en_attente',
                          'bg-green-100 text-green-800': devis.statut === 'accepte',
                          'bg-red-100 text-red-800': devis.statut === 'refuse',
                          'bg-blue-100 text-blue-800': devis.statut === 'facture'
                        }">
                    {{ devis.statut }}
                  </span>
                </div>
              </div>

              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Date du devis</label>
                  <p class="mt-1 text-sm text-gray-900">{{ new Date(devis.date_devis).toLocaleDateString('fr-FR') }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Date d'expiration</label>
                  <p class="mt-1 text-sm text-gray-900">{{ new Date(devis.date_expiration).toLocaleDateString('fr-FR') }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Numéro de devis</label>
                  <p class="mt-1 text-sm text-gray-900">{{ devis.numero_devis }}</p>
                </div>
              </div>
            </div>

            <div v-if="devis.description" class="mt-6">
              <label class="block text-sm font-medium text-gray-700">Description détaillée</label>
              <div class="mt-2 p-3 bg-gray-50 rounded-md">
                <p class="text-sm text-gray-900">{{ devis.description }}</p>
              </div>
            </div>

            <div class="mt-8 flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">
                  Créé le {{ new Date(devis.created_at).toLocaleDateString('fr-FR') }}
                </p>
              </div>
              
              <div class="space-x-2">
                <button
                  v-if="devis.statut === 'en_attente'"
                  @click="accepter"
                  class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                >
                  Accepter
                </button>
                <button
                  v-if="devis.statut === 'en_attente'"
                  @click="refuser"
                  class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                >
                  Refuser
                </button>
                <button
                  v-if="devis.statut === 'accepte'"
                  @click="genererFacture"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Générer facture
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps<{
  devis: any
}>()

const accepter = () => {
  router.patch(route('devis.update', props.devis.id), {
    statut: 'accepte'
  })
}

const refuser = () => {
  router.patch(route('devis.update', props.devis.id), {
    statut: 'refuse'
  })
}

const genererFacture = () => {
  router.post(route('factures.store'), {
    devis_id: props.devis.id
  })
}
</script> 