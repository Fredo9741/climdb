<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Nouveau Modèle de Relevé
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            Créez un nouveau modèle de relevé de mesures
          </p>
        </div>
        <Button 
          @click="$inertia.visit(route('modeles-releves.index'))"
          variant="outline"
          class="flex items-center gap-2"
        >
          <Icon name="ArrowLeft" class="w-4 h-4" />
          Retour à la liste
        </Button>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
          <form @submit.prevent="submit" class="p-6">
            <!-- Informations générales -->
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">
                Informations générales
              </h3>
              
              <div class="grid grid-cols-1 gap-6">
                <div>
                  <Label for="nom" class="text-sm font-medium text-gray-700">
                    Nom du modèle *
                  </Label>
                  <Input
                    id="nom"
                    v-model="form.nom"
                    type="text"
                    class="mt-1"
                    placeholder="Ex: Check-up Climatisation Standard"
                    required
                  />
                  <InputError :message="form.errors.nom" class="mt-2" />
                </div>

                <div>
                  <Label for="description" class="text-sm font-medium text-gray-700">
                    Description
                  </Label>
                  <textarea
                    id="description"
                    v-model="form.description"
                    rows="3"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Description du modèle de relevé..."
                  />
                  <InputError :message="form.errors.description" class="mt-2" />
                </div>
              </div>
            </div>

            <!-- Éléments de mesure -->
            <div class="mb-8">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                  Éléments de mesure
                </h3>
                <Button 
                  @click="addElement"
                  type="button"
                  variant="outline"
                  size="sm"
                  class="flex items-center gap-2"
                >
                  <Icon name="Plus" class="w-4 h-4" />
                  Ajouter un élément
                </Button>
              </div>

              <div v-if="form.elements.length === 0" class="text-center py-8 text-gray-500">
                <Icon name="FileText" class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                <p>Aucun élément de mesure défini</p>
                <p class="text-sm">Cliquez sur "Ajouter un élément" pour commencer</p>
              </div>

              <div v-else class="space-y-4">
                <div 
                  v-for="(element, index) in form.elements" 
                  :key="element.tempId"
                  class="p-4 border border-gray-200 rounded-lg bg-gray-50"
                >
                  <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">
                      Élément {{ index + 1 }}
                    </h4>
                    <Button 
                      @click="removeElement(index)"
                      type="button"
                      variant="outline"
                      size="sm"
                      class="text-red-600 hover:text-red-800"
                    >
                      <Icon name="Trash2" class="w-4 h-4" />
                    </Button>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <Label class="text-sm font-medium text-gray-700">
                        Type de mesure *
                      </Label>
                      <Input
                        v-model="element.type_mesure"
                        type="text"
                        class="mt-1"
                        placeholder="Ex: Température Soufflage"
                        required
                      />
                      <InputError :message="form.errors[`elements.${index}.type_mesure`]" class="mt-1" />
                    </div>

                    <div>
                      <Label class="text-sm font-medium text-gray-700">
                        Unité attendue
                      </Label>
                      <Input
                        v-model="element.unite_attendue"
                        type="text"
                        class="mt-1"
                        placeholder="Ex: °C, bar, L/s"
                      />
                      <InputError :message="form.errors[`elements.${index}.unite_attendue`]" class="mt-1" />
                    </div>

                    <div>
                      <Label class="text-sm font-medium text-gray-700">
                        Emplacement suggéré
                      </Label>
                      <Input
                        v-model="element.emplacement_suggerer"
                        type="text"
                        class="mt-1"
                        placeholder="Ex: Entrée compresseur"
                      />
                      <InputError :message="form.errors[`elements.${index}.emplacement_suggerer`]" class="mt-1" />
                    </div>

                    <div>
                      <Label class="text-sm font-medium text-gray-700">
                        Ordre d'affichage
                      </Label>
                      <Input
                        v-model="element.ordre"
                        type="number"
                        class="mt-1"
                        min="1"
                        placeholder="1, 2, 3..."
                      />
                      <InputError :message="form.errors[`elements.${index}.ordre`]" class="mt-1" />
                    </div>

                    <div>
                      <Label class="text-sm font-medium text-gray-700">
                        Valeur min. attendue
                      </Label>
                      <Input
                        v-model="element.valeur_min_attendue"
                        type="number"
                        step="0.01"
                        class="mt-1"
                        placeholder="Ex: 18"
                      />
                      <InputError :message="form.errors[`elements.${index}.valeur_min_attendue`]" class="mt-1" />
                    </div>

                    <div>
                      <Label class="text-sm font-medium text-gray-700">
                        Valeur max. attendue
                      </Label>
                      <Input
                        v-model="element.valeur_max_attendue"
                        type="number"
                        step="0.01"
                        class="mt-1"
                        placeholder="Ex: 25"
                      />
                      <InputError :message="form.errors[`elements.${index}.valeur_max_attendue`]" class="mt-1" />
                    </div>

                    <div class="md:col-span-2">
                      <Label class="text-sm font-medium text-gray-700">
                        Commentaire / Guide
                      </Label>
                      <textarea
                        v-model="element.commentaire_guide"
                        rows="2"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Instructions pour le technicien..."
                      />
                      <InputError :message="form.errors[`elements.${index}.commentaire_guide`]" class="mt-1" />
                    </div>

                    <div class="md:col-span-2">
                      <div class="flex items-center">
                        <input
                          :id="`obligatoire-${index}`"
                          v-model="element.obligatoire"
                          type="checkbox"
                          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                        <Label :for="`obligatoire-${index}`" class="ml-2 text-sm font-medium text-gray-700">
                          Mesure obligatoire
                        </Label>
                      </div>
                      <InputError :message="form.errors[`elements.${index}.obligatoire`]" class="mt-1" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3">
              <Button 
                @click="$inertia.visit(route('modeles-releves.index'))"
                type="button"
                variant="outline"
              >
                Annuler
              </Button>
              <Button 
                type="submit"
                :disabled="form.processing"
                class="flex items-center gap-2"
              >
                <Icon name="Save" class="w-4 h-4" />
                {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import InputError from '@/components/InputError.vue'
import Icon from '@/components/Icon.vue'

const props = defineProps({
  modele: {
    type: Object,
    required: true
  },
  elements: {
    type: Array,
    default: () => []
  }
})

const form = useForm({
  nom: '',
  description: '',
  elements: []
})

let nextElementId = 1

const addElement = () => {
  form.elements.push({
    tempId: nextElementId++,
    type_mesure: '',
    unite_attendue: '',
    emplacement_suggerer: '',
    valeur_min_attendue: null,
    valeur_max_attendue: null,
    obligatoire: false,
    ordre: form.elements.length + 1,
    commentaire_guide: ''
  })
}

const removeElement = (index) => {
  form.elements.splice(index, 1)
  // Réajuster les ordres
  form.elements.forEach((element, i) => {
    if (!element.ordre || element.ordre === i + 2) {
      element.ordre = i + 1
    }
  })
}

const submit = () => {
  form.post(route('modeles-releves.store'))
}
</script> 