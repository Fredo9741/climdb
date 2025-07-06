<template>
  <AppLayout :title="`Technicien #${technicien.id}`">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
          {{ technicien.name }}
        </h2>
        <div class="flex gap-2">
          <Button @click="router.visit(route('admin.techniciens.edit', technicien.id))" variant="outline" class="gap-1">
            <Icon name="edit" class="h-4 w-4" />
            Modifier
          </Button>
          <Button @click="router.visit(route('admin.techniciens.index'))" variant="outline">
            <Icon name="arrow-left" class="h-4 w-4" />
            Retour
          </Button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-6">
        <!-- Informations principales -->
        <Card>
          <CardHeader>
            <CardTitle>Informations du Technicien</CardTitle>
          </CardHeader>
          <CardContent class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nom complet</dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ technicien.name }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ technicien.email }}</dd>
            </div>
            <div v-if="technicien.telephone">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Téléphone</dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ technicien.telephone }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Qualification</dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                {{ technicien.qualification || 'Non définie' }}
              </dd>
            </div>
          </CardContent>
        </Card>

        <!-- Affectation véhicule actuelle -->
        <Card>
          <CardHeader>
            <CardTitle>Véhicule affecté</CardTitle>
          </CardHeader>
          <CardContent class="p-6">
            <template v-if="activeAffectation">
              <p class="text-gray-900 dark:text-gray-100">
                {{ activeAffectation.vehicule }}
              </p>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Depuis le {{ formatDate(activeAffectation.date_debut) }}
                <span v-if="activeAffectation.date_fin">
                  &nbsp;au {{ formatDate(activeAffectation.date_fin) }}
                </span>
              </p>
            </template>
            <template v-else>
              <p class="text-gray-500 dark:text-gray-400">Aucun véhicule actuellement affecté.</p>
            </template>
          </CardContent>
        </Card>

        <!-- Historique des affectations -->
        <Card v-if="technicien.affectations_vehicules.length > 0">
          <CardHeader>
            <CardTitle>Historique des affectations</CardTitle>
          </CardHeader>
          <CardContent class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Véhicule</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date début</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date fin</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="aff in technicien.affectations_vehicules" :key="aff.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                  <td class="px-4 py-2 whitespace-nowrap">{{ aff.vehicule }}</td>
                  <td class="px-4 py-2 whitespace-nowrap">{{ formatDate(aff.date_debut) }}</td>
                  <td class="px-4 py-2 whitespace-nowrap">
                    {{ aff.date_fin ? formatDate(aff.date_fin) : 'En cours' }}
                  </td>
                </tr>
              </tbody>
            </table>
          </CardContent>
        </Card>

        <!-- Statistiques -->
        <Card>
          <CardHeader>
            <CardTitle>Statistiques</CardTitle>
          </CardHeader>
          <CardContent class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center">
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Interventions</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ technicien.stats.interventions_count }}</p>
            </div>
            <div class="text-center">
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Habilitations</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ technicien.stats.habilitations_count }}</p>
            </div>
            <div class="text-center">
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Habilitations expirées</p>
              <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ technicien.stats.habilitations_expirees }}</p>
            </div>
            <div class="text-center">
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mouvements gaz</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ technicien.stats.mouvements_gaz_count }}</p>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import Icon from '@/components/Icon.vue'

interface AffectationVehicule {
  id: number
  vehicule: string
  date_debut: string
  date_fin: string | null
}

interface Stats {
  interventions_count: number
  habilitations_count: number
  habilitations_expirees: number
  mouvements_gaz_count: number
}

interface Technicien {
  id: number
  name: string
  email: string
  telephone: string | null
  qualification: string | null
  affectations_vehicules: AffectationVehicule[]
  stats: Stats
}

interface Props {
  technicien: Technicien
}

const props = defineProps<Props>()

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('fr-FR')
}

// Affectation actuelle
const activeAffectation = computed(() => {
  const affs = props.technicien.affectations_vehicules
  if (!affs || affs.length === 0) return null

  const now = new Date()
  return (
    affs.find((a) => {
      const debut = new Date(a.date_debut)
      const fin = a.date_fin ? new Date(a.date_fin) : null
      return debut <= now && (!fin || fin >= now)
    }) || affs[0]
  )
})
</script>