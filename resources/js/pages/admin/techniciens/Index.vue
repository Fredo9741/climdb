<template>
  <AppLayout title="Gestion des Techniciens">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
          Gestion des Techniciens
        </h2>
        <Button @click="router.visit(route('admin.techniciens.create'))" class="gap-2">
          <Icon name="plus" class="h-4 w-4" />
          Nouveau Technicien
        </Button>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Statistiques -->
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <Card>
            <CardContent class="p-6">
              <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg dark:bg-blue-900">
                  <Icon name="users" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Techniciens</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ techniciens.length }}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardContent class="p-6">
              <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg dark:bg-green-900">
                  <Icon name="check-circle" class="h-6 w-6 text-green-600 dark:text-green-400" />
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Habilitations Valides</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ totalHabilitationsValides }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardContent class="p-6">
              <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg dark:bg-yellow-900">
                  <Icon name="alert-triangle" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Expirent Bientôt</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ totalExpirantBientot }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardContent class="p-6">
              <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg dark:bg-red-900">
                  <Icon name="x-circle" class="h-6 w-6 text-red-600 dark:text-red-400" />
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Expirées</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ totalExpirees }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Tableau des techniciens -->
        <Card>
          <CardHeader>
            <CardTitle>Liste des Techniciens</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-gray-100">Nom</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-gray-100">Contact</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-gray-100">Qualification</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-gray-100">Habilitations</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-gray-100">Interventions</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-gray-100">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="technicien in techniciens" :key="technicien.id" class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="py-4 px-4">
                      <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ technicien.name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ technicien.id }}</p>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <div>
                        <p class="text-gray-900 dark:text-gray-100">{{ technicien.email }}</p>
                        <p v-if="technicien.telephone" class="text-sm text-gray-500 dark:text-gray-400">
                          {{ technicien.telephone }}
                        </p>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <p v-if="technicien.qualification" class="text-gray-900 dark:text-gray-100">
                        {{ technicien.qualification }}
                      </p>
                      <p v-else class="text-gray-500 dark:text-gray-400">Non définie</p>
                    </td>
                    <td class="py-4 px-4">
                      <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                          <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ technicien.habilitations_count }} habilitation(s)
                          </span>
                        </div>
                        <div v-if="technicien.habilitations_expirees > 0" class="flex items-center gap-1">
                          <Icon name="x-circle" class="h-3 w-3 text-red-500" />
                          <span class="text-xs text-red-600 dark:text-red-400">
                            {{ technicien.habilitations_expirees }} expirée(s)
                          </span>
                        </div>
                        <div v-if="technicien.habilitations_expirant_bientot > 0" class="flex items-center gap-1">
                          <Icon name="alert-triangle" class="h-3 w-3 text-yellow-500" />
                          <span class="text-xs text-yellow-600 dark:text-yellow-400">
                            {{ technicien.habilitations_expirant_bientot }} expire(nt) bientôt
                          </span>
                        </div>
                      </div>
                    </td>
                    <td class="py-4 px-4">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ technicien.interventions_count }} intervention(s)
                      </span>
                    </td>
                    <td class="py-4 px-4">
                      <div class="flex items-center gap-2">
                        <Button
                          @click="router.visit(route('admin.techniciens.show', technicien.id))"
                          variant="outline"
                          size="sm"
                        >
                          <Icon name="eye" class="h-4 w-4" />
                        </Button>
                        <Button
                          @click="router.visit(route('admin.techniciens.edit', technicien.id))"
                          variant="outline"
                          size="sm"
                        >
                          <Icon name="edit" class="h-4 w-4" />
                        </Button>
                        <Button
                          @click="deleteTechnicien(technicien)"
                          variant="outline"
                          size="sm"
                          class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                        >
                          <Icon name="trash" class="h-4 w-4" />
                        </Button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Message si aucun technicien -->
            <div v-if="techniciens.length === 0" class="text-center py-12">
              <Icon name="users" class="mx-auto h-12 w-12 text-gray-400" />
              <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucun technicien</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Commencez par créer votre premier technicien.
              </p>
              <div class="mt-6">
                <Button @click="router.visit(route('admin.techniciens.create'))">
                  <Icon name="plus" class="h-4 w-4 mr-2" />
                  Créer le premier technicien
                </Button>
              </div>
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

interface Technicien {
  id: number
  name: string
  email: string
  telephone: string | null
  qualification: string | null
  created_at: string
  habilitations_count: number
  habilitations_expirees: number
  habilitations_expirant_bientot: number
  interventions_count: number
}

interface Props {
  techniciens: Technicien[]
}

const props = defineProps<Props>()

// Computed properties pour les statistiques
const totalHabilitationsValides = computed(() => {
  return props.techniciens.reduce((total, tech) => {
    return total + (tech.habilitations_count - tech.habilitations_expirees)
  }, 0)
})

const totalExpirantBientot = computed(() => {
  return props.techniciens.reduce((total, tech) => total + tech.habilitations_expirant_bientot, 0)
})

const totalExpirees = computed(() => {
  return props.techniciens.reduce((total, tech) => total + tech.habilitations_expirees, 0)
})

// Fonction pour supprimer un technicien
const deleteTechnicien = (technicien: Technicien) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer le technicien "${technicien.name}" ?`)) {
    router.delete(route('admin.techniciens.destroy', technicien.id))
  }
}
</script> 