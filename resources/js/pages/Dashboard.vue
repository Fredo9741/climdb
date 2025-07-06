<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'

// Props depuis le contrôleur
const props = defineProps<{
  stats: {
    clients: number
    sites: number
    equipements: number
    pannesActives: number
    interventionsMois: number
    vehicules: number
    bouteillesGaz: number
    facturesEnAttente: number
    chiffreAffaireMois: number
    devisEnCours: number
  }
  statsDetaillees: {
    pannesParPriorite: {
      faible: number
      moyenne: number
      haute: number
      urgente: number
    }
    interventionsParStatut: {
      programmee: number
      en_cours: number
      terminee: number
      annulee: number
    }
    bouteillesParStatut: {
      disponible: number
      en_service: number
      vide: number
    }
  }
  activiteRecente: {
    interventions: Array<any>
    pannes: Array<any>
    devis: Array<any>
  }
  pannesUrgentes: Array<any>
  actionsRapides: Array<{
    label: string
    route: string
    icon: string
    color: string
  }>
  isAdmin: boolean
  isTechnicien: boolean
  devisEnAttente?: Array<any>
  facturesImpayees?: Array<any>
  mesInterventions?: Array<any>
  mesStatsTechnicien?: {
    interventionsTotal: number
    interventionsMois: number
    interventionsTerminees: number
    interventionsEnCours: number
  }
  evolutionCA?: Array<{ mois: string; montant: number }>
}>()

const page = usePage()

// Computed properties pour les rôles
const userRole = computed(() => {
  const roles = page.props.auth.user.roles
  if (roles.includes('admin')) return 'Administrateur'
  if (roles.includes('technicien')) return 'Technicien'
  return 'Utilisateur'
})

// Computed pour les couleurs des priorités
const priorityColors: { [key: string]: string } = {
  faible: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
  moyenne: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
  haute: 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400',
  urgente: 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
}

// Computed pour les couleurs des statuts
const statusColors: { [key: string]: string } = {
  programmee: 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
  en_cours: 'bg-amber-100 text-amber-800 dark:bg-amber-900/20 dark:text-amber-400',
  terminee: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
  annulee: 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
}

// Actions rapides selon le rôle
const navigateToAction = (route: string) => {
  router.visit(route)
}

// Méthodes utilitaires
const formatPrice = (price: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR'
  }).format(price)
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const formatDateTime = (date: string) => {
  return new Date(date).toLocaleString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Navigation
const voirPanne = (id: number) => {
  router.visit(`/pannes/${id}`)
}

const voirIntervention = (id: number) => {
  router.visit(`/interventions/${id}`)
}

const voirDevis = (id: number) => {
  router.visit(`/devis/${id}`)
}

const voirFacture = (id: number) => {
  router.visit(`/factures/${id}`)
}

const intervenir = (panneId: number) => {
  router.visit(`/interventions/create?panne_id=${panneId}`)
}

// Couleurs pour les actions rapides
const getActionColor = (color: string) => {
  const colors: { [key: string]: string } = {
    blue: 'bg-blue-600 hover:bg-blue-700',
    green: 'bg-green-600 hover:bg-green-700',
    purple: 'bg-purple-600 hover:bg-purple-700',
    orange: 'bg-orange-600 hover:bg-orange-700',
    red: 'bg-red-600 hover:bg-red-700'
  }
  return colors[color] || 'bg-gray-600 hover:bg-gray-700'
}
</script>

<template>
  <AppLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Tableau de bord
          </h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">
            Bienvenue, {{ $page.props.auth.user.name }}
          </p>
        </div>
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
            {{ userRole }}
          </span>
        </div>
      </div>
    </template>

    <!-- Actions rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <Card 
        v-for="action in actionsRapides" 
        :key="action.route"
        class="cursor-pointer hover:shadow-lg transition-shadow"
        @click="navigateToAction(action.route)"
      >
        <CardContent class="p-4">
          <div class="flex items-center gap-3">
            <div :class="`p-2 rounded-lg ${getActionColor(action.color)}`">
              <Icon :name="action.icon" class="h-5 w-5 text-white" />
            </div>
            <span class="font-medium text-gray-900 dark:text-white">
              {{ action.label }}
            </span>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
      <Card>
        <CardContent class="p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Clients</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.clients }}</p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
              <Icon name="Users" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Sites</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.sites }}</p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
              <Icon name="MapPin" class="h-6 w-6 text-green-600 dark:text-green-400" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Équipements</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.equipements }}</p>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
              <Icon name="Settings" class="h-6 w-6 text-purple-600 dark:text-purple-400" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Pannes actives</p>
              <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ stats.pannesActives }}</p>
            </div>
            <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-lg">
              <Icon name="AlertTriangle" class="h-6 w-6 text-red-600 dark:text-red-400" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Interventions mois</p>
              <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ stats.interventionsMois }}</p>
            </div>
            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg">
              <Icon name="Wrench" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Statistiques métier -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Répartition des pannes -->
      <Card>
        <CardHeader>
          <CardTitle class="text-lg">Répartition des pannes</CardTitle>
          <CardDescription>Par niveau de priorité</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">Urgente</span>
              <div class="flex items-center gap-2">
                <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                  <div 
                    class="h-2 bg-red-500 rounded-full" 
                    :style="`width: ${(statsDetaillees.pannesParPriorite.urgente / Math.max(1, stats.pannesActives)) * 100}%`"
                  ></div>
                </div>
                <span class="text-sm font-bold">{{ statsDetaillees.pannesParPriorite.urgente }}</span>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">Haute</span>
              <div class="flex items-center gap-2">
                <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                  <div 
                    class="h-2 bg-orange-500 rounded-full" 
                    :style="`width: ${(statsDetaillees.pannesParPriorite.haute / Math.max(1, stats.pannesActives)) * 100}%`"
                  ></div>
                </div>
                <span class="text-sm font-bold">{{ statsDetaillees.pannesParPriorite.haute }}</span>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">Moyenne</span>
              <div class="flex items-center gap-2">
                <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                  <div 
                    class="h-2 bg-yellow-500 rounded-full" 
                    :style="`width: ${(statsDetaillees.pannesParPriorite.moyenne / Math.max(1, stats.pannesActives)) * 100}%`"
                  ></div>
                </div>
                <span class="text-sm font-bold">{{ statsDetaillees.pannesParPriorite.moyenne }}</span>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">Faible</span>
              <div class="flex items-center gap-2">
                <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                  <div 
                    class="h-2 bg-green-500 rounded-full" 
                    :style="`width: ${(statsDetaillees.pannesParPriorite.faible / Math.max(1, stats.pannesActives)) * 100}%`"
                  ></div>
                </div>
                <span class="text-sm font-bold">{{ statsDetaillees.pannesParPriorite.faible }}</span>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- État des interventions -->
      <Card>
        <CardHeader>
          <CardTitle class="text-lg">État des interventions</CardTitle>
          <CardDescription>Statut des interventions</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">Programmées</span>
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                {{ statsDetaillees.interventionsParStatut.programmee }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">En cours</span>
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/20 dark:text-amber-400">
                {{ statsDetaillees.interventionsParStatut.en_cours }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">Terminées</span>
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ statsDetaillees.interventionsParStatut.terminee }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">Annulées</span>
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                {{ statsDetaillees.interventionsParStatut.annulee }}
              </span>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Gestion du gaz -->
      <Card>
        <CardHeader>
          <CardTitle class="text-lg">Gestion du gaz</CardTitle>
          <CardDescription>État des bouteilles</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm font-medium">Disponibles</span>
              </div>
              <span class="text-sm font-bold">{{ statsDetaillees.bouteillesParStatut.disponible }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <span class="text-sm font-medium">En service</span>
              </div>
              <span class="text-sm font-bold">{{ statsDetaillees.bouteillesParStatut.en_service }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-sm font-medium">Vides</span>
              </div>
              <span class="text-sm font-bold">{{ statsDetaillees.bouteillesParStatut.vide }}</span>
            </div>
          </div>
          <div class="mt-4 pt-4 border-t">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium">Total bouteilles</span>
              <span class="text-sm font-bold">{{ stats.bouteillesGaz }}</span>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Section spécifique aux admins -->
    <div v-if="isAdmin" class="space-y-6 mb-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistiques financières -->
        <Card>
          <CardHeader>
            <CardTitle class="text-lg">Situation financière</CardTitle>
            <CardDescription>Chiffres clés du mois</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-sm font-medium">CA du mois</span>
                <span class="text-lg font-bold text-green-600">{{ formatPrice(stats.chiffreAffaireMois) }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm font-medium">Factures en attente</span>
                <span class="text-lg font-bold text-orange-600">{{ stats.facturesEnAttente }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm font-medium">Devis en cours</span>
                <span class="text-lg font-bold text-blue-600">{{ stats.devisEnCours }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm font-medium">Véhicules</span>
                <span class="text-lg font-bold text-purple-600">{{ stats.vehicules }}</span>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Devis en attente -->
        <Card v-if="devisEnAttente && devisEnAttente.length > 0">
          <CardHeader>
            <CardTitle class="text-lg">Devis en attente</CardTitle>
            <CardDescription>Devis envoyés aux clients</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="space-y-3">
              <div 
                v-for="devis in devisEnAttente" 
                :key="devis.id"
                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
              >
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ devis.client.nom }}</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ devis.numero }}</p>
                </div>
                <div class="text-right">
                  <p class="font-semibold">{{ formatPrice(devis.montant_ttc) }}</p>
                  <Button size="sm" @click="voirDevis(devis.id)">Voir</Button>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <!-- Section spécifique aux techniciens -->
    <div v-if="isTechnicien" class="space-y-6 mb-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Mes statistiques -->
        <Card>
          <CardHeader>
            <CardTitle class="text-lg">Mes statistiques</CardTitle>
            <CardDescription>Votre activité</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-2 gap-4">
              <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ mesStatsTechnicien?.interventionsTotal || 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total interventions</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ mesStatsTechnicien?.interventionsMois || 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Ce mois</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ mesStatsTechnicien?.interventionsTerminees || 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Terminées</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ mesStatsTechnicien?.interventionsEnCours || 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">En cours</div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Mes interventions -->
        <Card v-if="mesInterventions && mesInterventions.length > 0">
          <CardHeader>
            <CardTitle class="text-lg">Mes interventions</CardTitle>
            <CardDescription>Interventions programmées et en cours</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="space-y-3">
              <div 
                v-for="intervention in mesInterventions" 
                :key="intervention.id"
                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
              >
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ intervention.panne?.description_panne || 'Maintenance' }}</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ intervention.panne?.equipement?.site?.nom || 'Site' }}</p>
                  <p class="text-xs text-gray-500">{{ formatDateTime(intervention.date_planifiee) }}</p>
                </div>
                <div class="text-right">
                  <span :class="`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${statusColors[intervention.statut]}`">
                    {{ intervention.statut }}
                  </span>
                  <Button size="sm" class="ml-2" @click="voirIntervention(intervention.id)">Voir</Button>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <!-- Pannes urgentes -->
    <Card class="mb-8">
      <CardHeader>
        <CardTitle class="flex items-center gap-2">
          <Icon name="AlertTriangle" class="h-5 w-5 text-red-500" />
          Pannes urgentes
          <span class="text-sm font-normal text-gray-500">({{ pannesUrgentes.length }})</span>
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div v-if="pannesUrgentes.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
          <Icon name="CheckCircle" class="h-12 w-12 mx-auto mb-2 text-green-500" />
          <p>Aucune panne urgente ! 🎉</p>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div 
            v-for="panne in pannesUrgentes" 
            :key="panne.id"
            class="p-4 bg-red-50 dark:bg-red-900/10 rounded-lg border border-red-200 dark:border-red-800"
          >
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <h4 class="font-semibold text-gray-900 dark:text-white">{{ panne.description_panne }}</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                  {{ panne.equipement?.site?.nom || 'Site non défini' }}
                </p>
                <div class="flex items-center gap-2 mt-2">
                  <span :class="`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${priorityColors[panne.priorite]}`">
                    {{ panne.priorite }}
                  </span>
                  <span class="text-xs text-gray-500">
                    {{ formatDate(panne.date_constat) }}
                  </span>
                </div>
              </div>
              <div class="flex gap-2 ml-4">
                <Button size="sm" variant="outline" @click="voirPanne(panne.id)">
                  Voir
                </Button>
                <Button size="sm" @click="intervenir(panne.id)" v-if="isTechnicien || isAdmin">
                  Intervenir
                </Button>
              </div>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Activité récente -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Interventions récentes -->
      <Card>
        <CardHeader>
          <CardTitle class="text-lg">Interventions récentes</CardTitle>
          <CardDescription>Dernières 7 jours</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div v-if="activiteRecente.interventions.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
              <p class="text-sm">Aucune intervention récente</p>
            </div>
            <div 
              v-for="intervention in activiteRecente.interventions" 
              :key="intervention.id"
              class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
              @click="voirIntervention(intervention.id)"
            >
              <div>
                <p class="font-medium text-gray-900 dark:text-white">{{ intervention.panne?.description_panne || 'Maintenance' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ intervention.technicien?.name || 'Technicien' }}</p>
              </div>
              <span :class="`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${statusColors[intervention.statut]}`">
                {{ intervention.statut }}
              </span>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Pannes récentes -->
      <Card>
        <CardHeader>
          <CardTitle class="text-lg">Pannes récentes</CardTitle>
          <CardDescription>Dernières 7 jours</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div v-if="activiteRecente.pannes.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
              <p class="text-sm">Aucune panne récente</p>
            </div>
            <div 
              v-for="panne in activiteRecente.pannes" 
              :key="panne.id"
              class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
              @click="voirPanne(panne.id)"
            >
              <div>
                <p class="font-medium text-gray-900 dark:text-white">{{ panne.description_panne }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ panne.equipement?.site?.nom || 'Site' }}</p>
              </div>
              <span :class="`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${priorityColors[panne.priorite]}`">
                {{ panne.priorite }}
              </span>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Devis récents -->
      <Card>
        <CardHeader>
          <CardTitle class="text-lg">Devis récents</CardTitle>
          <CardDescription>Dernières 7 jours</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div v-if="activiteRecente.devis.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
              <p class="text-sm">Aucun devis récent</p>
            </div>
            <div 
              v-for="devis in activiteRecente.devis" 
              :key="devis.id"
              class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
              @click="voirDevis(devis.id)"
            >
              <div>
                <p class="font-medium text-gray-900 dark:text-white">{{ devis.client.nom }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ devis.numero }}</p>
              </div>
              <div class="text-right">
                <p class="text-sm font-semibold">{{ formatPrice(devis.montant_ttc) }}</p>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                  {{ devis.statut }}
                </span>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Styles additionnels pour les animations et transitions */
.card-hover {
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card-hover:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>
