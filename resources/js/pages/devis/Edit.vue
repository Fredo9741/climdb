<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Modifier le devis #{{ devis.numero_devis }}</h2>
            
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
                <label for="objet" class="block text-sm font-medium text-gray-700">Objet du devis</label>
                <input 
                  type="text" 
                  id="objet" 
                  v-model="form.objet" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  required
                />
              </div>

              <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description détaillée</label>
                <textarea 
                  id="description" 
                  v-model="form.description" 
                  rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                  <label for="taux_tva" class="block text-sm font-medium text-gray-700">Taux TVA (%)</label>
                  <select 
                    id="taux_tva" 
                    v-model="form.taux_tva" 
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
                  <label for="date_emission" class="block text-sm font-medium text-gray-700">Date d'émission</label>
                  <input 
                    type="date" 
                    id="date_emission" 
                    v-model="form.date_emission" 
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
                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                <select 
                  id="statut" 
                  v-model="form.statut" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="en_attente">En attente</option>
                  <option value="accepte">Accepté</option>
                  <option value="refuse">Refusé</option>
                  <option value="facture">Facturé</option>
                </select>
              </div>

              <div>
                <label for="conditions" class="block text-sm font-medium text-gray-700">Conditions particulières</label>
                <textarea 
                  id="conditions" 
                  v-model="form.conditions" 
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
              </div>

              <div class="flex justify-between">
                <Link
                  :href="route('devis.show', devis.id)"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                  Annuler
                </Link>
                
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  {{ form.processing ? 'Modification...' : 'Modifier le devis' }}
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

const props = defineProps<{
  devis: any
  clients: any[]
  sites: any[]
}>()

const form = useForm({
  client_id: props.devis.client_id,
  site_id: props.devis.site_id || '',
  objet: props.devis.objet,
  description: props.devis.description || '',
  montant_ht: props.devis.montant_ht,
  taux_tva: props.devis.taux_tva,
  date_emission: props.devis.date_emission,
  date_validite: props.devis.date_validite,
  statut: props.devis.statut,
  conditions: props.devis.conditions || ''
})

const calculateTTC = () => {
  const ht = parseFloat(form.montant_ht) || 0
  const tva = parseFloat(form.taux_tva) || 0
  const ttc = ht * (1 + tva / 100)
  return ttc.toFixed(2)
}

const submit = () => {
  form.put(route('devis.update', props.devis.id))
}
</script> 