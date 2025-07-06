<template>
  <AppLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Interventions</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ isTechnicien ? 'Vos interventions' : 'Toutes les interventions' }}
          </p>
        </div>
        <div class="flex gap-2">
          <Button @click="router.visit('/interventions/create')" v-if="canCreate">
            <Icon name="Plus" class="h-4 w-4 mr-2" />
            <span class="hidden sm:inline">Nouvelle</span> intervention
          </Button>
        </div>
      </div>
    </template>

    <!-- Vue en onglets pour mobile -->
    <div class="sm:hidden mb-6">
      <div class="flex border-b border-gray-200 dark:border-gray-700">
        <button
          v-for="tab in mobileTabs"
          :key="tab.key"
          @click="activeTab = tab.key"
          :class="[
            'flex-1 py-2 px-3 text-sm font-medium text-center border-b-2 transition-colors',
            activeTab === tab.key
              ? 'border-blue-500 text-blue-600 dark:text-blue-400'
              : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
          ]"
        >
          {{ tab.label }}
          <span class="ml-1 text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">
            {{ getTabCount(tab.key) }}
          </span>
        </button>
      </div>
    </div>

    <!-- Filtres pour desktop -->
    <Card class="mb-6 hidden sm:block">
      <CardContent class="p-4 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <Label for="search">Rechercher</Label>
            <div class="relative mt-1">
              <Icon name="Search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
              <Input
                id="search"
                v-model="filters.search"
                placeholder="Description, équipement..."
                class="pl-10"
              />
            </div>
          </div>

          <div>
            <Label for="type">Type</Label>
            <select
              id="type"
              v-model="filters.type"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Tous</option>
              <option value="maintenance">Maintenance</option>
              <option value="reparation">Réparation</option>
              <option value="installation">Installation</option>
              <option value="inspection">Inspection</option>
            </select>
          </div>

          <div>
            <Label for="resultat">Résultat</Label>
            <select
              id="resultat"
              v-model="filters.resultat"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Tous</option>
              <option value="reussi">Réussi</option>
              <option value="partiel">Partiel</option>
              <option value="echec">Échec</option>
            </select>
          </div>

          <div class="flex items-end">
            <Button variant="outline" @click="resetFilters" class="w-full">
              <Icon name="X" class="h-4 w-4 mr-2" />
              Reset
            </Button>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Liste des interventions -->
    <div class="space-y-4">
      <!-- Mode mobile: cards optimisées -->
      <div class="sm:hidden space-y-3">
        <Card 
          v-for="intervention in filteredInterventions" 
          :key="intervention.id"
          class="hover:shadow-md transition-shadow cursor-pointer"
          @click="router.visit(`/interventions/${intervention.id}`)"
        >
          <CardContent class="p-4">
            <!-- Header avec status et type -->
            <div class="flex justify-between items-start mb-3">
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                  <span :class="[
                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                    getTypeColor(intervention.type_intervention)
                  ]">
                    {{ getTypeLabel(intervention.type_intervention) }}
                  </span>
                  <span :class="[
                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                    getResultatColor(intervention.resultat)
                  ]">
                    {{ getResultatLabel(intervention.resultat) }}
                  </span>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2">
                  {{ intervention.description }}
                </h3>
              </div>
              <DropdownMenu @click.stop="">
                <DropdownMenuTrigger asChild>
                  <Button variant="ghost" size="sm">
                    <Icon name="MoreVertical" class="h-4 w-4" />
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuItem @click="router.visit(`/interventions/${intervention.id}`)">
                    <Icon name="Eye" class="h-4 w-4 mr-2" />
                    Voir détails
                  </DropdownMenuItem>
                  <DropdownMenuItem @click="router.visit(`/interventions/${intervention.id}/edit`)" v-if="canEdit">
                    <Icon name="Edit" class="h-4 w-4 mr-2" />
                    Modifier
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>

            <!-- Informations principales -->
            <div class="space-y-2 text-sm">
              <div class="flex items-center text-gray-600 dark:text-gray-400" v-if="getInterventionEquipement(intervention)">
                <Icon name="Settings" class="h-4 w-4 mr-2 flex-shrink-0" />
                <span class="truncate">{{ getInterventionEquipement(intervention).nom }}</span>
              </div>
              
              <div class="flex items-center text-gray-600 dark:text-gray-400" v-if="getInterventionSite(intervention)">
                <Icon name="MapPin" class="h-4 w-4 mr-2 flex-shrink-0" />
                <span class="truncate">{{ getInterventionSite(intervention).nom }}</span>
              </div>
              
              <div class="flex items-center text-gray-600 dark:text-gray-400" v-if="!getInterventionEquipement(intervention)">
                <Icon name="Wrench" class="h-4 w-4 mr-2 flex-shrink-0" />
                <span class="truncate text-orange-600">Intervention générale (sans équipement)</span>
              </div>
              
              <div class="flex items-center text-gray-600 dark:text-gray-400">
                <Icon name="User" class="h-4 w-4 mr-2 flex-shrink-0" />
                <span class="truncate">{{ intervention.technicien?.name || 'Technicien non assigné' }}</span>
              </div>
              
              <div class="flex items-center justify-between">
                <div class="flex items-center text-gray-600 dark:text-gray-400">
                  <Icon name="Calendar" class="h-4 w-4 mr-2 flex-shrink-0" />
                  <span>{{ formatDate(intervention.date_debut || intervention.date_planifiee) }}</span>
                </div>
                
                <div v-if="intervention.date_fin" class="text-xs text-gray-500">
                  Durée: {{ calculateDuration(intervention.date_debut, intervention.date_fin) }}
                </div>
              </div>
            </div>

            <!-- Actions rapides -->
            <div class="flex gap-2 mt-4" v-if="intervention.technicien_id === $page.props.auth.user.id">
              <Button 
                size="sm" 
                variant="outline" 
                class="flex-1"
                @click.stop="router.visit(`/interventions/${intervention.id}/edit`)"
                v-if="!intervention.date_fin"
              >
                <Icon name="Edit" class="h-4 w-4 mr-1" />
                Modifier
              </Button>
              
              <Button 
                size="sm" 
                class="flex-1"
                @click.stop="router.visit(`/interventions/${intervention.id}`)"
              >
                <Icon name="Eye" class="h-4 w-4 mr-1" />
                Détails
              </Button>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Mode desktop: tableau -->
      <div class="hidden sm:block">
        <Card>
          <CardContent class="p-0">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Intervention
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Équipement
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Technicien
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Type
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Résultat
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Date
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="intervention in filteredInterventions" :key="intervention.id" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                  <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ intervention.description }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400" v-if="intervention.panne">
                                              Panne: {{ intervention.panne.description_panne }}
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ getInterventionEquipement(intervention)?.nom || 'Intervention générale' }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ getInterventionSite(intervention)?.nom || 'Sans équipement' }}
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                    {{ intervention.technicien?.name || 'Technicien non assigné' }}
                  </td>
                  <td class="px-6 py-4">
                    <span :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      getTypeColor(intervention.type_intervention)
                    ]">
                      {{ getTypeLabel(intervention.type_intervention) }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      getResultatColor(intervention.resultat)
                    ]">
                      {{ getResultatLabel(intervention.resultat) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                    <div>{{ formatDate(intervention.date_debut) }}</div>
                    <div class="text-xs text-gray-500" v-if="intervention.date_fin">
                      Durée: {{ calculateDuration(intervention.date_debut, intervention.date_fin) }}
                    </div>
                  </td>
                  <td class="px-6 py-4 text-right text-sm font-medium">
                    <div class="flex justify-end gap-2">
                      <Button size="sm" variant="ghost" @click="router.visit(`/interventions/${intervention.id}`)">
                        <Icon name="Eye" class="h-4 w-4" />
                      </Button>
                      <Button size="sm" variant="ghost" @click="router.visit(`/interventions/${intervention.id}/edit`)" v-if="canEdit">
                        <Icon name="Edit" class="h-4 w-4" />
                      </Button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </CardContent>
        </Card>
      </div>
    </div>

    <!-- Message si aucun résultat -->
    <div v-if="filteredInterventions.length === 0" class="text-center py-12">
      <Icon name="Wrench" class="h-12 w-12 mx-auto text-gray-400 mb-4" />
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucune intervention trouvée</h3>
      <p class="text-gray-500 dark:text-gray-400 mb-4">
        {{ hasFilters ? 'Aucune intervention ne correspond à vos critères.' : 'Aucune intervention programmée.' }}
      </p>
      <Button @click="router.visit('/interventions/create')" v-if="canCreate && !hasFilters">
        <Icon name="Plus" class="h-4 w-4 mr-2" />
        Programmer une intervention
      </Button>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import Icon from '@/components/Icon.vue'

// Props
const props = defineProps<{
  interventions: Array<any>
}>()

const page = usePage()

// État local
const activeTab = ref('all')
const filters = reactive({
  search: '',
  type: '',
  resultat: '',
})

// Onglets pour mobile
const mobileTabs = [
  { key: 'all', label: 'Toutes' },
  { key: 'pending', label: 'En cours' },
  { key: 'completed', label: 'Terminées' },
  { key: 'mine', label: 'Miennes' }
]

// Permissions
const canCreate = computed(() => page.props.auth.user.roles.includes('admin') || page.props.auth.user.roles.includes('technicien'))
const canEdit = computed(() => page.props.auth.user.roles.includes('admin') || page.props.auth.user.roles.includes('technicien'))
const isTechnicien = computed(() => page.props.auth.user.roles.includes('technicien'))

// Computed
const hasFilters = computed(() => filters.search || filters.type || filters.resultat)

const filteredInterventions = computed(() => {
  let filtered = props.interventions

  // Filtre par onglet mobile
  if (window.innerWidth < 640) { // sm breakpoint
    switch (activeTab.value) {
      case 'pending':
        filtered = filtered.filter(i => !i.date_fin)
        break
      case 'completed':
        filtered = filtered.filter(i => i.date_fin)
        break
      case 'mine':
        filtered = filtered.filter(i => i.technicien_id === page.props.auth.user.id)
        break
    }
  }

  // Filtres texte
  if (filters.search) {
    const search = filters.search.toLowerCase()
    filtered = filtered.filter(intervention => {
      const equipement = getInterventionEquipement(intervention)
      const site = getInterventionSite(intervention)
      
      return intervention.rapport?.toLowerCase().includes(search) ||
             equipement?.nom?.toLowerCase().includes(search) ||
             site?.nom?.toLowerCase().includes(search) ||
             intervention.technicien?.name?.toLowerCase().includes(search)
    })
  }

  if (filters.type) {
    filtered = filtered.filter(intervention => intervention.type_intervention === filters.type)
  }

  if (filters.resultat) {
    filtered = filtered.filter(intervention => intervention.resultat === filters.resultat)
  }

  return filtered
})

// Methods
const getTabCount = (tabKey: string) => {
  switch (tabKey) {
    case 'all':
      return props.interventions.length
    case 'pending':
      return props.interventions.filter(i => !i.date_fin).length
    case 'completed':
      return props.interventions.filter(i => i.date_fin).length
    case 'mine':
      return props.interventions.filter(i => i.technicien_id === page.props.auth.user.id).length
    default:
      return 0
  }
}

const resetFilters = () => {
  filters.search = ''
  filters.type = ''
  filters.resultat = ''
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const calculateDuration = (start: string, end: string) => {
  const startDate = new Date(start)
  const endDate = new Date(end)
  const diffMs = endDate.getTime() - startDate.getTime()
  const diffHours = Math.round(diffMs / (1000 * 60 * 60) * 10) / 10
  return `${diffHours}h`
}

const getTypeColor = (type: string) => {
  const colors = {
    maintenance: 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
    reparation: 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
    installation: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
    inspection: 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400'
  }
  return colors[type as keyof typeof colors] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
}

const getTypeLabel = (type: string) => {
  const labels = {
    maintenance: 'Maintenance',
    reparation: 'Réparation',
    installation: 'Installation',
    inspection: 'Inspection'
  }
  return labels[type as keyof typeof labels] || type
}

const getResultatColor = (resultat: string) => {
  const colors = {
    reussi: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
    partiel: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
    echec: 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
  }
  return colors[resultat as keyof typeof colors] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
}

const getResultatLabel = (resultat: string) => {
  const labels = {
    reussi: 'Réussi',
    partiel: 'Partiel',
    echec: 'Échec'
  }
  return labels[resultat as keyof typeof labels] || resultat
}

// Fonctions helper pour accéder aux informations d'équipement
const getInterventionEquipement = (intervention: any) => {
  if (intervention.panne?.equipement) {
    return intervention.panne.equipement
  }
  return null
}

const getInterventionSite = (intervention: any) => {
  if (intervention.panne?.equipement?.site) {
    return intervention.panne.equipement.site
  }
  return null
}

const getInterventionClient = (intervention: any) => {
  if (intervention.panne?.equipement?.site?.client) {
    return intervention.panne.equipement.site.client
  }
  return null
}
</script> 