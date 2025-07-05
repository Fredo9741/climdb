<template>
  <AppLayout :title="`Modèle ${modele.marque} ${modele.nom}`">
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- En-tête avec actions -->
            <div class="flex justify-between items-start mb-6">
              <div>
                <h1 class="text-2xl font-bold text-gray-900">
                  {{ modele.marque }} {{ modele.nom }}
                </h1>
                <p v-if="modele.reference_constructeur" class="mt-1 text-sm text-gray-600">
                  Réf: {{ modele.reference_constructeur }}
                </p>
              </div>
              <div class="flex space-x-2">
                <Link
                  :href="route('modeles.edit', modele.id)"
                  class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                >
                  <Icon name="pencil" class="w-4 h-4 mr-2" />
                  Modifier
                </Link>
                <button
                  @click="confirmDelete"
                  class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                  :disabled="(modele.equipements?.length || 0) > 0"
                  :class="{ 'opacity-50 cursor-not-allowed': (modele.equipements?.length || 0) > 0 }"
                >
                  <Icon name="trash" class="w-4 h-4 mr-2" />
                  Supprimer
                </button>
              </div>
            </div>

            <!-- Informations du modèle -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
              <!-- Informations générales -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations générales</h3>
                <dl class="space-y-3">
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Type d'équipement</dt>
                    <dd class="text-sm text-gray-900">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ modele.type_equipement?.nom || 'Non défini' }}
                      </span>
                    </dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Marque</dt>
                    <dd class="text-sm text-gray-900">{{ modele.marque }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Nom du modèle</dt>
                    <dd class="text-sm text-gray-900">{{ modele.nom }}</dd>
                  </div>
                  <div v-if="modele.reference_constructeur">
                    <dt class="text-sm font-medium text-gray-500">Référence constructeur</dt>
                    <dd class="text-sm text-gray-900">{{ modele.reference_constructeur }}</dd>
                  </div>
                </dl>
              </div>

              <!-- Caractéristiques techniques -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Caractéristiques techniques</h3>
                <dl class="space-y-3">
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Type de gaz</dt>
                    <dd class="text-sm text-gray-900">
                      {{ modele.type_gaz?.nom || 'Non défini' }}
                    </dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Quantité de gaz</dt>
                    <dd class="text-sm text-gray-900">
                      {{ modele.quantite_gaz_kg ? `${modele.quantite_gaz_kg} kg` : 'Non définie' }}
                    </dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Modèle de relevé par défaut</dt>
                    <dd class="text-sm text-gray-900">
                      {{ modele.modele_releve_defaut?.nom || 'Aucun' }}
                    </dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Description -->
            <div v-if="modele.description" class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
              <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ modele.description }}</p>
              </div>
            </div>

            <!-- Équipements utilisant ce modèle -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Équipements utilisant ce modèle ({{ modele.equipements?.length || 0 }})
              </h3>
              <div v-if="modele.equipements && modele.equipements.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Équipement
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Site
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client
                      </th>
                      <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="equipement in modele.equipements" :key="equipement.id" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ equipement.nom }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ equipement.site?.nom || 'N/A' }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ equipement.site?.client?.nom || 'N/A' }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <Link
                          :href="route('equipements.show', equipement.id)"
                          class="text-blue-600 hover:text-blue-900"
                        >
                          Voir
                        </Link>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-else class="text-center py-8 bg-gray-50 rounded-lg">
                <Icon name="cube" class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                <p class="text-gray-500">Aucun équipement n'utilise ce modèle pour le moment.</p>
              </div>
            </div>

            <!-- Retour à la liste -->
            <div class="flex justify-start">
              <Link
                :href="route('modeles.index')"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
              >
                <Icon name="arrow-left" class="w-4 h-4 mr-2" />
                Retour à la liste
              </Link>
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
              <strong>{{ modele.marque }} {{ modele.nom }}</strong> ?
              Cette action est irréversible.
            </p>
            <p v-if="(modele.equipements?.length || 0) > 0" class="text-sm text-red-600 mt-2">
              Attention : {{ modele.equipements?.length }} équipement(s) utilisent ce modèle.
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
              :disabled="(modele.equipements?.length || 0) > 0"
              class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 disabled:opacity-50"
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

interface Client {
  id: number
  nom: string
}

interface Site {
  id: number
  nom: string
  client?: Client
}

interface Equipement {
  id: number
  nom: string
  site?: Site
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
  created_at: string
  updated_at: string
}

interface Props {
  modele: Modele
}

const props = defineProps<Props>()

const showDeleteModal = ref(false)

const confirmDelete = () => {
  showDeleteModal.value = true
}

const deleteModele = () => {
  router.delete(route('modeles.destroy', props.modele.id), {
    onSuccess: () => {
      router.visit(route('modeles.index'))
    }
  })
}
</script> 