<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Gestion des Pannes
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Suivi et gestion des pannes d'équipements
          </p>
        </div>
        <Button v-if="canCreate" @click="router.visit(route('pannes.create'))" class="gap-2">
          <Icon name="plus" class="h-4 w-4" />
          Nouvelle Panne
        </Button>
      </div>
    </template>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <Card>
        <CardContent class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalPannes }}</p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
              <Icon name="alert-triangle" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">En attente</p>
              <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ pannesEnAttente }}</p>
            </div>
            <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
              <Icon name="clock" class="h-6 w-6 text-orange-600 dark:text-orange-400" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">En cours</p>
              <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ pannesEnCours }}</p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
              <Icon name="settings" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Résolues</p>
              <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ pannesResolues }}</p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
              <Icon name="check-circle" class="h-6 w-6 text-green-600 dark:text-green-400" />
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Filtres et recherche -->
    <Card class="mb-6">
      <CardContent class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <Label for="search">Recherche</Label>
            <Input
              id="search"
              v-model="searchQuery"
              placeholder="Rechercher dans les pannes..."
              class="mt-1"
            />
          </div>
          <div>
            <Label for="priority">Priorité</Label>
            <select
              id="priority"
              v-model="selectedPriority"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Toutes les priorités</option>
              <option value="urgente">Urgente</option>
              <option value="haute">Haute</option>
              <option value="moyenne">Moyenne</option>
              <option value="faible">Faible</option>
            </select>
          </div>
          <div>
            <Label for="status">Statut</Label>
            <select
              id="status"
              v-model="selectedStatus"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Tous les statuts</option>
              <option value="13">En attente</option>
              <option value="14">En cours</option>
              <option value="15">Résolue</option>
              <option value="16">Annulée</option>
            </select>
          </div>
          <div class="flex items-end">
            <Button @click="resetFilters" variant="outline" class="w-full">
              Réinitialiser
            </Button>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Liste des pannes -->
    <Card>
      <CardContent class="p-0">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Équipement
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Description
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Priorité
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Statut
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Date de constat
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="panne in filteredPannes" :key="panne.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ panne.equipement?.nom || 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ panne.equipement?.site?.nom || 'N/A' }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">
                    {{ panne.description_panne }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getPriorityClass(panne.priorite)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ formatPriority(panne.priorite) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusClass(panne.statut_demande_id)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ formatStatus(panne.statut_demande_id) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(panne.date_constat) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end gap-2">
                    <Button @click="viewPanne(panne)" variant="outline" size="sm">
                      <Icon name="eye" class="h-4 w-4" />
                    </Button>
                    <Button v-if="canEdit" @click="editPanne(panne)" variant="outline" size="sm">
                      <Icon name="edit" class="h-4 w-4" />
                    </Button>
                    <Button v-if="canDelete" @click="deletePanne(panne)" variant="outline" size="sm" class="text-red-600 hover:text-red-700">
                      <Icon name="trash" class="h-4 w-4" />
                    </Button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          
          <div v-if="filteredPannes.length === 0" class="text-center py-12">
            <Icon name="alert-triangle" class="h-12 w-12 text-gray-400 mx-auto mb-4" />
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucune panne trouvée</h3>
            <p class="text-gray-500 dark:text-gray-400">
              {{ searchQuery || selectedPriority || selectedStatus ? 'Aucune panne ne correspond aux filtres sélectionnés.' : 'Aucune panne enregistrée pour le moment.' }}
            </p>
            <Button v-if="canCreate && !searchQuery && !selectedPriority && !selectedStatus" @click="router.visit(route('pannes.create'))" class="mt-4">
              Créer la première panne
            </Button>
          </div>
        </div>
      </CardContent>
    </Card>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import Icon from '@/components/Icon.vue'

const page = usePage()

const props = defineProps({
  pannes: {
    type: Array,
    default: () => []
  }
})

// État local
const searchQuery = ref('')
const selectedPriority = ref('')
const selectedStatus = ref('')

// Permissions
const canCreate = computed(() => page.props.auth.user.roles.includes('admin') || page.props.auth.user.roles.includes('technicien'))
const canEdit = computed(() => page.props.auth.user.roles.includes('admin') || page.props.auth.user.roles.includes('technicien'))
const canDelete = computed(() => page.props.auth.user.roles.includes('admin'))

// Statistiques
const totalPannes = computed(() => props.pannes.length)
const pannesEnAttente = computed(() => props.pannes.filter(p => p.statut_demande_id === 13).length)
const pannesEnCours = computed(() => props.pannes.filter(p => p.statut_demande_id === 14).length)
const pannesResolues = computed(() => props.pannes.filter(p => p.statut_demande_id === 15).length)

// Pannes filtrées
const filteredPannes = computed(() => {
  let filtered = [...props.pannes]

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(panne => 
      panne.description_panne?.toLowerCase().includes(query) ||
      panne.equipement?.nom?.toLowerCase().includes(query) ||
      panne.equipement?.site?.nom?.toLowerCase().includes(query)
    )
  }

  if (selectedPriority.value) {
    filtered = filtered.filter(panne => panne.priorite === selectedPriority.value)
  }

  if (selectedStatus.value) {
    filtered = filtered.filter(panne => panne.statut_demande_id.toString() === selectedStatus.value)
  }

  return filtered
})

// Méthodes utilitaires
const formatPriority = (priority) => {
  const priorities = {
    'urgente': 'Urgente',
    'haute': 'Haute',
    'moyenne': 'Moyenne',
    'faible': 'Faible'
  }
  return priorities[priority] || priority
}

const formatStatus = (statusId) => {
  const statuses = {
    13: 'En attente',
    14: 'En cours',
    15: 'Résolue',
    16: 'Annulée'
  }
  return statuses[statusId] || 'Inconnu'
}

const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getPriorityClass = (priority) => {
  const classes = {
    'urgente': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    'haute': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
    'moyenne': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    'faible': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
  }
  return classes[priority] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
}

const getStatusClass = (statusId) => {
  const classes = {
    13: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    14: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    15: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    16: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
  }
  return classes[statusId] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
}

// Actions
const resetFilters = () => {
  searchQuery.value = ''
  selectedPriority.value = ''
  selectedStatus.value = ''
}

const viewPanne = (panne) => {
  router.visit(route('pannes.show', panne.id))
}

const editPanne = (panne) => {
  router.visit(route('pannes.edit', panne.id))
}

const deletePanne = (panne) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette panne ?')) {
    router.delete(route('pannes.destroy', panne.id), {
      onSuccess: () => {
        // Message de succès géré par le backend
      }
    })
  }
}
</script> 