<template>
  <AppLayout title="Modifier le modèle">
    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- En-tête -->
            <div class="mb-6">
              <h1 class="text-2xl font-bold text-gray-900">
                Modifier le modèle {{ modele.marque }} {{ modele.nom }}
              </h1>
              <p class="mt-1 text-sm text-gray-600">
                Modifiez les informations de ce modèle d'équipement
              </p>
            </div>

            <!-- Formulaire -->
            <form @submit.prevent="submit">
              <!-- Type d'équipement -->
              <div class="mb-4">
                <label for="type_equipement_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Type d'équipement *
                </label>
                <select
                  id="type_equipement_id"
                  v-model="form.type_equipement_id"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.type_equipement_id }"
                  required
                >
                  <option value="">Sélectionnez un type d'équipement</option>
                  <option v-for="type in typesEquipements" :key="type.id" :value="type.id">
                    {{ type.nom }}
                  </option>
                </select>
                <InputError v-if="errors.type_equipement_id" :message="errors.type_equipement_id" />
              </div>

              <!-- Marque -->
              <div class="mb-4">
                <label for="marque" class="block text-sm font-medium text-gray-700 mb-2">
                  Marque *
                </label>
                <input
                  id="marque"
                  v-model="form.marque"
                  type="text"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.marque }"
                  placeholder="Ex: Daikin, Mitsubishi, Atlantic..."
                  required
                />
                <InputError v-if="errors.marque" :message="errors.marque" />
              </div>

              <!-- Nom du modèle -->
              <div class="mb-4">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                  Nom du modèle *
                </label>
                <input
                  id="nom"
                  v-model="form.nom"
                  type="text"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.nom }"
                  placeholder="Ex: FTXM50R, Atlantic Naia 2..."
                  required
                />
                <InputError v-if="errors.nom" :message="errors.nom" />
              </div>

              <!-- Référence constructeur -->
              <div class="mb-4">
                <label for="reference_constructeur" class="block text-sm font-medium text-gray-700 mb-2">
                  Référence constructeur
                </label>
                <input
                  id="reference_constructeur"
                  v-model="form.reference_constructeur"
                  type="text"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.reference_constructeur }"
                  placeholder="Référence unique du fabricant"
                />
                <InputError v-if="errors.reference_constructeur" :message="errors.reference_constructeur" />
              </div>

              <!-- Type de gaz -->
              <div class="mb-4">
                <label for="type_gaz_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Type de gaz
                </label>
                <select
                  id="type_gaz_id"
                  v-model="form.type_gaz_id"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.type_gaz_id }"
                >
                  <option value="">Aucun type de gaz</option>
                  <option v-for="typeGaz in typesGaz" :key="typeGaz.id" :value="typeGaz.id">
                    {{ typeGaz.nom }}
                  </option>
                </select>
                <InputError v-if="errors.type_gaz_id" :message="errors.type_gaz_id" />
              </div>

              <!-- Quantité de gaz -->
              <div class="mb-4">
                <label for="quantite_gaz_kg" class="block text-sm font-medium text-gray-700 mb-2">
                  Quantité de gaz (kg)
                </label>
                <input
                  id="quantite_gaz_kg"
                  v-model="form.quantite_gaz_kg"
                  type="number"
                  step="0.01"
                  min="0"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.quantite_gaz_kg }"
                  placeholder="Ex: 2.5"
                />
                <InputError v-if="errors.quantite_gaz_kg" :message="errors.quantite_gaz_kg" />
              </div>

              <!-- Modèle de relevé par défaut -->
              <div class="mb-4">
                <label for="modele_releve_defaut_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Modèle de relevé par défaut
                </label>
                <select
                  id="modele_releve_defaut_id"
                  v-model="form.modele_releve_defaut_id"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.modele_releve_defaut_id }"
                >
                  <option value="">Aucun modèle de relevé</option>
                  <option v-for="modeleReleve in modelesReleves" :key="modeleReleve.id" :value="modeleReleve.id">
                    {{ modeleReleve.nom }}
                  </option>
                </select>
                <InputError v-if="errors.modele_releve_defaut_id" :message="errors.modele_releve_defaut_id" />
              </div>

              <!-- Description -->
              <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                  Description
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="4"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': errors.description }"
                  placeholder="Description détaillée du modèle..."
                ></textarea>
                <InputError v-if="errors.description" :message="errors.description" />
              </div>

              <!-- Boutons d'action -->
              <div class="flex justify-end space-x-4">
                <Link
                  :href="route('modeles.show', modele.id)"
                  class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400"
                >
                  Annuler
                </Link>
                <button
                  type="submit"
                  :disabled="processing"
                  class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 disabled:opacity-50"
                >
                  <span v-if="processing">Mise à jour...</span>
                  <span v-else>Mettre à jour</span>
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
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import InputError from '@/components/InputError.vue'

interface TypeEquipement {
  id: number
  nom: string
}

interface TypeGaz {
  id: number
  nom: string
}

interface ModeleReleve {
  id: number
  nom: string
}

interface Modele {
  id: number
  marque: string
  nom: string
  reference_constructeur?: string
  description?: string
  quantite_gaz_kg?: number
  type_equipement_id: number
  type_gaz_id?: number
  modele_releve_defaut_id?: number
}

interface Props {
  modele: Modele
  typesEquipements: TypeEquipement[]
  typesGaz: TypeGaz[]
  modelesReleves: ModeleReleve[]
  errors: Record<string, string>
}

const props = defineProps<Props>()

const form = useForm({
  type_equipement_id: props.modele.type_equipement_id,
  marque: props.modele.marque,
  nom: props.modele.nom,
  reference_constructeur: props.modele.reference_constructeur || '',
  description: props.modele.description || '',
  quantite_gaz_kg: props.modele.quantite_gaz_kg,
  type_gaz_id: props.modele.type_gaz_id || '',
  modele_releve_defaut_id: props.modele.modele_releve_defaut_id || '',
})

const submit = () => {
  form.put(route('modeles.update', props.modele.id))
}

const { errors, processing } = form
</script> 