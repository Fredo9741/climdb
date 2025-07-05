<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages de feedback -->
        <div v-if="$page.props.flash?.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash?.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ $page.props.flash.error }}
        </div>

        <!-- En-tête -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
              <div>
                <h1 class="text-2xl font-bold text-gray-900">Détails de la panne</h1>
                <p class="text-gray-600 mt-1">
                  Panne #{{ panne.id }} - {{ panne.equipement?.nom }}
                </p>
              </div>
              <div class="flex gap-2">
                <Button @click="router.visit(route('pannes.edit', panne.id))" class="bg-blue-600 hover:bg-blue-700 text-white">
                  <Icon name="Edit" class="h-4 w-4 mr-2" />
                  Modifier
                </Button>
                <Button @click="router.visit(route('pannes.index'))" variant="outline">
                  <Icon name="ArrowLeft" class="h-4 w-4 mr-2" />
                  Retour
                </Button>
              </div>
            </div>
          </div>
        </div>

        <!-- Informations principales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
          <!-- Statut et priorité -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Icon name="AlertTriangle" class="h-5 w-5" />
                Statut de la panne
              </CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut actuel</label>
                <span :class="getStatusClass(panne.statut_demande_id)" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">
                  {{ formatStatus(panne.statut_demande_id) }}
                </span>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                <span :class="getPriorityClass(panne.priorite)" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">
                  {{ formatPriority(panne.priorite) }}
                </span>
              </div>
              <div v-if="panne.date_resolution">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de résolution</label>
                <p class="text-sm text-gray-900">{{ formatDate(panne.date_resolution) }}</p>
              </div>
            </CardContent>
          </Card>

          <!-- Informations temporelles -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Icon name="Clock" class="h-5 w-5" />
                Chronologie
              </CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de constat</label>
                <p class="text-sm text-gray-900">{{ formatDate(panne.date_constat) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Signalée par</label>
                <p class="text-sm text-gray-900">{{ panne.user?.name || 'Utilisateur inconnu' }}</p>
              </div>
              <div v-if="panne.date_resolution">
                <label class="block text-sm font-medium text-gray-700 mb-1">Durée de résolution</label>
                <p class="text-sm text-gray-900">{{ calculateDuration(panne.date_constat, panne.date_resolution) }}</p>
              </div>
            </CardContent>
          </Card>

          <!-- Équipement concerné -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Icon name="Cpu" class="h-5 w-5" />
                Équipement
              </CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <p class="text-sm text-gray-900 font-medium">{{ panne.equipement?.nom }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Site</label>
                <p class="text-sm text-gray-900">{{ panne.equipement?.site?.nom }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <p class="text-sm text-gray-900">
                  {{ panne.equipement?.site?.client?.nom_entreprise || panne.equipement?.site?.client?.nom }}
                </p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Modèle</label>
                <p class="text-sm text-gray-900">{{ panne.equipement?.modele?.nom }}</p>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Description détaillée -->
        <Card class="mb-6">
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Icon name="FileText" class="h-5 w-5" />
              Description de la panne
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="bg-gray-50 p-4 rounded-md">
              <p class="text-gray-900 whitespace-pre-wrap">{{ panne.description_panne }}</p>
            </div>
          </CardContent>
        </Card>

        <!-- Actions correctives -->
        <Card v-if="panne.actions_correctives" class="mb-6">
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Icon name="Wrench" class="h-5 w-5" />
              Actions correctives
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="bg-blue-50 p-4 rounded-md">
              <p class="text-gray-900 whitespace-pre-wrap">{{ panne.actions_correctives }}</p>
            </div>
          </CardContent>
        </Card>

        <!-- Interventions liées -->
        <Card v-if="panne.interventions && panne.interventions.length > 0">
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Icon name="Settings" class="h-5 w-5" />
              Interventions liées
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="space-y-4">
              <div 
                v-for="intervention in panne.interventions" 
                :key="intervention.id"
                class="border rounded-lg p-4 hover:bg-gray-50 transition-colors"
              >
                <div class="flex justify-between items-start mb-2">
                  <h4 class="font-medium text-gray-900">{{ intervention.type_intervention }}</h4>
                  <span :class="getInterventionStatusClass(intervention.statut)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ formatInterventionStatus(intervention.statut) }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ intervention.description_travaux }}</p>
                <div class="text-xs text-gray-500">
                  Date d'intervention : {{ formatDate(intervention.date_intervention) }}
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Actions rapides -->
        <Card v-if="panne.statut_demande_id !== 3">
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Icon name="Zap" class="h-5 w-5" />
              Actions rapides
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="flex flex-wrap gap-2">
              <Button 
                v-if="panne.statut_demande_id === 1" 
                @click="updateStatus(2)"
                class="bg-blue-600 hover:bg-blue-700 text-white"
              >
                <Icon name="Play" class="h-4 w-4 mr-2" />
                Prendre en charge
              </Button>
              <Button 
                v-if="panne.statut_demande_id === 2" 
                @click="updateStatus(3)"
                class="bg-green-600 hover:bg-green-700 text-white"
              >
                <Icon name="CheckCircle" class="h-4 w-4 mr-2" />
                Marquer comme résolue
              </Button>
              <Button 
                @click="router.visit(route('interventions.create', { panne_id: panne.id }))"
                variant="outline"
              >
                <Icon name="Plus" class="h-4 w-4 mr-2" />
                Créer une intervention
              </Button>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardHeader from '@/components/ui/card/CardHeader.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'
import Icon from '@/components/Icon.vue'

const props = defineProps<{
  panne: any
}>()

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatPriority = (priority: string) => {
  const priorities: Record<string, string> = {
    'faible': 'Faible',
    'moyenne': 'Moyenne', 
    'haute': 'Haute',
    'urgente': 'Urgente'
  }
  return priorities[priority] || priority
}

const formatStatus = (statusId: number) => {
  const statuses: Record<number, string> = {
    1: 'En attente',
    2: 'En cours',
    3: 'Résolue'
  }
  return statuses[statusId] || 'Inconnu'
}

const formatInterventionStatus = (status: string) => {
  const statuses: Record<string, string> = {
    'planifiee': 'Planifiée',
    'en_cours': 'En cours',
    'terminee': 'Terminée'
  }
  return statuses[status] || status
}

const getPriorityClass = (priority: string) => {
  const classes: Record<string, string> = {
    'faible': 'bg-gray-100 text-gray-800',
    'moyenne': 'bg-yellow-100 text-yellow-800',
    'haute': 'bg-orange-100 text-orange-800',
    'urgente': 'bg-red-100 text-red-800'
  }
  return classes[priority] || 'bg-gray-100 text-gray-800'
}

const getStatusClass = (statusId: number) => {
  const classes: Record<number, string> = {
    1: 'bg-yellow-100 text-yellow-800',
    2: 'bg-blue-100 text-blue-800',
    3: 'bg-green-100 text-green-800'
  }
  return classes[statusId] || 'bg-gray-100 text-gray-800'
}

const getInterventionStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    'planifiee': 'bg-yellow-100 text-yellow-800',
    'en_cours': 'bg-blue-100 text-blue-800',
    'terminee': 'bg-green-100 text-green-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const calculateDuration = (startDate: string, endDate: string) => {
  const start = new Date(startDate)
  const end = new Date(endDate)
  const diffTime = Math.abs(end.getTime() - start.getTime())
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  
  if (diffDays === 1) return '1 jour'
  if (diffDays < 7) return `${diffDays} jours`
  if (diffDays < 30) return `${Math.floor(diffDays / 7)} semaine${Math.floor(diffDays / 7) > 1 ? 's' : ''}`
  return `${Math.floor(diffDays / 30)} mois`
}

const updateStatus = (newStatusId: number) => {
  router.patch(route('pannes.update', props.panne.id), {
    statut_demande_id: newStatusId,
    ...(newStatusId === 3 && { date_resolution: new Date().toISOString() })
  }, {
    preserveScroll: true,
    onSuccess: () => {
      // Le message de succès sera affiché via flash
    }
  })
}
</script> 