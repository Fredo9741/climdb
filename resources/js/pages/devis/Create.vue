<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Créer un nouveau devis</h2>
            
            <form @submit.prevent="submit" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                  <select 
                    id="client_id" 
                    v-model="form.client_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  >
                    <option value="">Sélectionner un client</option>
                    <option v-for="client in clients" :key="client.id" :value="client.id">
                                              {{ client.nom }}
                    </option>
                  </select>
                </div>

                <div>
                  <label for="site_id" class="block text-sm font-medium text-gray-700">Site (optionnel)</label>
                  <select 
                    id="site_id" 
                    v-model="form.site_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                    <option value="">Sélectionner un site</option>
                    <option v-for="site in sites" :key="site.id" :value="site.id">
                      {{ site.nom }}
                    </option>
                  </select>
                </div>
              </div>

              <div>
                <label for="numero" class="block text-sm font-medium text-gray-700">Numéro de devis</label>
                <input 
                  type="text" 
                  id="numero" 
                  v-model="form.numero" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  required
                  placeholder="Ex: DEV-2024-001"
                />
              </div>

              <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description détaillée</label>
                <textarea 
                  id="description" 
                  v-model="form.description" 
                  rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Détaillez les prestations proposées..."
                ></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label for="montant_ht" class="block text-sm font-medium text-gray-700">Montant HT (€)</label>
                  <input 
                    type="number" 
                    id="montant_ht" 
                    v-model="form.montant_ht" 
                    step="0.01"
                    min="0"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  />
                </div>

                <div>
                  <label for="tva" class="block text-sm font-medium text-gray-700">Taux TVA (%)</label>
                  <select 
                    id="tva" 
                    v-model="form.tva" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                    <option value="20">20%</option>
                    <option value="10">10%</option>
                    <option value="5.5">5.5%</option>
                    <option value="0">0%</option>
                  </select>
                </div>

                <div>
                  <label for="montant_ttc" class="block text-sm font-medium text-gray-700">Montant TTC (€)</label>
                  <input 
                    type="text" 
                    id="montant_ttc" 
                    :value="calculateTTC()"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                    readonly
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="date_creation" class="block text-sm font-medium text-gray-700">Date de création</label>
                  <input 
                    type="date" 
                    id="date_creation" 
                    v-model="form.date_creation" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  />
                </div>

                <div>
                  <label for="date_validite" class="block text-sm font-medium text-gray-700">Date de validité</label>
                  <input 
                    type="date" 
                    id="date_validite" 
                    v-model="form.date_validite" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  />
                </div>
              </div>

              <div>
                <label for="conditions_paiement" class="block text-sm font-medium text-gray-700">Conditions de paiement</label>
                <textarea 
                  id="conditions_paiement" 
                  v-model="form.conditions_paiement" 
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Conditions de paiement, délais, garanties..."
                ></textarea>
              </div>

              <div class="flex justify-between">
                <Link
                  :href="route('devis.index')"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                  Annuler
                </Link>
                
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  {{ form.processing ? 'Création...' : 'Créer le devis' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps<{
  clients: any[]
  sites: any[]
}>()

const form = useForm({
  client_id: '',
  site_id: '',
  numero: '',
  date_creation: new Date().toISOString().split('T')[0],
  date_validite: '',
  montant_ht: 0,
  tva: 20,
  montant_ttc: 0,
  statut: 'brouillon',
  description: '',
  conditions_paiement: ''
})

const calculateTTC = () => {
  const ht = parseFloat(form.montant_ht.toString()) || 0
  const tva = parseFloat(form.tva.toString()) || 0
  const ttc = ht * (1 + tva / 100)
  return ttc.toFixed(2)
}

const submit = () => {
  // Calculer le montant TTC avant l'envoi
  form.montant_ttc = parseFloat(calculateTTC())
  form.post(route('devis.store'))
}
</script> 