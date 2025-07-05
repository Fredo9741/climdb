<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Nouvelle Intervention</h2>
                <p class="text-gray-600">Créer une nouvelle intervention technique</p>
              </div>
              <Link
                :href="route('interventions.index')"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
              >
                Retour
              </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
              <!-- Informations de base -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Informations de base</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Date d'intervention -->
                  <div>
                    <label for="date_intervention" class="block text-sm font-medium text-gray-700">
                      Date d'intervention *
                    </label>
                    <input
                      id="date_intervention"
                      v-model="form.date_intervention"
                      type="datetime-local"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                    <div v-if="form.errors.date_intervention" class="mt-1 text-sm text-red-600">
                      {{ form.errors.date_intervention }}
                    </div>
                  </div>

                  <!-- Type d'intervention -->
                  <div>
                    <label for="type_intervention" class="block text-sm font-medium text-gray-700">
                      Type d'intervention *
                    </label>
                    <select
                      id="type_intervention"
                      v-model="form.type_intervention"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    >
                      <option value="">Sélectionner un type</option>
                      <option value="maintenance">Maintenance préventive</option>
                      <option value="reparation">Réparation</option>
                      <option value="installation">Installation</option>
                      <option value="controle">Contrôle technique</option>
                      <option value="urgence">Intervention d'urgence</option>
                    </select>
                    <div v-if="form.errors.type_intervention" class="mt-1 text-sm text-red-600">
                      {{ form.errors.type_intervention }}
                    </div>
                  </div>

                  <!-- Équipement -->
                  <div>
                    <label for="equipement_id" class="block text-sm font-medium text-gray-700">
                      Équipement concerné *
                    </label>
                    <select
                      id="equipement_id"
                      v-model="form.equipement_id"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    >
                      <option value="">Sélectionner un équipement</option>
                      <option
                        v-for="equipement in equipements"
                        :key="equipement.id"
                        :value="equipement.id"
                      >
                        {{ equipement.numero_serie }} - {{ equipement.site?.nom }} ({{ equipement.modele?.nom }})
                      </option>
                    </select>
                    <div v-if="form.errors.equipement_id" class="mt-1 text-sm text-red-600">
                      {{ form.errors.equipement_id }}
                    </div>
                  </div>

                  <!-- Technicien -->
                  <div>
                    <label for="technicien_id" class="block text-sm font-medium text-gray-700">
                      Technicien assigné *
                    </label>
                    <select
                      id="technicien_id"
                      v-model="form.technicien_id"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    >
                      <option value="">Sélectionner un technicien</option>
                      <option
                        v-for="technicien in techniciens"
                        :key="technicien.id"
                        :value="technicien.id"
                      >
                        {{ technicien.name }}
                      </option>
                    </select>
                    <div v-if="form.errors.technicien_id" class="mt-1 text-sm text-red-600">
                      {{ form.errors.technicien_id }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Description et observations -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Description de l'intervention</h3>
                <div class="space-y-4">
                  <!-- Description -->
                  <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                      Description des travaux *
                    </label>
                    <textarea
                      id="description"
                      v-model="form.description"
                      rows="4"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Décrivez les travaux à effectuer..."
                      required
                    ></textarea>
                    <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                      {{ form.errors.description }}
                    </div>
                  </div>

                  <!-- Observations -->
                  <div>
                    <label for="observations" class="block text-sm font-medium text-gray-700">
                      Observations particulières
                    </label>
                    <textarea
                      id="observations"
                      v-model="form.observations"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Remarques, conditions particulières..."
                    ></textarea>
                    <div v-if="form.errors.observations" class="mt-1 text-sm text-red-600">
                      {{ form.errors.observations }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Durée et coût -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Durée et coût estimé</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Durée estimée -->
                  <div>
                    <label for="duree_estimee" class="block text-sm font-medium text-gray-700">
                      Durée estimée (heures)
                    </label>
                    <input
                      id="duree_estimee"
                      v-model="form.duree_estimee"
                      type="number"
                      step="0.5"
                      min="0"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: 2.5"
                    />
                    <div v-if="form.errors.duree_estimee" class="mt-1 text-sm text-red-600">
                      {{ form.errors.duree_estimee }}
                    </div>
                  </div>

                  <!-- Coût estimé main d'oeuvre -->
                  <div>
                    <label for="cout_main_oeuvre" class="block text-sm font-medium text-gray-700">
                      Coût main d'œuvre (€)
                    </label>
                    <input
                      id="cout_main_oeuvre"
                      v-model="form.cout_main_oeuvre"
                      type="number"
                      step="0.01"
                      min="0"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: 150.00"
                    />
                    <div v-if="form.errors.cout_main_oeuvre" class="mt-1 text-sm text-red-600">
                      {{ form.errors.cout_main_oeuvre }}
                    </div>
                  </div>

                  <!-- Coût estimé pièces -->
                  <div>
                    <label for="cout_pieces" class="block text-sm font-medium text-gray-700">
                      Coût pièces (€)
                    </label>
                    <input
                      id="cout_pieces"
                      v-model="form.cout_pieces"
                      type="number"
                      step="0.01"
                      min="0"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: 75.00"
                    />
                    <div v-if="form.errors.cout_pieces" class="mt-1 text-sm text-red-600">
                      {{ form.errors.cout_pieces }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Statut -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Statut</h3>
                <div>
                  <label for="statut" class="block text-sm font-medium text-gray-700">
                    Statut de l'intervention
                  </label>
                  <select
                    id="statut"
                    v-model="form.statut"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  >
                    <option value="programmee">Programmée</option>
                    <option value="en_cours">En cours</option>
                    <option value="terminee">Terminée</option>
                    <option value="annulee">Annulée</option>
                  </select>
                  <div v-if="form.errors.statut" class="mt-1 text-sm text-red-600">
                    {{ form.errors.statut }}
                  </div>
                </div>
              </div>

              <!-- Boutons d'action -->
              <div class="flex items-center justify-end space-x-4 pt-6">
                <Link
                  :href="route('interventions.index')"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded"
                >
                  Annuler
                </Link>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  <span v-if="form.processing">Création en cours...</span>
                  <span v-else>Créer l'intervention</span>
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
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

interface Equipement {
  id: number
  numero_serie: string
  site?: { nom: string }
  modele?: { nom: string }
}

interface Technicien {
  id: number
  name: string
}

const props = defineProps<{
  equipements: Equipement[]
  techniciens: Technicien[]
}>()

const form = useForm({
  date_intervention: '',
  type_intervention: '',
  equipement_id: '',
  technicien_id: '',
  description: '',
  observations: '',
  duree_estimee: '',
  cout_main_oeuvre: '',
  cout_pieces: '',
  statut: 'programmee'
})

const submit = () => {
  form.post(route('interventions.store'))
}
</script> 