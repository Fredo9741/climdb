<template>
  <AppLayout title="Modèles d'équipements">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- En-tête avec bouton d'ajout -->
            <div class="flex justify-between items-center mb-6">
              <div>
                <h1 class="text-2xl font-bold text-gray-900">Modèles d'équipements</h1>
                <p class="mt-1 text-sm text-gray-600">
                  Gérez les modèles d'équipements de climatisation
                </p>
              </div>
              <Link
                :href="route('modeles.create')"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
              >
                <Icon name="plus" class="w-4 h-4 mr-2" />
                Nouveau modèle
              </Link>
            </div>

            <!-- Tableau des modèles -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Modèle
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Type d'équipement
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Type de gaz
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Quantité de gaz
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Équipements
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="modele in modeles" :key="modele.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                      <div class="flex flex-col">
                        <div class="text-sm font-medium text-gray-900">
                          {{ modele.marque }} {{ modele.nom }}
                        </div>
                        <div v-if="modele.reference_constructeur" class="text-sm text-gray-500">
                          Ref: {{ modele.reference_constructeur }}
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ modele.type_equipement?.nom || 'Non défini' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">
                        {{ modele.type_gaz?.nom || 'Non défini' }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">
                        {{ modele.quantite_gaz_kg ? `${modele.quantite_gaz_kg} kg` : '-' }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        {{ modele.equipements_count || modele.equipements?.length || 0 }} équipement(s)
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="flex justify-end space-x-2">
                        <Link
                          :href="route('modeles.show', modele.id)"
                          class="text-blue-600 hover:text-blue-900"
                          title="Voir"
                        >
                          <Icon name="eye" class="w-4 h-4" />
                        </Link>
                        <Link
                          :href="route('modeles.edit', modele.id)"
                          class="text-orange-600 hover:text-orange-900"
                          title="Modifier"
                        >
                          <Icon name="pencil" class="w-4 h-4" />
                        </Link>
                        <button
                          @click="confirmDelete(modele)"
                          class="text-red-600 hover:text-red-900"
                          title="Supprimer"
                          :disabled="(modele.equipements?.length || 0) > 0"
                          :class="{ 'opacity-50 cursor-not-allowed': (modele.equipements?.length || 0) > 0 }"
                        >
                          <Icon name="trash" class="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              
              <!-- Message si aucun modèle -->
              <div v-if="modeles.length === 0" class="text-center py-8">
                <Icon name="cube" class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun modèle</h3>
                <p class="text-gray-500 mb-4">Commencez par créer votre premier modèle d'équipement.</p>
                <Link
                  :href="route('modeles.create')"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Créer un modèle
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <Icon name="exclamation-triangle" class="w-16 h-16 mx-auto text-red-600 mb-4" />
          <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
              Êtes-vous sûr de vouloir supprimer le modèle
              <strong>{{ modelToDelete?.marque }} {{ modelToDelete?.nom }}</strong> ?
              Cette action est irréversible.
            </p>
          </div>
          <div class="flex justify-center space-x-4 mt-4">
            <button
              @click="showDeleteModal = false"
              class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400"
            >
              Annuler
            </button>
            <button
              @click="deleteModele"
              class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700"
            >
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'

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

interface Equipement {
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
  type_equipement?: TypeEquipement
  type_gaz?: TypeGaz
  modele_releve_defaut?: ModeleReleve
  equipements?: Equipement[]
  equipements_count?: number
  created_at: string
  updated_at: string
}

interface Props {
  modeles: Modele[]
}

defineProps<Props>()

const showDeleteModal = ref(false)
const modelToDelete = ref<Modele | null>(null)

const confirmDelete = (modele: Modele) => {
  if ((modele.equipements?.length || 0) > 0) {
    alert('Impossible de supprimer ce modèle car des équipements l\'utilisent encore.')
    return
  }
  modelToDelete.value = modele
  showDeleteModal.value = true
}

const deleteModele = () => {
  if (modelToDelete.value) {
    router.delete(route('modeles.destroy', modelToDelete.value.id), {
      onSuccess: () => {
        showDeleteModal.value = false
        modelToDelete.value = null
      }
    })
  }
}
</script> 