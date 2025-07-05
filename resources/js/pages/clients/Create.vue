<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center gap-4">
        <Button variant="ghost" @click="router.visit('/clients')" size="sm">
          <Icon name="ArrowLeft" class="h-4 w-4 mr-2" />
          Retour
        </Button>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nouveau client</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">
            Ajoutez un nouveau client à votre base de données
          </p>
        </div>
      </div>
    </template>

    <form @submit.prevent="submit" class="max-w-4xl">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire principal -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Informations de base -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Icon name="User" class="h-5 w-5" />
                Informations de base
              </CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <!-- Type de client -->
              <div>
                <Label for="type">Type de client *</Label>
                <div class="mt-2 grid grid-cols-2 gap-4">
                  <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus:outline-none dark:border-gray-600 dark:bg-gray-800"
                         :class="{ 'border-blue-500 ring-2 ring-blue-500': form.type === 'particulier' }">
                    <input
                      type="radio"
                      value="particulier"
                      v-model="form.type"
                      class="sr-only"
                    />
                    <div class="flex w-full items-center justify-between">
                      <div class="flex items-center">
                        <div class="text-sm">
                          <div class="font-medium text-gray-900 dark:text-white">Particulier</div>
                          <div class="text-gray-500 dark:text-gray-400">Personne physique</div>
                        </div>
                      </div>
                      <Icon name="Check" class="h-5 w-5 text-blue-600" v-if="form.type === 'particulier'" />
                    </div>
                  </label>
                  
                  <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus:outline-none dark:border-gray-600 dark:bg-gray-800"
                         :class="{ 'border-blue-500 ring-2 ring-blue-500': form.type === 'entreprise' }">
                    <input
                      type="radio"
                      value="entreprise"
                      v-model="form.type"
                      class="sr-only"
                    />
                    <div class="flex w-full items-center justify-between">
                      <div class="flex items-center">
                        <div class="text-sm">
                          <div class="font-medium text-gray-900 dark:text-white">Entreprise</div>
                          <div class="text-gray-500 dark:text-gray-400">Personne morale</div>
                        </div>
                      </div>
                      <Icon name="Check" class="h-5 w-5 text-blue-600" v-if="form.type === 'entreprise'" />
                    </div>
                  </label>
                </div>
                <InputError :message="form.errors.type" class="mt-1" />
              </div>

              <!-- Nom -->
              <div>
                <Label for="nom">{{ form.type === 'entreprise' ? 'Raison sociale' : 'Nom complet' }} *</Label>
                <Input
                  id="nom"
                  v-model="form.nom"
                  type="text"
                  class="mt-1"
                  :placeholder="form.type === 'entreprise' ? 'Ex: SARL Climatisation Express' : 'Ex: Jean Dupont'"
                  required
                />
                <InputError :message="form.errors.nom" class="mt-1" />
              </div>

              <!-- Nom d'entreprise pour particulier -->
              <div v-if="form.type === 'particulier'">
                <Label for="entreprise">Entreprise (optionnel)</Label>
                <Input
                  id="entreprise"
                  v-model="form.entreprise"
                  type="text"
                  class="mt-1"
                  placeholder="Ex: SARL Dupont"
                />
                <InputError :message="form.errors.entreprise" class="mt-1" />
              </div>

              <!-- SIRET pour entreprise -->
              <div v-if="form.type === 'entreprise'">
                <Label for="siret">SIRET</Label>
                <Input
                  id="siret"
                  v-model="form.siret"
                  type="text"
                  class="mt-1"
                  placeholder="14 chiffres"
                  maxlength="14"
                />
                <InputError :message="form.errors.siret" class="mt-1" />
              </div>
            </CardContent>
          </Card>

          <!-- Contact -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Icon name="Phone" class="h-5 w-5" />
                Informations de contact
              </CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Email -->
                <div>
                  <Label for="email">Email *</Label>
                  <Input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1"
                    placeholder="contact@exemple.fr"
                    required
                  />
                  <InputError :message="form.errors.email" class="mt-1" />
                </div>

                <!-- Téléphone -->
                <div>
                  <Label for="telephone">Téléphone *</Label>
                  <Input
                    id="telephone"
                    v-model="form.telephone"
                    type="tel"
                    class="mt-1"
                    placeholder="01 23 45 67 89"
                    required
                  />
                  <InputError :message="form.errors.telephone" class="mt-1" />
                </div>
              </div>

              <!-- Téléphone mobile -->
              <div>
                <Label for="telephone_mobile">Téléphone mobile</Label>
                <Input
                  id="telephone_mobile"
                  v-model="form.telephone_mobile"
                  type="tel"
                  class="mt-1"
                  placeholder="06 12 34 56 78"
                />
                <InputError :message="form.errors.telephone_mobile" class="mt-1" />
              </div>
            </CardContent>
          </Card>

          <!-- Adresse -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Icon name="MapPin" class="h-5 w-5" />
                Adresse de facturation
              </CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <!-- Adresse -->
              <div>
                <Label for="adresse">Adresse *</Label>
                <Input
                  id="adresse"
                  v-model="form.adresse"
                  type="text"
                  class="mt-1"
                  placeholder="123 rue de la République"
                  required
                />
                <InputError :message="form.errors.adresse" class="mt-1" />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Code postal -->
                <div>
                  <Label for="code_postal">Code postal *</Label>
                  <Input
                    id="code_postal"
                    v-model="form.code_postal"
                    type="text"
                    class="mt-1"
                    placeholder="75000"
                    maxlength="5"
                    required
                  />
                  <InputError :message="form.errors.code_postal" class="mt-1" />
                </div>

                <!-- Ville -->
                <div class="sm:col-span-2">
                  <Label for="ville">Ville *</Label>
                  <Input
                    id="ville"
                    v-model="form.ville"
                    type="text"
                    class="mt-1"
                    placeholder="Paris"
                    required
                  />
                  <InputError :message="form.errors.ville" class="mt-1" />
                </div>
              </div>

              <!-- Pays -->
              <div>
                <Label for="pays">Pays *</Label>
                <select
                  id="pays"
                  v-model="form.pays"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                  required
                >
                  <option value="France">France</option>
                  <option value="Belgique">Belgique</option>
                  <option value="Suisse">Suisse</option>
                  <option value="Luxembourg">Luxembourg</option>
                </select>
                <InputError :message="form.errors.pays" class="mt-1" />
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Sidebar avec options -->
        <div class="space-y-6">
          <!-- Statut -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Icon name="Settings" class="h-5 w-5" />
                Paramètres
              </CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
              <!-- Client actif -->
              <div class="flex items-center space-x-2">
                <Checkbox
                  id="actif"
                  v-model="form.actif"
                />
                <Label for="actif" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                  Client actif
                </Label>
              </div>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                Les clients inactifs n'apparaîtront pas dans les listes de sélection
              </p>
            </CardContent>
          </Card>

          <!-- Notes -->
          <Card>
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Icon name="FileText" class="h-5 w-5" />
                Notes
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div>
                <Label for="notes">Notes internes</Label>
                <textarea
                  id="notes"
                  v-model="form.notes"
                  rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                  placeholder="Notes pour l'équipe..."
                ></textarea>
                <InputError :message="form.errors.notes" class="mt-1" />
              </div>
            </CardContent>
          </Card>

          <!-- Actions -->
          <Card>
            <CardContent class="pt-6">
              <div class="flex flex-col gap-3">
                <Button type="submit" :disabled="form.processing" class="w-full">
                  <Icon name="Save" class="h-4 w-4 mr-2" />
                  {{ form.processing ? 'Création...' : 'Créer le client' }}
                </Button>
                
                <Button variant="outline" type="button" @click="router.visit('/clients')" class="w-full">
                  <Icon name="X" class="h-4 w-4 mr-2" />
                  Annuler
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </form>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Checkbox } from '@/components/ui/checkbox'
import InputError from '@/components/InputError.vue'
import Icon from '@/components/Icon.vue'

// Formulaire réactif
const form = useForm({
  type: 'particulier',
  nom: '',
  entreprise: '',
  siret: '',
  email: '',
  telephone: '',
  telephone_mobile: '',
  adresse: '',
  code_postal: '',
  ville: '',
  pays: 'France',
  actif: true,
  notes: ''
})

// Soumettre le formulaire
const submit = () => {
  form.post('/clients', {
    onSuccess: () => {
      // Rediriger vers la liste des clients
      router.visit('/clients')
    }
  })
}
</script> 