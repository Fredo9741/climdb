<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ modele.nom }}
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            Détails du modèle de relevé
          </p>
        </div>
        <div class="flex items-center space-x-2">
          <Button 
            @click="$inertia.visit(route('modeles-releves.edit', modele.id))"
            variant="default"
            class="flex items-center gap-2"
          >
            <Icon name="Edit" class="w-4 h-4" />
            Modifier
          </Button>
          <Button 
            @click="$inertia.visit(route('modeles-releves.index'))"
            variant="outline"
            class="flex items-center gap-2"
          >
            <Icon name="ArrowLeft" class="w-4 h-4" />
            Retour à la liste
          </Button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
          <div class="p-6">
            <!-- Informations générales -->
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">
                Informations générales
              </h3>
              
              <div class="bg-gray-50 rounded-lg p-4">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Nom</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ modele.nom }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ formatDate(modele.created_at) }}</dd>
                  </div>
                  <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                      {{ modele.description || 'Aucune description' }}
                    </dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Statistiques -->
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">
                Statistiques
              </h3>
              
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-blue-50 rounded-lg p-4">
                  <div class="flex items-center">
                    <Icon name="FileText" class="w-8 h-8 text-blue-600" />
                    <div class="ml-3">
                      <p class="text-sm font-medium text-blue-900">Total Éléments</p>
                      <p class="text-2xl font-bold text-blue-600">{{ modele.elements_modele_releve?.length || 0 }}</p>
                    </div>
                  </div>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                  <div class="flex items-center">
                    <Icon name="CheckCircle" class="w-8 h-8 text-green-600" />
                    <div class="ml-3">
                      <p class="text-sm font-medium text-green-900">Éléments Obligatoires</p>
                      <p class="text-2xl font-bold text-green-600">{{ elementsObligatoires }}</p>
                    </div>
                  </div>
                </div>
                
                <div class="bg-yellow-50 rounded-lg p-4">
                  <div class="flex items-center">
                    <Icon name="AlertTriangle" class="w-8 h-8 text-yellow-600" />
                    <div class="ml-3">
                      <p class="text-sm font-medium text-yellow-900">Avec Plages</p>
                      <p class="text-2xl font-bold text-yellow-600">{{ elementsAvecPlages }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Éléments de mesure -->
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">
                Éléments de mesure ({{ modele.elements_modele_releve?.length || 0 }})
              </h3>

              <div v-if="!modele.elements_modele_releve || modele.elements_modele_releve.length === 0" 
                   class="text-center py-12 text-gray-500">
                <Icon name="FileText" class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                <p>Aucun élément de mesure défini</p>
                <p class="text-sm">Cliquez sur "Modifier" pour ajouter des éléments</p>
              </div>

              <div v-else class="space-y-4">
                <div 
                  v-for="(element, index) in sortedElements" 
                  :key="element.id"
                  class="border border-gray-200 rounded-lg p-4 bg-white"
                >
                  <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-2">
                      <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-medium text-white bg-blue-600 rounded-full">
                        {{ element.ordre || index + 1 }}
                      </span>
                      <h4 class="font-medium text-gray-900">
                        {{ element.type_mesure }}
                      </h4>
                      <span v-if="element.obligatoire" 
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Obligatoire
                      </span>
                    </div>
                    <div class="text-sm text-gray-500">
                      ID: {{ element.id }}
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-if="element.unite_attendue">
                      <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Unité</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ element.unite_attendue }}</dd>
                    </div>

                    <div v-if="element.emplacement_suggerer">
                      <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Emplacement</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ element.emplacement_suggerer }}</dd>
                    </div>

                    <div v-if="element.valeur_min_attendue !== null || element.valeur_max_attendue !== null">
                      <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Plage Attendue</dt>
                      <dd class="mt-1 text-sm text-gray-900">
                        <span v-if="element.valeur_min_attendue !== null">{{ element.valeur_min_attendue }}</span>
                        <span v-if="element.valeur_min_attendue !== null && element.valeur_max_attendue !== null"> - </span>
                        <span v-if="element.valeur_max_attendue !== null">{{ element.valeur_max_attendue }}</span>
                        <span v-if="element.unite_attendue"> {{ element.unite_attendue }}</span>
                      </dd>
                    </div>
                  </div>

                  <div v-if="element.commentaire_guide" class="mt-3 pt-3 border-t border-gray-100">
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Guide / Instructions</dt>
                    <dd class="mt-1 text-sm text-gray-700 italic">{{ element.commentaire_guide }}</dd>
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
              <Button 
                @click="$inertia.visit(route('modeles-releves.index'))"
                variant="outline"
              >
                Retour à la liste
              </Button>
              <Button 
                @click="$inertia.visit(route('modeles-releves.edit', {modeles_relefe: modele.id}))"
                variant="default"
                class="flex items-center gap-2"
              >
                <Icon name="Edit" class="w-4 h-4" />
                Modifier ce modèle
              </Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'

const props = defineProps({
  modele: {
    type: Object,
    required: true
  }
})

const sortedElements = computed(() => {
  if (!props.modele.elements_modele_releve) return []
  
  return [...props.modele.elements_modele_releve].sort((a, b) => {
    const ordreA = a.ordre || 999
    const ordreB = b.ordre || 999
    return ordreA - ordreB
  })
})

const elementsObligatoires = computed(() => {
  if (!props.modele.elements_modele_releve) return 0
  return props.modele.elements_modele_releve.filter(element => element.obligatoire).length
})

const elementsAvecPlages = computed(() => {
  if (!props.modele.elements_modele_releve) return 0
  return props.modele.elements_modele_releve.filter(element => 
    element.valeur_min_attendue !== null || element.valeur_max_attendue !== null
  ).length
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script> 