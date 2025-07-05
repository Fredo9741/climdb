<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                <h1 class="text-2xl font-bold text-gray-900">Modifier la panne</h1>
                <p class="text-gray-600 mt-1">
                  Panne #{{ panne.id }} - {{ panne.equipement?.nom }}
                </p>
              </div>
              <div class="flex gap-2">
                <Button @click="router.visit(route('pannes.show', panne.id))" variant="outline">
                  <Icon name="ArrowLeft" class="h-4 w-4 mr-2" />
                  Retour aux détails
                </Button>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulaire de modification -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Icon name="Edit" class="h-5 w-5" />
              Informations de la panne
            </CardTitle>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Équipement -->
              <div>
                <Label for="equipement_id" class="required">Équipement</Label>
                <select
                  id="equipement_id"
                  v-model="form.equipement_id"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': form.errors.equipement_id }"
                >
                  <option value="">Sélectionner un équipement...</option>
                  <option 
                    v-for="equipement in equipements" 
                    :key="equipement.id" 
                    :value="equipement.id"
                  >
                    {{ equipement.nom }} - {{ equipement.site?.nom }} ({{ equipement.site?.client?.nom_entreprise || equipement.site?.client?.nom }})
                  </option>
                </select>
                <InputError v-if="form.errors.equipement_id" :message="form.errors.equipement_id" />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Statut -->
                <div>
                  <Label for="statut_demande_id" class="required">Statut</Label>
                  <select
                    id="statut_demande_id"
                    v-model="form.statut_demande_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    :class="{ 'border-red-500': form.errors.statut_demande_id }"
                  >
                    <option value="">Sélectionner un statut...</option>
                    <option 
                      v-for="statut in statuts" 
                      :key="statut.id" 
                      :value="statut.id"
                    >
                      {{ statut.nom }}
                    </option>
                  </select>
                  <InputError v-if="form.errors.statut_demande_id" :message="form.errors.statut_demande_id" />
                </div>

                <!-- Priorité -->
                <div>
                  <Label for="priorite" class="required">Priorité</Label>
                  <select
                    id="priorite"
                    v-model="form.priorite"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    :class="{ 'border-red-500': form.errors.priorite }"
                  >
                    <option value="">Sélectionner une priorité...</option>
                    <option value="faible">Faible</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="haute">Haute</option>
                    <option value="urgente">Urgente</option>
                  </select>
                  <InputError v-if="form.errors.priorite" :message="form.errors.priorite" />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date de constat -->
                <div>
                  <Label for="date_constat" class="required">Date de constat</Label>
                  <Input
                    id="date_constat"
                    type="datetime-local"
                    v-model="form.date_constat"
                    class="mt-1"
                    :class="{ 'border-red-500': form.errors.date_constat }"
                  />
                  <InputError v-if="form.errors.date_constat" :message="form.errors.date_constat" />
                </div>

                <!-- Date de résolution -->
                <div>
                  <Label for="date_resolution">Date de résolution</Label>
                  <Input
                    id="date_resolution"
                    type="datetime-local"
                    v-model="form.date_resolution"
                    class="mt-1"
                    :class="{ 'border-red-500': form.errors.date_resolution }"
                  />
                  <InputError v-if="form.errors.date_resolution" :message="form.errors.date_resolution" />
                  <p class="text-xs text-gray-500 mt-1">Laissez vide si la panne n'est pas encore résolue</p>
                </div>
              </div>

              <!-- Description -->
              <div>
                <Label for="description_panne" class="required">Description de la panne</Label>
                <textarea
                  id="description_panne"
                  v-model="form.description_panne"
                  rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': form.errors.description_panne }"
                  placeholder="Décrivez en détail la panne observée..."
                ></textarea>
                <InputError v-if="form.errors.description_panne" :message="form.errors.description_panne" />
              </div>

              <!-- Actions correctives -->
              <div>
                <Label for="actions_correctives">Actions correctives</Label>
                <textarea
                  id="actions_correctives"
                  v-model="form.actions_correctives"
                  rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500': form.errors.actions_correctives }"
                  placeholder="Décrivez les actions entreprises ou prévues pour résoudre la panne..."
                ></textarea>
                <InputError v-if="form.errors.actions_correctives" :message="form.errors.actions_correctives" />
              </div>

              <!-- Actions du formulaire -->
              <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t">
                <Button 
                  type="submit" 
                  :disabled="form.processing"
                  class="bg-blue-600 hover:bg-blue-700 text-white"
                >
                  <Icon name="Save" class="h-4 w-4 mr-2" />
                  {{ form.processing ? 'Enregistrement...' : 'Enregistrer les modifications' }}
                </Button>
                
                <Button 
                  type="button"
                  @click="router.visit(route('pannes.show', panne.id))"
                  variant="outline"
                  :disabled="form.processing"
                >
                  Annuler
                </Button>

                <!-- Actions rapides -->
                <div class="flex gap-2 sm:ml-auto">
                  <Button 
                    v-if="form.statut_demande_id === 1"
                    type="button"
                    @click="quickStatusUpdate(2)"
                    class="bg-blue-600 hover:bg-blue-700 text-white"
                    :disabled="form.processing"
                  >
                    <Icon name="Play" class="h-4 w-4 mr-2" />
                    Prendre en charge
                  </Button>
                  
                  <Button 
                    v-if="form.statut_demande_id === 2"
                    type="button"
                    @click="quickStatusUpdate(3)"
                    class="bg-green-600 hover:bg-green-700 text-white"
                    :disabled="form.processing"
                  >
                    <Icon name="CheckCircle" class="h-4 w-4 mr-2" />
                    Marquer comme résolue
                  </Button>
                </div>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardHeader from '@/components/ui/card/CardHeader.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import Icon from '@/components/Icon.vue'
import InputError from '@/components/InputError.vue'

const props = defineProps<{
  panne: any
  equipements: any[]
  statuts: any[]
}>()

// Formatage des dates pour les champs datetime-local
const formatDateTimeLocal = (date: string | null) => {
  if (!date) return ''
  return new Date(date).toISOString().slice(0, 16)
}

const form = useForm({
  equipement_id: props.panne.equipement_id,
  statut_demande_id: props.panne.statut_demande_id,
  priorite: props.panne.priorite,
  date_constat: formatDateTimeLocal(props.panne.date_constat),
  date_resolution: formatDateTimeLocal(props.panne.date_resolution),
  description_panne: props.panne.description_panne,
  actions_correctives: props.panne.actions_correctives || ''
})

const submit = () => {
  form.patch(route('pannes.update', props.panne.id), {
    onSuccess: () => {
      router.visit(route('pannes.show', props.panne.id))
    }
  })
}

const quickStatusUpdate = (newStatusId: number) => {
  form.statut_demande_id = newStatusId
  if (newStatusId === 3) {
    // Si on marque comme résolue, on met la date de résolution à maintenant
    form.date_resolution = new Date().toISOString().slice(0, 16)
  }
  
  form.patch(route('pannes.update', props.panne.id), {
    onSuccess: () => {
      router.visit(route('pannes.show', props.panne.id))
    }
  })
}
</script>

<style scoped>
.required::after {
  content: ' *';
  color: #ef4444;
}
</style> 