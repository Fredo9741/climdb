<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Gestion des Devis</h2>
              <Link
                :href="route('devis.create')"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              >
                Nouveau Devis
              </Link>
            </div>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Numéro
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Client
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Montant
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="devis in devis" :key="devis.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ devis.numero }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ devis.client?.nom || 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ formatDate(devis.date_devis) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ formatCurrency(devis.montant_ttc) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <Link :href="route('devis.show', devis.id)" class="text-blue-600 hover:text-blue-900 mr-2">
                        Voir
                      </Link>
                      <Link :href="route('devis.edit', devis.id)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                        Modifier
                      </Link>
                      <button @click="deleteDevis(devis)" class="text-red-600 hover:text-red-900">
                        Supprimer
                      </button>
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
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'

defineProps<{
  devis: Array<{
    id: number
    numero: string
    date_devis: string
    montant_ttc: number
    client?: { nom: string }
  }>
}>()

const formatDate = (date: string) => {
  if (!date) return 'N/A'
  // S'assurer qu'on passe un ISO complet pour éviter Invalid Date
  const parsed = new Date(date.includes('T') ? date : `${date}T00:00:00`)
  if (isNaN(parsed.getTime())) return 'N/A'
  return parsed.toLocaleDateString('fr-FR')
}

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR'
  }).format(amount)
}

const deleteDevis = (devis) => {
  if (confirm(`Supprimer le devis ${devis.numero} ?`)) {
    router.delete(route('devis.destroy', devis.id))
  }
}
</script>
