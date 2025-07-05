<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Facture #{{ facture.numero_facture }}</h2>
              <div class="space-x-2">
                <Link
                  :href="route('factures.edit', facture.id)"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Modifier
                </Link>
                <Link
                  :href="route('factures.index')"
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
                  <p class="mt-1 text-sm text-gray-900">{{ facture.client?.nom_entreprise || facture.client?.nom }}</p>
                </div>

                <div v-if="facture.devis">
                  <label class="block text-sm font-medium text-gray-700">Devis associé</label>
                  <Link :href="route('devis.show', facture.devis.id)" class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                    Devis #{{ facture.devis.numero_devis }}
                  </Link>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Objet</label>
                  <p class="mt-1 text-sm text-gray-900">{{ facture.objet }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Statut</label>
                  <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="{
                          'bg-yellow-100 text-yellow-800': facture.statut === 'emise',
                          'bg-blue-100 text-blue-800': facture.statut === 'envoyee',
                          'bg-green-100 text-green-800': facture.statut === 'payee',
                          'bg-red-100 text-red-800': facture.statut === 'en_retard'
                        }">
                    {{ facture.statut }}
                  </span>
                </div>
              </div>

              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Date d'émission</label>
                  <p class="mt-1 text-sm text-gray-900">{{ new Date(facture.date_emission).toLocaleDateString('fr-FR') }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                  <p class="mt-1 text-sm text-gray-900">{{ new Date(facture.date_echeance).toLocaleDateString('fr-FR') }}</p>
                </div>

                <div v-if="facture.date_paiement">
                  <label class="block text-sm font-medium text-gray-700">Date de paiement</label>
                  <p class="mt-1 text-sm text-gray-900">{{ new Date(facture.date_paiement).toLocaleDateString('fr-FR') }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Montant HT</label>
                  <p class="mt-1 text-sm text-gray-900">{{ facture.montant_ht }}€</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">TVA</label>
                  <p class="mt-1 text-sm text-gray-900">{{ facture.taux_tva }}%</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Montant TTC</label>
                  <p class="mt-1 text-lg font-bold text-gray-900">{{ facture.montant_ttc }}€</p>
                </div>
              </div>
            </div>

            <div v-if="facture.description" class="mt-6">
              <label class="block text-sm font-medium text-gray-700">Description</label>
              <div class="mt-2 p-3 bg-gray-50 rounded-md">
                <p class="text-sm text-gray-900">{{ facture.description }}</p>
              </div>
            </div>

            <div v-if="facture.conditions_paiement" class="mt-6">
              <label class="block text-sm font-medium text-gray-700">Conditions de paiement</label>
              <div class="mt-2 p-3 bg-gray-50 rounded-md">
                <p class="text-sm text-gray-900">{{ facture.conditions_paiement }}</p>
              </div>
            </div>

            <div class="mt-8 flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">
                  Créée le {{ new Date(facture.created_at).toLocaleDateString('fr-FR') }}
                </p>
              </div>
              
              <div class="space-x-2">
                <button
                  v-if="facture.statut === 'emise'"
                  @click="marquerEnvoyee"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Marquer comme envoyée
                </button>
                <button
                  v-if="facture.statut === 'envoyee' || facture.statut === 'en_retard'"
                  @click="marquerPayee"
                  class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                >
                  Marquer comme payée
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
  facture: any
}>()

const marquerEnvoyee = () => {
  router.patch(route('factures.update', props.facture.id), {
    statut: 'envoyee'
  })
}

const marquerPayee = () => {
  router.patch(route('factures.update', props.facture.id), {
    statut: 'payee',
    date_paiement: new Date().toISOString().split('T')[0]
  })
}
</script> 