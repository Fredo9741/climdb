<template>
  <AppLayout title="Nouveau Technicien">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
          Nouveau Technicien
        </h2>
        <Button @click="router.visit(route('admin.techniciens.index'))" variant="outline">
          <Icon name="arrow-left" class="h-4 w-4 mr-2" />
          Retour
        </Button>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <CardTitle>Informations du Technicien</CardTitle>
            <CardDescription>
              Créez un nouveau compte technicien avec ses habilitations.
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Informations de base -->
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <Label for="name">Nom complet *</Label>
                  <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    :class="{ 'border-red-500': errors.name }"
                  />
                  <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ errors.name }}
                  </p>
                </div>

                <div>
                  <Label for="email">Email *</Label>
                  <Input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    :class="{ 'border-red-500': errors.email }"
                  />
                  <p v-if="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ errors.email }}
                  </p>
                </div>

                <div>
                  <Label for="telephone">Téléphone</Label>
                  <Input
                    id="telephone"
                    v-model="form.telephone"
                    type="tel"
                    placeholder="06 12 34 56 78"
                  />
                  <p v-if="errors.telephone" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ errors.telephone }}
                  </p>
                </div>

                <div>
                  <Label for="qualification">Qualification</Label>
                  <input
                    list="qualification-list"
                    id="qualification"
                    v-model="form.qualification"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                    placeholder="Sélectionner ou saisir"
                  />
                  <datalist id="qualification-list">
                    <option v-for="q in qualifications" :key="q.id" :value="q.nom">
                      {{ q.nom }}
                    </option>
                  </datalist>
                  <p v-if="errors.qualification" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ errors.qualification }}
                  </p>
                </div>
              </div>

              <!-- Habilitations -->
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="text-lg font-medium">Habilitations</h3>
                  <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    @click="addHabilitation"
                  >
                    <Icon name="plus" class="h-4 w-4 mr-2" />
                    Ajouter une habilitation
                  </Button>
                </div>

                <div v-if="form.habilitations.length === 0" class="text-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                  <Icon name="certificate" class="mx-auto h-12 w-12 text-gray-400" />
                  <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Aucune habilitation ajoutée
                  </p>
                  <p class="text-xs text-gray-400 dark:text-gray-500">
                    Les habilitations peuvent être ajoutées plus tard
                  </p>
                </div>

                <div v-else class="space-y-4">
                  <div
                    v-for="(habilitation, index) in form.habilitations"
                    :key="index"
                    class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg"
                  >
                    <div class="flex items-center justify-between mb-4">
                      <h4 class="font-medium">Habilitation {{ index + 1 }}</h4>
                      <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        @click="removeHabilitation(index)"
                        class="text-red-600 hover:text-red-700"
                      >
                        <Icon name="trash" class="h-4 w-4" />
                      </Button>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                      <div>
                        <Label :for="`habilitation-${index}`">Type d'habilitation *</Label>
                        <select
                          :id="`habilitation-${index}`"
                          v-model="habilitation.habilitation_id"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                          required
                        >
                          <option value="">Sélectionner une habilitation</option>
                          <option
                            v-for="hab in habilitations"
                            :key="hab.id"
                            :value="hab.id"
                          >
                            {{ hab.nom }} ({{ hab.duree_validite_mois }} mois)
                          </option>
                        </select>
                      </div>

                      <div>
                        <Label :for="`date-obtention-${index}`">Date d'obtention *</Label>
                        <Input
                          :id="`date-obtention-${index}`"
                          v-model="habilitation.date_obtention"
                          type="date"
                          required
                        />
                      </div>

                      <div>
                        <Label :for="`numero-certificat-${index}`">Numéro de certificat</Label>
                        <Input
                          :id="`numero-certificat-${index}`"
                          v-model="habilitation.numero_certificat"
                          type="text"
                          placeholder="ex: CERT-2024-001"
                        />
                      </div>

                      <div>
                        <Label :for="`commentaires-${index}`">Commentaires</Label>
                        <Input
                          :id="`commentaires-${index}`"
                          v-model="habilitation.commentaires"
                          type="text"
                          placeholder="Informations complémentaires"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Informations sur le mot de passe -->
              <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex items-start">
                  <Icon name="info" class="h-5 w-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3" />
                  <div>
                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">
                      Mot de passe automatique
                    </h4>
                    <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                      Un mot de passe sécurisé sera généré automatiquement et affiché après la création du compte.
                    </p>
                  </div>
                </div>
              </div>

              <!-- Boutons d'action -->
              <div class="flex justify-end gap-4">
                <Button
                  type="button"
                  variant="outline"
                  @click="router.visit(route('admin.techniciens.index'))"
                >
                  Annuler
                </Button>
                <Button type="submit" :disabled="processing">
                  <Icon v-if="processing" name="loader" class="h-4 w-4 mr-2 animate-spin" />
                  <Icon v-else name="save" class="h-4 w-4 mr-2" />
                  {{ processing ? 'Création...' : 'Créer le technicien' }}
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import Icon from '@/components/Icon.vue'

interface Habilitation {
  id: number
  nom: string
  description: string
  duree_validite_mois: number
}

interface Qualification {
  id: number
  nom: string
  description: string | null
}

interface Props {
  habilitations: Habilitation[]
  qualifications: Qualification[]
  errors: Record<string, string>
}

const props = defineProps<Props>()

const form = useForm({
  name: '',
  email: '',
  telephone: '',
  qualification: '',
  habilitations: [] as Array<{
    habilitation_id: string
    date_obtention: string
    numero_certificat: string
    commentaires: string
  }>
})

const processing = ref(false)

const submit = () => {
  processing.value = true
  form.post(route('admin.techniciens.store'), {
    onFinish: () => {
      processing.value = false
    }
  })
}

const addHabilitation = () => {
  form.habilitations.push({
    habilitation_id: '',
    date_obtention: '',
    numero_certificat: '',
    commentaires: ''
  })
}

const removeHabilitation = (index: number) => {
  form.habilitations.splice(index, 1)
}
</script> 