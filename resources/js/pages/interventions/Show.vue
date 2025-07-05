<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <!-- En-tête -->
            <div class="flex justify-between items-center mb-6">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Détails de l'Intervention</h2>
                <p class="text-gray-600">Informations complètes de l'intervention technique</p>
              </div>
              <div class="flex space-x-2">
                <Link
                  :href="route('interventions.edit', intervention.id)"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Modifier
                </Link>
                <Link
                  :href="route('interventions.index')"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                  Retour
                </Link>
              </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Informations principales -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Informations principales</h3>
                <div class="space-y-3">
                  <div>
                    <label class="block text-sm font-medium text-gray-600">Date planifiée</label>
                    <p class="text-gray-900">{{ formatDate(intervention.date_planifiee) }}</p>
                  </div>
                  
                  <div v-if="intervention.date_debut">
                    <label class="block text-sm font-medium text-gray-600">Date de début</label>
                    <p class="text-gray-900">{{ formatDate(intervention.date_debut) }}</p>
                  </div>
                  
                  <div v-if="intervention.date_fin">
                    <label class="block text-sm font-medium text-gray-600">Date de fin</label>
                    <p class="text-gray-900">{{ formatDate(intervention.date_fin) }}</p>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-600">Statut</label>
                    <span :class="getStatusClass(intervention.statut)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ getStatusText(intervention.statut) }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Équipement et technicien -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Équipement et technicien</h3>
                <div class="space-y-3">
                  <div v-if="intervention.panne?.equipement">
                    <label class="block text-sm font-medium text-gray-600">Équipement</label>
                    <p class="text-gray-900">{{ intervention.panne.equipement.numero_serie }}</p>
                    <p class="text-sm text-gray-600">{{ intervention.panne.equipement.modele?.nom }}</p>
                  </div>
                  
                  <div v-if="intervention.panne?.equipement?.site">
                    <label class="block text-sm font-medium text-gray-600">Site</label>
                    <p class="text-gray-900">{{ intervention.panne.equipement.site.nom }}</p>
                    <p class="text-sm text-gray-600">{{ intervention.panne.equipement.site.adresse }}</p>
                  </div>
                  
                  <div v-if="intervention.panne?.equipement?.site?.client">
                    <label class="block text-sm font-medium text-gray-600">Client</label>
                    <p class="text-gray-900">{{ intervention.panne.equipement.site.client.nom }}</p>
                  </div>
                  
                  <div v-if="intervention.technicien">
                    <label class="block text-sm font-medium text-gray-600">Technicien assigné</label>
                    <p class="text-gray-900">{{ intervention.technicien.name }}</p>
                    <p class="text-sm text-gray-600">{{ intervention.technicien.email }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Rapport d'intervention -->
            <div class="mt-6 bg-gray-50 p-4 rounded-lg">
              <h3 class="text-lg font-semibold mb-4 text-gray-800">Rapport d'intervention</h3>
              <div class="bg-white p-4 rounded border">
                <pre class="whitespace-pre-wrap text-gray-900 text-sm">{{ intervention.rapport || 'Aucun rapport disponible.' }}</pre>
              </div>
            </div>

            <!-- Informations de panne (si applicable) -->
            <div v-if="intervention.panne" class="mt-6 bg-gray-50 p-4 rounded-lg">
              <h3 class="text-lg font-semibold mb-4 text-gray-800">Panne associée</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-600">Date de déclaration</label>
                  <p class="text-gray-900">{{ formatDate(intervention.panne.date_panne) }}</p>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-600">Priorité</label>
                  <span :class="getPriorityClass(intervention.panne.priorite)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ getPriorityText(intervention.panne.priorite) }}
                  </span>
                </div>
                
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-600">Description de la panne</label>
                  <p class="text-gray-900">{{ intervention.panne.description_probleme }}</p>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex items-center justify-between pt-6 border-t border-gray-200">
              <div class="text-sm text-gray-500">
                Intervention #{{ intervention.id }}
              </div>
              
              <div class="flex space-x-2">
                <Link
                  :href="route('interventions.edit', intervention.id)"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                  Modifier cette intervention
                </Link>
                
                <button
                  @click="confirmDelete"
                  class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                >
                  Supprimer
                </button>
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
          <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
              Êtes-vous sûr de vouloir supprimer cette intervention ? Cette action est irréversible.
            </p>
          </div>
          <div class="flex justify-center space-x-2 mt-4">
            <button
              @click="deleteIntervention"
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
            >
              Supprimer
            </button>
            <button
              @click="showDeleteModal = false"
              class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded"
            >
              Annuler
            </button>
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
import { ref } from 'vue'

interface Site {
  id: number
  nom: string
  adresse: string
  client: {
    id: number
    nom: string
  }
}

interface Equipement {
  id: number
  numero_serie: string
  site: Site
  modele?: {
    nom: string
  }
}

interface Panne {
  id: number
  date_panne: string
  description_probleme: string
  priorite: string
  equipement: Equipement
}

interface Technicien {
  id: number
  name: string
  email: string
}

interface Intervention {
  id: number
  date_planifiee: string
  date_debut?: string
  date_fin?: string
  rapport?: string
  statut: string
  panne?: Panne
  technicien: Technicien
}

const props = defineProps<{
  intervention: Intervention
}>()

const showDeleteModal = ref(false)

// Formatage de date
const formatDate = (dateString: string): string => {
  if (!dateString) return 'Non définie'
  
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Classes CSS pour le statut
const getStatusClass = (statut: string): string => {
  const statusClasses = {
    'programmee': 'bg-yellow-100 text-yellow-800',
    'en_cours': 'bg-blue-100 text-blue-800',
    'terminee': 'bg-green-100 text-green-800',
    'annulee': 'bg-red-100 text-red-800'
  }
  return statusClasses[statut as keyof typeof statusClasses] || 'bg-gray-100 text-gray-800'
}

// Texte pour le statut
const getStatusText = (statut: string): string => {
  const statusTexts = {
    'programmee': 'Programmée',
    'en_cours': 'En cours',
    'terminee': 'Terminée',
    'annulee': 'Annulée'
  }
  return statusTexts[statut as keyof typeof statusTexts] || statut
}

// Classes CSS pour la priorité
const getPriorityClass = (priorite: string): string => {
  const priorityClasses = {
    'faible': 'bg-green-100 text-green-800',
    'normale': 'bg-yellow-100 text-yellow-800',
    'haute': 'bg-orange-100 text-orange-800',
    'critique': 'bg-red-100 text-red-800'
  }
  return priorityClasses[priorite as keyof typeof priorityClasses] || 'bg-gray-100 text-gray-800'
}

// Texte pour la priorité
const getPriorityText = (priorite: string): string => {
  const priorityTexts = {
    'faible': 'Faible',
    'normale': 'Normale',
    'haute': 'Haute',
    'critique': 'Critique'
  }
  return priorityTexts[priorite as keyof typeof priorityTexts] || priorite
}

// Confirmation de suppression
const confirmDelete = () => {
  showDeleteModal.value = true
}

// Suppression de l'intervention
const deleteIntervention = () => {
  router.delete(route('interventions.destroy', props.intervention.id))
}
</script> 