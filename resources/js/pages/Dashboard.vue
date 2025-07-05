<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'

// Props depuis le contrôleur
const props = defineProps<{
  stats: {
    clients: number
    equipements: number
    pannesActives: number
    interventionsMois: number
  }
  pannesUrgentes: Array<any>
  devisEnAttente?: Array<any>
  facturesImpayees?: Array<any>
  mesInterventions?: Array<any>
}>()

const page = usePage()

// Computed properties pour les rôles
const userRole = computed(() => {
  const roles = page.props.auth.user.roles
  if (roles.includes('admin')) return 'Administrateur'
  if (roles.includes('technicien')) return 'Technicien'
  return 'Utilisateur'
})

const isAdmin = computed(() => page.props.auth.user.roles.includes('admin'))
const isTechnicien = computed(() => page.props.auth.user.roles.includes('technicien'))

// Permissions
const canCreatePanne = computed(() => isAdmin.value || isTechnicien.value)
const canCreateIntervention = computed(() => isAdmin.value || isTechnicien.value)
const canIntervenir = computed(() => isAdmin.value || isTechnicien.value)

// Methods
const navigateTo = (url: string) => {
  router.visit(url)
}

const voirPanne = (id: number) => {
  router.visit(`/pannes/${id}`)
}

const intervenir = (panneId: number) => {
  router.visit(`/interventions/create?panne=${panneId}`)
}

const voirDevis = (id: number) => {
  router.visit(`/devis/${id}`)
}

const voirFacture = (id: number) => {
  router.visit(`/factures/${id}`)
}

const voirIntervention = (id: number) => {
  router.visit(`/interventions/${id}`)
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR'
  }).format(price)
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
      <Card>
        <CardContent class="p-4 sm:p-6">
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
        <CardContent class="p-4 sm:p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Équipements</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.equipements }}</p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
              <Icon name="Settings" class="h-6 w-6 text-green-600 dark:text-green-400" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-4 sm:p-6">
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
        <CardContent class="p-4 sm:p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Interventions du mois</p>
              <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ stats.interventionsMois }}</p>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
              <Icon name="Wrench" class="h-6 w-6 text-purple-600 dark:text-purple-400" />
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Pannes urgentes -->
      <Card class="lg:col-span-2">
        <CardHeader>
          <CardTitle class="flex items-center gap-2">
            <Icon name="AlertTriangle" class="h-5 w-5 text-red-500" />
            Pannes urgentes
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div v-if="pannesUrgentes.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
              <Icon name="CheckCircle" class="h-12 w-12 mx-auto mb-2 text-green-500" />
              <p>Aucune panne urgente ! 🎉</p>
            </div>
            <div 
              v-for="panne in pannesUrgentes" 
              :key="panne.id"
              class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-red-50 dark:bg-red-900/10 rounded-lg border border-red-200 dark:border-red-800"
            >
              <div class="flex-1 mb-3 sm:mb-0">
                <h4 class="font-semibold text-gray-900 dark:text-white">{{ panne.titre }}</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  {{ panne.equipement.nom }} - {{ panne.equipement.site.nom }}
                </p>
                <div class="flex items-center gap-2 mt-1">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                    {{ panne.priorite }}
                  </span>
                  <span class="text-xs text-gray-500">
                    {{ formatDate(panne.date_declaration) }}
                  </span>
                </div>
              </div>
              <div class="flex gap-2">
                <Button size="sm" variant="outline" @click="voirPanne(panne.id)">
                  Voir
                </Button>
                <Button size="sm" @click="intervenir(panne.id)" v-if="canIntervenir">
                  Intervenir
                </Button>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Actions rapides -->
      <Card>
        <CardHeader>
          <CardTitle>Actions rapides</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <Button 
              class="w-full justify-start" 
              variant="outline"
              @click="navigateTo('/pannes/create')"
              v-if="canCreatePanne"
            >
              <Icon name="Plus" class="h-4 w-4 mr-2" />
              Déclarer une panne
            </Button>
            
            <Button 
              class="w-full justify-start" 
              variant="outline"
              @click="navigateTo('/interventions/create')"
              v-if="canCreateIntervention"
            >
              <Icon name="Wrench" class="h-4 w-4 mr-2" />
              Nouvelle intervention
            </Button>
            
            <Button 
              class="w-full justify-start" 
              variant="outline"
              @click="navigateTo('/clients/create')"
              v-if="isAdmin"
            >
              <Icon name="UserPlus" class="h-4 w-4 mr-2" />
              Ajouter un client
            </Button>
            
            <Button 
              class="w-full justify-start" 
              variant="outline"
              @click="navigateTo('/devis/create')"
              v-if="isAdmin"
            >
              <Icon name="FileText" class="h-4 w-4 mr-2" />
              Créer un devis
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Tableaux récapitulatifs pour admin -->
    <div v-if="isAdmin" class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <!-- Devis en attente -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center justify-between">
            <span>Devis en attente</span>
            <Button size="sm" variant="ghost" @click="navigateTo('/devis')">
              Voir tout
            </Button>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div v-if="!devisEnAttente || devisEnAttente.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
              <p>Aucun devis en attente</p>
            </div>
            <div 
              v-for="devis in devisEnAttente || []" 
              :key="devis.id"
              class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/10 rounded-lg"
            >
              <div>
                <p class="font-medium text-gray-900 dark:text-white">{{ devis.numero }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ devis.client.nom }}</p>
                <p class="text-sm text-green-600 dark:text-green-400 font-medium">{{ formatPrice(devis.montant_ttc) }}</p>
              </div>
              <Button size="sm" @click="voirDevis(devis.id)">
                Voir
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Factures impayées -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center justify-between">
            <span>Factures impayées</span>
            <Button size="sm" variant="ghost" @click="navigateTo('/factures')">
              Voir tout
            </Button>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div v-if="!facturesImpayees || facturesImpayees.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
              <p>Toutes les factures sont payées ! 🎉</p>
            </div>
            <div 
              v-for="facture in facturesImpayees || []" 
              :key="facture.id"
              class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/10 rounded-lg"
            >
              <div>
                <p class="font-medium text-gray-900 dark:text-white">{{ facture.numero }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ facture.client.nom }}</p>
                <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ formatPrice(facture.montant_ttc) }}</p>
              </div>
              <Button size="sm" @click="voirFacture(facture.id)">
                Voir
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Planning technicien -->
    <div v-if="isTechnicien" class="mt-6">
      <Card>
        <CardHeader>
          <CardTitle>Mes interventions programmées</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-3">
            <div v-if="!mesInterventions || mesInterventions.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
              <Icon name="Calendar" class="h-12 w-12 mx-auto mb-2" />
              <p>Aucune intervention programmée</p>
            </div>
            <div 
              v-for="intervention in mesInterventions || []" 
              :key="intervention.id"
              class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg"
            >
              <div class="flex-1 mb-3 sm:mb-0">
                <h4 class="font-semibold text-gray-900 dark:text-white">{{ intervention.description }}</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  {{ intervention.equipement.nom }} - {{ intervention.equipement.site.nom }}
                </p>
                <div class="flex items-center gap-2 mt-1">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                    {{ intervention.type_intervention }}
                  </span>
                  <span class="text-xs text-gray-500">
                    {{ formatDate(intervention.date_debut) }}
                  </span>
                </div>
              </div>
              <Button size="sm" @click="voirIntervention(intervention.id)">
                Voir détails
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Section de test des liens (temporaire) -->
    <div v-if="isAdmin" class="mt-8 p-6 bg-yellow-50 dark:bg-yellow-900/10 rounded-lg border border-yellow-200 dark:border-yellow-800">
      <h3 class="text-lg font-semibold mb-4 text-yellow-800 dark:text-yellow-400">🔧 Test des liens de navigation</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <Button variant="outline" size="sm" @click="navigateTo('/clients')" class="justify-start">
          <Icon name="Users" class="h-4 w-4 mr-2" />
          Clients
        </Button>
        <Button variant="outline" size="sm" @click="navigateTo('/sites')" class="justify-start">
          <Icon name="MapPin" class="h-4 w-4 mr-2" />
          Sites
        </Button>
        <Button variant="outline" size="sm" @click="navigateTo('/modeles')" class="justify-start">
          <Icon name="Package" class="h-4 w-4 mr-2" />
          Modèles
        </Button>
        <Button variant="outline" size="sm" @click="navigateTo('/equipements')" class="justify-start">
          <Icon name="Settings" class="h-4 w-4 mr-2" />
          Équipements
        </Button>
        <Button variant="outline" size="sm" @click="navigateTo('/pannes')" class="justify-start">
          <Icon name="AlertTriangle" class="h-4 w-4 mr-2" />
          Pannes
        </Button>
        <Button variant="outline" size="sm" @click="navigateTo('/interventions')" class="justify-start">
          <Icon name="Wrench" class="h-4 w-4 mr-2" />
          Interventions
        </Button>
        <Button variant="outline" size="sm" @click="navigateTo('/devis')" class="justify-start">
          <Icon name="FileText" class="h-4 w-4 mr-2" />
          Devis
        </Button>
        <Button variant="outline" size="sm" @click="navigateTo('/factures')" class="justify-start">
          <Icon name="Receipt" class="h-4 w-4 mr-2" />
          Factures
        </Button>
        <Button variant="outline" size="sm" @click="navigateTo('/dashboard')" class="justify-start">
          <Icon name="LayoutGrid" class="h-4 w-4 mr-2" />
          Dashboard
        </Button>
      </div>
    </div>
  </AppLayout>
</template>
