<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Modifier l'équipement</h2>
            
            <form @submit.prevent="submit" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="site_id" class="block text-sm font-medium text-gray-700">Site</label>
                  <select 
                    id="site_id" 
                    v-model="form.site_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  >
                    <option value="">Sélectionner un site</option>
                    <option v-for="site in sites" :key="site.id" :value="site.id">
                      {{ site.nom }} ({{ site.client?.nom_entreprise || site.client?.nom }})
                    </option>
                  </select>
                </div>

                <div>
                  <label for="modele_id" class="block text-sm font-medium text-gray-700">Modèle</label>
                  <select 
                    id="modele_id" 
                    v-model="form.modele_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  >
                    <option value="">Sélectionner un modèle</option>
                    <option v-for="modele in modeles" :key="modele.id" :value="modele.id">
                      {{ modele.marque }} {{ modele.nom }} ({{ modele.type_equipement?.nom }})
                    </option>
                  </select>
                </div>
              </div>

              <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom de l'équipement</label>
                <input 
                  type="text" 
                  id="nom" 
                  v-model="form.nom" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  required
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="numero_serie" class="block text-sm font-medium text-gray-700">Numéro de série</label>
                  <input 
                    type="text" 
                    id="numero_serie" 
                    v-model="form.numero_serie" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  />
                </div>

                <div>
                  <label for="date_installation" class="block text-sm font-medium text-gray-700">Date d'installation</label>
                  <input 
                    type="date" 
                    id="date_installation" 
                    v-model="form.date_installation" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="date_derniere_maintenance" class="block text-sm font-medium text-gray-700">Date de dernière maintenance</label>
                  <input 
                    type="date" 
                    id="date_derniere_maintenance" 
                    v-model="form.date_derniere_maintenance" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  />
                </div>

                <div>
                  <label for="etat" class="block text-sm font-medium text-gray-700">État</label>
                  <select 
                    id="etat" 
                    v-model="form.etat" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                    <option value="maintenance">En maintenance</option>
                    <option value="panne">En panne</option>
                  </select>
                </div>
              </div>

              <div>
                <label for="localisation_precise" class="block text-sm font-medium text-gray-700">Localisation précise</label>
                <input 
                  type="text" 
                  id="localisation_precise" 
                  v-model="form.localisation_precise" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Ex: Salle des machines, Étage 2, Bureau 3..."
                />
              </div>

              <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea 
                  id="description" 
                  v-model="form.description" 
                  rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Description détaillée de l'équipement..."
                ></textarea>
              </div>

              <div class="flex justify-between">
                <Link
                  :href="route('equipements.show', equipement.id)"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                  Annuler
                </Link>
                
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  {{ form.processing ? 'Modification...' : 'Modifier l\'équipement' }}
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
  equipement: any
  sites: any[]
  modeles: any[]
}>()

const form = useForm({
  site_id: props.equipement.site_id,
  modele_id: props.equipement.modele_id,
  nom: props.equipement.nom,
  numero_serie: props.equipement.numero_serie,
  date_installation: props.equipement.date_installation,
  date_derniere_maintenance: props.equipement.date_derniere_maintenance,
  etat: props.equipement.etat,
  localisation_precise: props.equipement.localisation_precise || '',
  description: props.equipement.description || ''
})

const submit = () => {
  form.put(route('equipements.update', props.equipement.id))
}
</script> 