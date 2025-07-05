<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Créer une nouvelle panne
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Enregistrer une nouvelle panne d'équipement
          </p>
        </div>
        <Button @click="$inertia.visit(route('pannes.index'))" variant="outline">
          <Icon name="arrow-left" class="h-4 w-4 mr-2" />
          Retour à la liste
        </Button>
      </div>
    </template>

    <div class="max-w-2xl mx-auto">
      <Card>
        <CardContent class="p-6">
          <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Sélection de l'équipement -->
            <div>
              <Label for="equipement_id" class="required">Équipement concerné</Label>
              <select
                id="equipement_id"
                v-model="form.equipement_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                required
              >
                <option value="">Sélectionnez un équipement</option>
                <option v-for="equipement in equipements" :key="equipement.id" :value="equipement.id">
                  {{ equipement.nom }} - {{ equipement.site?.nom }}
                </option>
              </select>
            </div>

            <!-- Description de la panne -->
            <div>
              <Label for="description_panne" class="required">Description de la panne</Label>
              <textarea
                id="description_panne"
                v-model="form.description_panne"
                rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                placeholder="Décrivez en détail les symptômes observés..."
                required
              ></textarea>
            </div>

            <!-- Priorité -->
            <div>
              <Label for="priorite" class="required">Priorité</Label>
              <select
                id="priorite"
                v-model="form.priorite"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                required
              >
                <option value="faible">Faible</option>
                <option value="moyenne">Moyenne</option>
                <option value="haute">Haute</option>
                <option value="urgente">Urgente</option>
              </select>
            </div>

            <!-- Date de constat -->
            <div>
              <Label for="date_constat" class="required">Date et heure de constat</Label>
              <Input
                id="date_constat"
                v-model="form.date_constat"
                type="datetime-local"
                class="mt-1"
                required
              />
            </div>

            <!-- Actions correctives -->
            <div>
              <Label for="actions_correctives">Actions correctives tentées</Label>
              <textarea
                id="actions_correctives"
                v-model="form.actions_correctives"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                placeholder="Actions déjà tentées..."
              ></textarea>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4">
              <Button
                type="button"
                variant="outline"
                @click="$inertia.visit(route('pannes.index'))"
              >
                Annuler
              </Button>
              <Button type="submit" class="gap-2">
                <Icon name="save" class="h-4 w-4" />
                Enregistrer
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import Icon from '@/components/Icon.vue'

const props = defineProps({
  equipements: {
    type: Array,
    default: () => []
  }
})

// État du formulaire
const form = useForm({
  equipement_id: '',
  description_panne: '',
  priorite: 'moyenne',
  date_constat: new Date().toISOString().slice(0, 16),
  actions_correctives: ''
})

// Soumettre le formulaire
const submitForm = () => {
  form.post(route('pannes.store'))
}
</script>

<style scoped>
.required::after {
  content: " *";
  color: #ef4444;
}
</style> 